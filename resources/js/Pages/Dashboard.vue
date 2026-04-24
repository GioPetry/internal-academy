<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  auth: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="mb-4">Benvenuto, {{ auth.user.name }}!</p>
                        <p class="text-gray-600 mb-4">Ruolo: {{ auth.user.role }}</p>
                        
                        <div class="space-y-2">
                            <a href="/workshops" class="block text-indigo-600 hover:text-indigo-800">Workshop Disponibili</a>
                            <a v-if="auth.user.role === 'admin'" href="/admin/workshops" class="block text-indigo-600 hover:text-indigo-800">Gestione Workshop (Admin)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>