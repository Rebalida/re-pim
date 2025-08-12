<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Settings</h2>

    <form class="bg-gray-800 p-4 rounded space-y-4" @submit.prevent="save">
      <div>
        <label class="block text-sm text-gray-300 mb-1">Store URL</label>
        <input v-model="store.url" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="store-name.myshopify.com" />
      </div>

      <div>
        <label class="block text-sm text-gray-300 mb-1">API Token</label>
        <input v-model="store.token" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="shpat_xxx" />
      </div>

      <div class="flex gap-2">
        <button type="submit" class="bg-green-600 px-4 py-2 rounded">Save (local)</button>
        <button type="button" class="bg-gray-700 px-4 py-2 rounded" @click="clear">Clear</button>
      </div>
    </form>

    <p class="text-sm text-gray-400 mt-3">This is static for now — clicking Save stores the values in local component state only.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      store: { url: '', token: '' }
    }
  },
  methods: {
    save() {
      // For now keep values in component state
      // Later we will POST to /api/stores to persist them
      alert('Saved locally: ' + this.store.url);
    },
    clear() {
      this.store.url = '';
      this.store.token = '';
    }
  }
}
</script>
