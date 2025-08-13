<template>
  <div class="min-h-screen bg-gray-900 text-gray-100 font-sans">
    <header class="bg-gray-800">
      <div class="container mx-auto flex items-center justify-between px-6 py-4">
        
        <!-- Logo -->
        <div class="flex items-center gap-4">
          <h1 class="text-2xl font-extrabold">RE:PIM</h1>
        </div>

        <!-- Desktop Nav -->
        <nav class="hidden md:flex space-x-4">
          <router-link to="/" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Dashboard</router-link>
          <router-link to="/products" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Products</router-link>
          <router-link to="/settings" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Settings</router-link>
        </nav>

        <!-- Menu Button -->
        <button
         class="md:hidden text-gray-300 hover:text-white focus:outline-none"
         @click="isOpen = true"
        >
          <Bars3Icon class="h-6 w-6" />
        </button>
      </div>
    </header>

    <!-- Drawer -->
    <div
      class="fixed inset-0 z-50 flex"
      :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
      style="transition: transform 0.3s ease-in-out"
    >
      <div class="w-64 bg-gray-800 p-6 flex flex-col">

        <div class="flex justify-between items-center mb-8">
          <h2 class="text-xl font-bold">RE:PIM</h2>
          <button
            @click="isOpen = false"
          >
            <XMarkIcon class="h-6 w-6 text-gray-400 hover:text-white" />
          </button>
        </div>

        <nav class="space-y-4">
          <router-link to="/" class="block hover:text-emerald-300">Dashboard</router-link>
          <router-link to="/products" class="block hover:text-emerald-300">Products</router-link>
          <router-link to="/settings" class="block hover:text-emerald-300">Settings</router-link>
        </nav>

      </div>

      <div
        class="flex-1 bg-black bg-opacity-50"
        @click="isOpen = false"
      ></div>
    </div>

    <main class="container mx-auto px-6 py-8">
      <router-view />
    </main>
  
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';

const isOpen = ref(false);
</script>