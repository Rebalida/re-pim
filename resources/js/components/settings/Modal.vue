<template>
  <!-- Only render the overlay when open -->
  <div v-if="isOpenProxy" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg border border-gray-800">

      <!-- Loading state -->
      <div v-if="saving && !saveSuccess && !saveError" class="text-center py-8">
        <svg class="animate-spin h-10 w-10 text-sky-400 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        <div class="text-sky-400">Checking credentials...</div>
      </div>

      <!-- Success state -->
      <div v-else-if="saveSuccess" class="text-center py-8">
        <svg class="h-12 w-12 text-green-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <div class="text-green-400">Store added successfully!</div>
      </div>

      <!-- Form state -->
      <div v-else>
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-2xl font-semibold text-white">Add New Store</h3>
          <button @click="isOpenProxy = false" class="text-gray-400 hover:text-white text-xl">&times;</button>
        </div>

        <div v-if="saveError" class="text-red-400 mb-4">{{ saveError }}</div>

        <input v-model="nameProxy" type="text" placeholder="Store Name"
               class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700" />

        <input v-model="domainProxy" type="text" placeholder="Store Domain (e.g. mystore.myshopify.com)"
               class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700" />

        <!-- Store Type -->
        <div class="flex mb-4 space-x-4">
          <button @click="platformProxy = 'shopify'" :class="storeTypeClass('shopify')">Shopify</button>
          <button @click="platformProxy = 'woocommerce'" :class="storeTypeClass('woocommerce')">WooCommerce</button>
        </div>

        <input
          v-model="apiKeyProxy"
          type="text"
          :placeholder="platformProxy === 'shopify'
              ? 'shpat_xxxxxxxxxx'
              : platformProxy === 'woocommerce'
                ? 'ck_xxxxxxxxxx'
                : 'Select store type first'"
          class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700"
        />

        <input
          v-if="platformProxy === 'woocommerce'"
          v-model="apiSecretProxy"
          type="text"
          placeholder="cs_xxxxxxxxxxxxxxxx"
          class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700"
        />

        <div class="flex justify-end space-x-2 mt-2">
          <button @click="isOpenProxy = false" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg">Cancel</button>
          <button @click="$emit('save')" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  isOpen:     { type: Boolean, default: false },
  saving:     { type: Boolean, default: false },
  saveSuccess:{ type: Boolean, default: false },
  saveError:  { type: String,  default: '' },
  name:       { type: String,  default: '' },
  domain:     { type: String,  default: '' },
  apiKey:     { type: String,  default: '' },
  apiSecret:  { type: String,  default: '' },
  platform:   { type: String,  default: '' },
})

const emit = defineEmits([
  'update:isOpen',
  'update:name',
  'update:domain',
  'update:apiKey',
  'update:apiSecret',
  'update:platform',
  'save'
])

const isOpenProxy = computed({
  get: () => props.isOpen,
  set: v  => emit('update:isOpen', v)
})
const nameProxy = computed({
  get: () => props.name,
  set: v  => emit('update:name', v)
})
const domainProxy = computed({
  get: () => props.domain,
  set: v  => emit('update:domain', v)
})
const apiKeyProxy = computed({
  get: () => props.apiKey,
  set: v  => emit('update:apiKey', v)
})
const apiSecretProxy = computed({
  get: () => props.apiSecret,
  set: v  => emit('update:apiSecret', v)
})
const platformProxy = computed({
  get: () => props.platform,
  set: v  => emit('update:platform', v)
})

const storeTypeClass = (type) => [
  'flex-1 px-4 py-2 rounded-lg border transition font-medium',
  platformProxy.value === type
    ? (type === 'shopify'
        ? 'bg-emerald-600 border-emerald-500 text-white'
        : 'bg-purple-600 border-purple-500 text-white')
    : 'bg-gray-800 border-gray-700 text-gray-400 hover:bg-gray-700'
]
</script>
