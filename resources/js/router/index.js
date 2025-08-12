import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import Products from '../pages/Products.vue';
import Settings from '../pages/Settings.vue';

const routes = [
    { path: '/', name: 'dashboard', component: Dashboard },
    { path: '/products', name: 'products', component: Products },
    { path: '/settings', name: 'settings', component: Settings },
];


export default createRouter({
    history: createWebHistory(),
    routes,
});