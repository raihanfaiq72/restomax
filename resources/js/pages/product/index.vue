<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import ProductCrud from '@/components/ProductCrud.vue';
import { type Product, type Category } from '@/components/ProductCrud.vue'; 

defineProps<{
  categories: Category[];
  products: Product[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: 'dashboard' },
];
</script>

<template>
    <Head title="Manajemen Produk" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6 lg:p-8">
            <ProductCrud :products="products" :categories="categories" />
        </div>
    </AppLayout>
</template>