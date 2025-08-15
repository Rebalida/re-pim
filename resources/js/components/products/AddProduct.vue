<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center mb-8 sm:justify-between gap-4">
      <div class="space-y-2">
        <h2 class="text-3xl font-bold">Add Product</h2>
        <p class="text-sm text-gray-400">Manage your products across stores</p>
      </div>
      <button
        @click="saveProduct"
        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg w-full sm:w-auto"
      >
        Save Product
      </button>
    </div>

    <!-- Two-column responsive layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Left Column -->
      <div class="space-y-6">
        <!-- Basic Details -->
        <div class="bg-gray-800 p-6 rounded-lg space-y-4">
          <h2 class="text-lg font-semibold">Basic Details</h2>

          <div>
            <label class="block text-sm mb-1">Title</label>
            <input
              v-model="product.title"
              type="text"
              class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2"
            />
          </div>

          <div>
            <label class="block text-sm mb-1">Description</label>
            <textarea
              v-model="product.description"
              rows="4"
              class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2"
            ></textarea>
          </div>

          <!-- Images -->
          <div>
            <label class="block text-sm mb-1">Images</label>
            <div
              class="border-2 border-dashed border-gray-600 p-4 rounded-lg text-center cursor-pointer"
              @click="$refs.fileInput.click()"
            >
              <p>Click to upload images</p>
              <input
                type="file"
                ref="fileInput"
                class="hidden"
                multiple
                @change="handleImageUpload"
              />
            </div>
            <div class="mt-4 flex gap-4 flex-wrap">
              <div
                v-for="(img, idx) in product.images"
                :key="idx"
                class="w-20 h-20 relative"
              >
                <img
                  :src="img"
                  class="w-full h-full object-cover rounded-lg"
                />
                <button
                  @click="removeImage(idx)"
                  class="absolute top-0 right-0 bg-red-600 rounded-full p-1"
                >
                  <XMarkIcon class="w-4 h-4 text-white" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pricing -->
        <div class="bg-gray-800 p-6 rounded-lg space-y-4">
          <h2 class="text-lg font-semibold">Pricing</h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm mb-1">Price</label>
              <input v-model="product.price" type="number" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Compare-at Price</label>
              <input v-model="product.compare_at_price" type="number" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Cost per Item</label>
              <input v-model="product.cost" type="number" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
          </div>

          <!-- Tax Toggle -->
          <div class="flex items-center justify-between flex-wrap gap-2">
            <span>Charge tax on this product</span>
            <button
              type="button"
              :class="product.charge_tax ? 'bg-purple-600' : 'bg-gray-600'"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
              @click="product.charge_tax = !product.charge_tax"
            >
              <span
                :class="product.charge_tax ? 'translate-x-6' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
              />
            </button>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-6">
        <!-- Inventory -->
        <div class="bg-gray-800 p-6 rounded-lg space-y-4">
          <h2 class="text-lg font-semibold">Inventory</h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm mb-1">SKU</label>
              <input v-model="product.sku" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Barcode</label>
              <input v-model="product.barcode" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Quantity</label>
              <input v-model="product.quantity" type="number" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
          </div>
        </div>

        <!-- Organization -->
        <div class="bg-gray-800 p-6 rounded-lg space-y-4">
          <h2 class="text-lg font-semibold">Organization</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm mb-1">Product Type</label>
              <input v-model="product.type" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Vendor</label>
              <input v-model="product.vendor" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Collections / Categories</label>
              <input v-model="product.collections" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
            <div>
              <label class="block text-sm mb-1">Tags</label>
              <input v-model="product.tags" type="text" class="w-full rounded-lg bg-gray-700 border border-gray-600 p-2" />
            </div>
          </div>

          <!-- Status Toggle -->
          <div class="flex items-center justify-between flex-wrap gap-2">
            <span>Status: {{ product.active ? 'Active' : 'Draft' }}</span>
            <button
              type="button"
              :class="product.active ? 'bg-green-600' : 'bg-gray-600'"
              class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
              @click="product.active = !product.active"
            >
              <span
                :class="product.active ? 'translate-x-6' : 'translate-x-1'"
                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
              />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref } from "vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";

const product = ref({
  title: "",
  description: "",
  images: [],
  price: null,
  compare_at_price: null,
  cost: null,
  charge_tax: false,
  sku: "",
  barcode: "",
  quantity: 0,
  type: "",
  vendor: "",
  collections: "",
  tags: "",
  active: false,
});

const handleImageUpload = (e) => {
  const files = Array.from(e.target.files);
  files.forEach((file) => {
    const reader = new FileReader();
    reader.onload = (event) => {
      product.value.images.push(event.target.result);
    };
    reader.readAsDataURL(file);
  });
};

const removeImage = (index) => {
  product.value.images.splice(index, 1);
};

const saveProduct = () => {
  console.log("Saving product:", product.value);
  // TODO: send to backend
};
</script>
