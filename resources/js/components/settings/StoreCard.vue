<template>
  <div
    class="bg-gray-800 bg-opacity-40 backdrop-blur-md border border-gray-700/50 p-6 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 space-y-2"
  >
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <img
          v-if="store.platform === 'shopify'"
          :src="shopifyLogo"
          alt="Shopify"
          class="w-10 h-10"
        />
        <img
          v-else-if="store.platform === 'woocommerce'"
          :src="woocommerceLogo"
          alt="Woocommerce"
          class="w-10 h-10"
        />
        <h3 class="text-xl font-semibold">{{ store.store_name }}</h3>
      </div>

      <label class="relative inline-flex items-center cursor-pointer">
        <input
          type="checkbox"
          :checked="store.status"
          @click="$emit('toggle-status', store)"
          class="sr-only peer"
        />
        <div
          class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-500 transition-all"
        ></div>
        <div
          class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-5"
        ></div>
      </label>
    </div>

    <p class="text-gray-400 text-sm">{{ store.store_url }}</p>
    <p class="text-gray-500 text-sm">API Key: {{ store.api_key }}</p>
    <p v-if="store.api_secret" class="text-gray-500 text-sm">
      API Secret: {{ store.api_secret }}
    </p>
  </div>
</template>

<script setup>
import shopifyLogo from '@/../images/shopify-logo.png';
import woocommerceLogo from '@/../images/woocommerce-logo.png';

defineProps({
    store: {
        type: Object,
        required: true
    }
});

defineEmits(['toggle-status'])

</script>

