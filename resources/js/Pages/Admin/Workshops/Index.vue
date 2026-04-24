<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  workshops: Object,
});
</script>

<template>
  <div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Workshop</h1>
      <Link
        href="/admin/workshops/create"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
      >
        Nuovo Workshop
      </Link>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titolo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Istruttore</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durata</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Iscritti</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Azioni</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="workshop in workshops.data" :key="workshop.id">
            <td class="px-6 py-4">{{ workshop.title }}</td>
            <td class="px-6 py-4">{{ workshop.instructor }}</td>
            <td class="px-6 py-4">{{ new Date(workshop.scheduled_at).toLocaleString('it-IT') }}</td>
            <td class="px-6 py-4">{{ workshop.duration_minutes }} min</td>
            <td class="px-6 py-4">{{ workshop.max_participants }}</td>
            <td class="px-6 py-4">{{ workshop.registrations_count || 0 }}</td>
            <td class="px-6 py-4 flex gap-2">
              <Link :href="`/admin/workshops/${workshop.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Modifica</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>