<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import IngredientCrud from '@/components/IngredientCrud.vue';
import { type Ingredient} from '@/components/IngredientCrud.vue'; 

defineProps<{
    ingredients: Ingredient[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Ingredients' },
];
</script>

<template>
    <Head title="Manajemen Bahan Baku" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6 lg:p-8">
            <IngredientCrud :ingredients="ingredients"/>
        </div>
    </AppLayout>
</template>