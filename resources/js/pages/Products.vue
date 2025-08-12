<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-semibold">Products</h2>
      <button class="bg-blue-600 px-4 py-2 rounded">Create product</button>
    </div>

    <div class="bg-gray-800 rounded p-4">
      <p class="text-gray-400 mb-4">Product table placeholder (static)</p>
      <table class="min-w-full text-left">
        <thead class="text-gray-400">
          <tr><th class="p-2">Title</th><th class="p-2">Price</th><th class="p-2">Stores</th></tr>
        </thead>
        <tbody>
          <tr class="border-t border-gray-700">
            <td class="p-2">Sample product</td>
            <td class="p-2">$0.00</td>
            <td class="p-2">—</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
