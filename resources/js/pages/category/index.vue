<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import CategoryCrud from '@/components/CategoryCrud.vue';
import { type Category } from '@/components/CategoryCrud.vue';

// Terima props 'categories' dari CategoryController@index
defineProps<{
  categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: 'dashboard' },
    { title: 'Category' },
];
</script>

<template>
    <Head title="Manajemen Kategori" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 md:p-6">
             <CategoryCrud :categories="categories" />
        </div>
    </AppLayout>
</template>