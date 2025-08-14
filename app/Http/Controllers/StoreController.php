<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stores = Store::all();

            $transformedStores = $stores->map(function ($store) {
                $apiKey = '';
                $apiSecret = null;
                try {
                    $apiKey = Crypt::decryptString($store->api_key);
                    if ($store->api_secret) {
                        $apiSecret = Crypt::decryptString($store->api_secret);
                    }
                } catch (\Exception $e) {

                }

                return [
                    'id'            => $store->id,
                    'store_name'    => $store->store_name,
                    'store_url'     => $store->store_url,
                    'platform'      => $store->platform,
                    'api_key'       => $this->maskKey($apiKey),
                    'api_secret'    => $apiSecret ? $this->maskKey($apiSecret) : null,
                    'status'        => $store->status,
                ];
            });

            return response()->json($transformedStores);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch stores'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'platform'   => ['required', 'string', Rule::in(['woocommerce', 'shopify'])],
            'store_url'  => ['required', 'string'],
            'api_key'    => ['required', 'string'],
            'api_secret' => ['nullable', 'string', 'required_if:platform,woocommerce'],
        ]);

        $correctCreds = $this->checkCredentialsFromRequest($validated);

        if (!$correctCreds) {
            return response()->json([
                'message' => 'Credentials could not be validated'
            ], 422);
        };

        $store = Store::create([
            'store_name'        => $validated['store_name'],
            'platform'          => $validated['platform'],
            'store_url'         => $validated['store_url'],
            'api_key'           => Crypt::encryptString($validated['api_key']),
            'api_secret'        => isset($validated['api_secret']) ? Crypt::encryptString($validated['api_secret']) : null,
            'status'            => true,
            'last_checked_at'   => now(),
        ]);

        $storeResponse = $store->toArray();
        $storeResponse['api_key'] = $this->maskKey($validated['api_key']);
        $storeResponse['api_secret'] = isset($validated['api_secret']) ? $this->maskKey($validated['api_secret']) : null;

        return response()->json([
            'message'   => 'Store saved and credentials verified',
            'valid'     => true,
            'store'     => $storeResponse,
        ], 201);
    }

    /**
     * Re-check an existing store's credentials
    */
    public function check(Store $store) 
    {
        try {
            $apiKey = Crypt::decryptString($store->api_key);
            $apiSecret = $store->api_secret ? Crypt::decryptString($store->api_secret) : null;
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Stored credentials are corrupted or cannot be decrypted.' ], 500);
        }

        $isValid = false; 

        if ($store->platform === 'woocommerce') {
            $isValid = $this->checkWooCommerceByValues($store->store_url, $apiKey, $apiSecret);
        } elseif ($store->platform === 'shopify') {
            $isValid = $this->checkShopifyByValues($store->store_url, $apiKey);
        }

        $store->status = $isValid;
        $store->last_checked_at = now();
        $store->save();

        return response()->json(['valid' => $isValid]);
    }

    /**
     * Helper: check credentials based on request array before storing
     */
    private function checkCredentialsFromRequest(array $data): bool
    {
        if ($data['platform'] === 'woocommerce') {
            return $this->checkWooCommerceByValues($data['store_url'], $data['api_key'], $data['api_secret'] ?? null);
        }

        return $this->checkShopifyByValues($data['store_url'], $data['api_key']);
    }

    /**
     * WooCommerce check using Basic Auth (consumer_key / consumer_secret).
     */
    private function checkWooCommerceByValues(string $storeUrl, ?string $key, ?string $secret): bool 
    {
        if (!$storeUrl || !$key || !$secret) {
            return false;
        }

        if (!str_starts_with($storeUrl, 'http://') && !str_starts_with($storeUrl,
            'https://')) {
                $storeUrl = 'https://' . $storeUrl;
        }

        $endpoint = rtrim($storeUrl, '/') . '/wp-json/wc/v3/products?per_page=1';

        try {
            $response = Http::timeout(30)->withBasicAuth($key, $secret)->get($endpoint);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * Shopify check using access token in header.
     */
    private function checkShopifyByValues(string $storeUrl, ?string $accessToken): bool
    {
        if (!$storeUrl || !$accessToken) {
            return false;
        }

        $storeUrl = str_replace(['http://', 'https://'], '', $storeUrl);

        if (!str_contains($storeUrl, 'myshopify.com')) {
            $storeUrl = rtrim($storeUrl, '/') . '.myshopify.com';
        }


        $endpoint = 'https://' . $storeUrl . '/admin/api/2023-07/products.json?limit=1';

        try {
            $response = Http::timeout(30)->withHeaders(['X-Shopify-Access-Token' => $accessToken])->get($endpoint);
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Hide the API key
     */
    private function maskKey(string $key): string 
    {
        if (empty($key)){
            return '';
        }

        $len = strlen($key);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }

        $lastFour = substr($key, -4);
        $maxStars = 11;
        $starsNeeded = min($maxStars, $len -4);
        return str_repeat('*', $starsNeeded) . $lastFour;
    }

    /**
     * Toggle store status (active/inactive)
     */
    public function updateStatus(Request $request, Store $store)
    {
        $validated = $request->validate([
            'status' => 'required|boolean'
        ]);
        
        $store->status = $validated['status'];
        $store->save();
        
        return response()->json([
            'message' => 'Status updated successfully',
            'status' => $store->status
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
