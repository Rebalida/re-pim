<template>
  <div class="min-h-screen bg-gray-900 text-gray-100">
    <header class="bg-gray-800">
      <div class="container mx-auto flex items-center justify-between px-6 py-4">
        
        <div class="flex items-center gap-4">
          <h1 class="text-2xl font-extrabold">RE:PIM</h1>
        </div>

        <nav class="space-x-4">
          <router-link to="/" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Dashboard</router-link>
          <router-link to="/products" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Products</router-link>
          <router-link to="/settings" class="font-semi-bold hover:text-emerald-300 transition-colors duration-200">Settings</router-link>
        </nav>

      </div>
    </header>

    <main class="container mx-auto px-6 py-8">
      <router-view />
    </main>
  
  </div>
</template>