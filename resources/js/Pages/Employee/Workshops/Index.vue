<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  workshops: Object,
});
</script>

<template>
  <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Workshop Disponibili</h1>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="workshop in workshops.data" :key="workshop.id" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-2">{{ workshop.title }}</h2>
        <p class="text-gray-600 mb-4">{{ workshop.description }}</p>
        <div class="space-y-2 text-sm text-gray-500">
          <p><span class="font-medium">Istruttore:</span> {{ workshop.instructor }}</p>
          <p><span class="font-medium">Data:</span> {{ new Date(workshop.scheduled_at).toLocaleString('it-IT') }}</p>
          <p><span class="font-medium">Durata:</span> {{ workshop.duration_minutes }} minuti</p>
          <p><span class="font-medium">Posti:</span> {{ workshop.available_slots || workshop.max_participants }} / {{ workshop.max_participants }}</p>
        </div>
        <Link
          :href="`/workshops/${workshop.id}`"
          class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
        >
          Dettagli
        </Link>
      </div>
    </div>
  </div>
</template>