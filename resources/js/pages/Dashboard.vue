<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="space-y-2">
        <h2 class="text-3xl font-bold">Dashboard</h2>
        <p class="text-sm text-gray-400">Overview of the stores and products</p>
      </div>
    </div>
  
    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div
          v-for="stat in stats"
          :key="stat.title"
          class="bg-gray-800 bg-opacity-40 backdrop-blur-md border border-gray-700/50 rounded-lg p-6 flex items-center justify-between hover:shadow-lg transition"
        >
          <div>
            <p class="text-sm text-gray-400">{{ stat.title }}</p>
            <p class="text-3xl font-bold">{{ stat.value }}</p>
          </div>
            
          <div
            :class="['p-3 rounded-md', stat.bgColor]"
          >
            <component :is="stat.icon" class="w-6 h-6 text-white"/>
          </div>
        </div>
    </div>
  
    <!-- Chart and Activitiy -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  
      <!-- Chart -->
      <div class="bg-gray-800 bg-opacity-40 backdrop-blur-md border border-gray-700/50 p-6 rounded-lg lg:col-span-2">
        <h3 class="text-lg font-semibold mb-4">Products Over Time</h3>
        <ApexChart
          type="area"
          height="300"
          :options="chartOptions"
          :series="chartSeries"
        />
      </div>
  
      <!-- Activities -->
       <div class="bg-gray-800 bg-opacity-40 backdrop-blur-md border border-gray-700/50 p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <ul class="space-y-4">
          <li
            v-for="item in recentActivity"
            :key="item.id"
            class="flex items-center justify-between border-b border-gray-700 pb-2"
          >
            <span>{{ item.text }}</span>
            <span class="text-xs text-gray-400">{{ item.name }}</span>
          </li>
        </ul>
       </div>
    </div>
  </div>

</template>

<script setup>
import ApexChart from 'vue3-apexcharts'
import { ShoppingBagIcon, BuildingStorefrontIcon, ArrowUpTrayIcon } from '@heroicons/vue/16/solid';
import { ref } from 'vue';

const stats = ref([
    { title: 'Total Products', value: 120, icon: ShoppingBagIcon, bgColor: 'bg-rose-600' },
    { title: 'Total Stores', value: 5, icon: BuildingStorefrontIcon, bgColor: 'bg-sky-600' },
    { title: 'Exports', value: 56, icon: ArrowUpTrayIcon, bgColor: 'bg-emerald-600' },
]);

const chartOptions = ref({
  chart: { toolbar: { show: false } },
  xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] },
  theme: { mode: 'dark' },
  dataLabels: { enabled: false },
  stroke: { curve: 'smooth' }
})

const chartSeries = ref([ 
  { name: 'Products', data: [10, 20, 40, 60, 90, 120] }
])

const recentActivity = ref([
  { id: 1, text: 'Added new product: Hoodie', time: '2h ago' },
  { id: 2, text: 'Connected new store', time: '5h ago' },
  { id: 3, text: 'Exported 12 products', time: '1d ago' }
])

</script>
