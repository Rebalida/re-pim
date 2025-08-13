<template>
    <div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="space-y-2">
            <h2 class="text-3xl font-bold">Products</h2>
            <p class="text-sm text-gray-400">Manage your products across stores</p>
        </div>
        <button class="bg-sky-500 hover:bg-sky-700 text-white px-4 py-2 rounded-lg transition duration-200">Add Products</button>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
    <input type="text"
    v-model="search"
    placeholder="Search Products"
    class="w-full p-3 rounded-lg bg-gray-800 text-white placeholder-gray-400 focus:ring-2 focus:ring-sky-500"
    >
    </div>

    <!-- Products Table -->
    <div class="bg-gray-800/40 backdrop-blur-md border border-white/10 rounded-lg overflow-hidden shadow-lg">
    <table class="min-w-full">
        <!-- Head -->
        <thead class="bg-gray-700/40 text-gray-300 backdrop-blur-md">
            <tr>
                <th class="px-6 py-3 text-left">Image</th>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Store</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Price</th>
                <th class="px-6 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <!-- Body -->
        <tbody>
            <tr
                v-for="product in filteredProducts"
                :key="product.id"
                class="border-b border-white/10 hover:bg-gray-700/30 transition"
            >
                <td class="px-6 py-4">
                    <img :src="product.image" alt="" class="w-12 h-12 rounded-lg object-cover" />
                </td>
                <td class="px-6 py-4">{{ product.name }}</td>
                <td class="px-6 py-4">{{ product.store }}</td>
                <td class="px-6 py-4">
                    <span
                        :class="[
                        'px-4 py-2 rounded-lg text-sm font-normal',
                        product.status === 'Active' ? 'bg-emerald-700/15 text-green-500' : 'bg-rose-700/15 text-red-500'
                        ]"
                    >
                        {{ product.status }}
                    </span>
                </td>
                <td class="px-6 py-4">${{ product.price }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <button class="px-3 py-1">
                        <PencilSquareIcon 
                            class="h-6 w-6 text-yellow-500 hover:text-yellow-600"
                        />
                    </button>
                    <button class="px-3 py-1">
                        <TrashIcon 
                            class="h-6 w-6 text-rose-500 hover:text-rose-600"
                        />
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { PencilSquareIcon } from '@heroicons/vue/16/solid';
import { TrashIcon } from '@heroicons/vue/24/outline';

const search = ref('');

const products = ref ([
    {
        id: 1,
        image: 'https://placehold.co/150',
        name: 'Hoodie',
        store: 'Store A',
        status: 'Active',
        price: '49.99'
    },
    {
        id: 2,
        image: 'https://placehold.co/150',
        name: 'T-Shirt',
        store: 'Store B',
        status: 'Inactive',
        price: 19.99
    },
    {
        id: 3,
        image: 'https://placehold.co/150',
        name: 'Cap',
        store: 'Store C',
        status: 'Active',
        price: 14.99
    }
])

const filteredProducts = computed(() => {
    return products.value.filter(p =>
        p.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase())
    );
});
</script>