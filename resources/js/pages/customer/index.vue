<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import CustomerCrud from '@/components/CustomerCrud.vue';
import { type Customer } from '@/components/CustomerCrud.vue';
import { type BreadcrumbItem } from '@/types';

defineProps<{
    customers: Customer[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Customers' },
];
</script>

<template>
    <Head title="Manajemen Pelanggan" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 sm:p-6 lg:p-8">
            <CustomerCrud :customers="customers" />
        </div>
    </AppLayout>
</template>