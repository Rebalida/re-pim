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
    <div v-if="loading" class="text-center py-8">
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
      <StoreCard 
       v-for="store in stores"
       :key="store.id"
       :store="store"
       @toggle-status="toggleStoreStatus"
      />
    </div>

    <!-- Modal -->
    <Modal
      v-model:isOpen="isModalOpen"
      v-model:name="newStoreName"
      v-model:domain="newStoreDomain"
      v-model:apiKey="newStoreKey"
      v-model:apiSecret="newStoreSecret"
      v-model:platform="newStoreType"
      :saving="saving"
      :saveSuccess="saveSuccess"
      :saveError="saveError"
      @save="saveStore"
    />

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import StoreCard from '../components/settings/StoreCard.vue';
import Modal from '../components/settings/Modal.vue';

const isModalOpen = ref(false);
const saving = ref(false);
const saveSuccess = ref(false);
const saveError = ref('');
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
  } catch (err) {
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
  saveError.value = '';
  saveSuccess.value = false;
  saving.value = false;

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
  saveError.value = '';
  saveSuccess.value = false;

  try {
    const { data } = await axios.post('/api/stores', {
      store_name:   newStoreName.value,
      platform:     newStoreType.value,
      store_url:    newStoreDomain.value,
      api_key:      newStoreKey.value,
      api_secret:   newStoreSecret.value
    });

    saveSuccess.value = true;
    stores.value.push(data.store);

    setTimeout(() => {
      closeModal();
    }, 1500);

  } catch (err) {
    if (err.response && err.response.status === 422) {
      saveError.value = err.response.data.message || 'Invalid credentials';
    } else {
      saveError.value = 'An unexpected error occurred';
    }
  } finally {
    saving.value = false;
  }
};

</script>
