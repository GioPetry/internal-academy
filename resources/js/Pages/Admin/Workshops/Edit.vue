<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  workshop: Object,
});

const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  return d.toISOString().slice(0, 16);
};

const form = useForm({
  title: props.workshop.title,
  description: props.workshop.description,
  instructor: props.workshop.instructor,
  scheduled_at: formatDate(props.workshop.scheduled_at),
  duration_minutes: props.workshop.duration_minutes,
  max_participants: props.workshop.max_participants,
});

const submit = () => {
  form.put(`/admin/workshops/${props.workshop.id}`);
};
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Modifica Workshop</h1>

    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">Titolo</label>
        <input v-model="form.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Descrizione</label>
        <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Istruttore</label>
        <input v-model="form.instructor" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Data e Ora</label>
        <input v-model="form.scheduled_at" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Durata (minuti)</label>
        <input v-model="form.duration_minutes" type="number" min="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Max Partecipanti</label>
        <input v-model="form.max_participants" type="number" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required />
      </div>

      <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
        Salva Modifiche
      </button>
    </form>
  </div>
</template>