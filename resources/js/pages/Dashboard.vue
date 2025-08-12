<template>
  <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-3xl font-bold">Dashboard</h2>
        <p class="text-sm text-gray-400">Overview of your stores and products</p>
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div
        v-for="stat in stats"
        :key="stat.title"
        class="bg-gray-800 rounded-lg p-6 flex items-center justify-between hover:shadow-lg transition"
      >
        <div>
          <p class="text-sm text-gray-400">{{ stat.title }}</p>
          <p class="text-3xl font-bold">{{ stat.value }}</p>
        </div>
        <div :class="['p-3 rounded-md', stat.bgColor]">
          <component :is="stat.icon" class="w-6 h-6 text-white" />
        </div>
      </div>
    </div>

    <!-- Chart + Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Chart -->
      <div class="bg-gray-800 p-6 rounded-lg lg:col-span-2">
        <h3 class="text-lg font-semibold mb-4">Products Over Time</h3>
        <ApexChart
          type="line"
          height="300"
          :options="chartOptions"
          :series="chartSeries"
        />
      </div>

      <!-- Recent Activity -->
      <div class="bg-gray-800 p-6 rounded-lg">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <ul class="space-y-4">
          <li
            v-for="item in recentActivity"
            :key="item.id"
            class="flex items-center justify-between border-b border-gray-700 pb-2"
          >
            <span>{{ item.text }}</span>
            <span class="text-xs text-gray-400">{{ item.time }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import ApexChart from 'vue3-apexcharts' // ✅ Import component with correct name
import { ShoppingBagIcon, BuildingStorefrontIcon, ArrowUpTrayIcon } from '@heroicons/vue/16/solid'
import { ref } from 'vue'

// ✅ Register component locally (script setup auto-registers imports as template components)
const stats = ref([
  { title: 'Total Products', value: 120, icon: ShoppingBagIcon, bgColor: 'bg-red-600' },
  { title: 'Total Stores', value: 4, icon: BuildingStorefrontIcon, bgColor: 'bg-blue-600' },
  { title: 'Exports', value: 56, icon: ArrowUpTrayIcon, bgColor: 'bg-green-600' }
])

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

