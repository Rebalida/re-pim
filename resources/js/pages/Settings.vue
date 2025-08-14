<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 space-y-4 md:space-y-0">
      <div class="space-y-2">
        <h3 class="text-3xl font-bold">Settings</h3>
        <p class="text-sm text-gray-400">Manage connected stores</p>
      </div>
      <button
       @click="openModal"
       class="bg-sky-500 hover:bg-sky-700 text-white px-4 py-2 rounded-lg transition duration-200"
      >
        Add Store
      </button>
    </div>

    <!-- Loading State -->
    <div 
     v-if="loading"
     class="text-center py-8"
     >
      <div class="text-sky-400">Loading stores</div>
    </div>

    <!-- Empty State -->
    <div 
     v-else-if="stores.length === 0"
     class="text-center py-12"
    >
      <div class="text-gray-400 mb-4">No stores connected yet</div>
      <button
       @click="openModal"
       class="bg-sky-500 hover:bg-sky-700 text-white px-6 py-3 rounded-lg transition duration-200"
      >
        Add Your First Store
      </button>
    </div>

    <!-- Strores List -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div
      v-for="store in stores"
      :key="store.id"
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
            @click="toggleStoreStatus(store)"
            class="sr-only peer"
          />
          <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-500 transition-all"></div>
          <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-5"></div>
        </label>
      </div>

      <p class="text-gray-400 text-sm">{{ store.store_url }}</p>
      <p class="text-gray-500 text-sm">API Key: {{ store.api_key }}</p>
      <p v-if="store.api_secret" class="text-gray-500 text-sm">API Secret: {{ store.api_secret }}</p>
    </div>
    </div>

     <!-- Modal -->
    <div
     v-if="isModalOpen"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
     <div class="bg-gray-900 p-6 rounded-xl w-full max-w-md shadow-lg border border-gray-800">

      <div
       v-if="saving"
       class="text-center mb-4 text-sky-400"
      >
        Checking credentials
      </div>
      <div class="flex items-center justify-between mb-5">
        <h3 class="text-2xl font-semibold text-white">Add New Store</h3>
        <button @click="closeModal" class="text-gray-400 hover:text-white text-xl">
          &times;
        </button>
      </div>

      <!-- Store Name -->
       <input
        v-model="newStoreName"
        type="text"
        placeholder="Store Name"
        class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 placeholder-gray-500"
       />
      
      <!-- Store Domain -->
       <input
        v-model="newStoreDomain" 
        type="text"
        placeholder="Store Domain (e.g. mystore.myshopify.com)"
        class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 placeholder-gray-500"
       />

      <!-- Store Type -->
       <div class="flex mb-4 space-x-4">
        <button
         @click="newStoreType = 'shopify'"
         :class="[
            'flex-1 px-4 py-2 rounded-lg border transition font-medium',
            newStoreType === 'shopify'
              ? 'bg-emerald-600 border-emerald-500 text-white'
              : 'bg-gray-800 border-gray-700 text-gray-400 hover:bg-gray-700'
         ]"
        >
          Shopify
        </button>
        <button
         @click="newStoreType = 'woocommerce'"
         :class="[
            'flex-1 px-4 py-2 rounded-lg border transition font-medium',
            newStoreType === 'woocommerce'
              ? 'bg-purple-600 border-purple-500 text-white'
              : 'bg-gray-800 border-gray-700 text-gray-400 hover:bg-gray-700'
         ]"
        >
          WooCommerce
        </button>
       </div>

       <!-- API Key -->
        <input
         v-model="newStoreKey"
         type="text"
         :placeholder="newStoreType === 'shopify'
                        ? 'shpat_xxxxxxxxxx'
                        : newStoreType === 'woocommerce'
                          ? 'ck_xxxxxxxxxx'
                          : 'Select store type first'"
         class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 placeholder-gray-500"
        />

      <!-- API Secret -->
       <input
        v-if="newStoreType === 'woocommerce'"
        v-model="newStoreSecret"
        type="text" 
        placeholder="cs_xxxxxxxxxxxxxxxx"
        class="w-full mb-4 px-4 py-2 rounded-lg bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-sky-500 placeholder-gray-500"
       />
      <div class="flex justify-end space-x-2 mt-2">
        <button
         @click="closeModal"
         class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition"
        >
          Cancel
        </button>
        <button
         @click="saveStore"
         class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-lg transition"
        >
          Save
        </button>
      </div>
     </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import shopifyLogo from '@/../images/shopify-logo.png';
import woocommerceLogo from '@/../images/woocommerce-logo.png';
import axios from 'axios';

const isModalOpen = ref(false);
const saving = ref(false);
const loading = ref(true);
const stores = ref([])

// Form fields
const newStoreName = ref ('');
const newStoreDomain = ref ('');
const newStoreKey = ref ('');
const newStoreSecret = ref ('');
const newStoreType = ref ('');

const fetchStores = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/stores');
    stores.value = response.data;
    console.log('Fetched stores:', response.data);
  } catch (err) {
    console.error('Error fetching stores:', err);
    alert('Failed to load stores');
  } finally {
    loading.value = false;
  }
}

const toggleStoreStatus = async (store) => {
  store.status = !store.status;
  
  try {
    await axios.put(`/api/stores/${store.id}/status`, {
      status: store.status
    });

  } catch (error) {
    store.status = !store.status;
    console.error('Failed to update status:', error);
    alert('Failed to update store status');
  }
};

onMounted(() => {
  fetchStores();
});

const openModal = () => {
  newStoreName.value = '';
  newStoreDomain.value = '';
  newStoreKey.value = '';
  newStoreSecret.value = '';
  newStoreType.value = '';

  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveStore = async () => {
  saving.value = true;
  try {
    const { data } = await axios.post('/api/stores', {
      store_name:   newStoreName.value,
      platform:     newStoreType.value,
      store_url:    newStoreDomain.value,
      api_key:      newStoreKey.value,
      api_secret:   newStoreSecret.value
    });
    stores.value.push(data.store);
    alert(data.message);
    closeModal();
  } catch (err) {
    if (err.response && err.response.status === 422) {
      alert(err.response.data.message || 'Invalid credentials');
    } else {
      alert('An unexpected error occurred');
    }
  } finally {
    saving.value = false;
  }
}

</script>
