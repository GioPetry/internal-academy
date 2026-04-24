<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  workshop: Object,
});

const page = usePage();
const userId = computed(() => page.props.auth?.user?.id);

const isRegistered = computed(() => {
  if (!props.workshop.registrations) return false;
  return props.workshop.registrations.some(r => r.user_id === userId.value && r.status === 'registered');
});

const isWaitlisted = computed(() => {
  if (!props.workshop.registrations) return false;
  return props.workshop.registrations.some(r => r.user_id === userId.value && r.status === 'waitlisted');
});

const registration = computed(() => {
  if (!props.workshop.registrations) return null;
  return props.workshop.registrations.find(r => r.user_id === userId.value);
});
</script>

<template>
  <div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <h1 class="text-2xl font-bold mb-2">{{ workshop.title }}</h1>
      <p class="text-gray-600 mb-6">{{ workshop.description }}</p>

      <div class="grid grid-cols-2 gap-4 text-sm mb-6">
        <div><span class="font-medium">Istruttore:</span> {{ workshop.instructor }}</div>
        <div><span class="font-medium">Data:</span> {{ new Date(workshop.scheduled_at).toLocaleString('it-IT') }}</div>
        <div><span class="font-medium">Durata:</span> {{ workshop.duration_minutes }} minuti</div>
        <div><span class="font-medium">Posti disponibili:</span> {{ workshop.available_slots || 0 }} / {{ workshop.max_participants }}</div>
      </div>

      <div v-if="isRegistered" class="p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-green-700 font-medium">Sei registrato a questo workshop</p>
        <form :action="`/workshops/${workshop.id}/register`" method="POST">
          <input type="hidden" name="_method" value="DELETE" />
          <button type="submit" class="mt-2 text-red-600 hover:text-red-800">Cancella registrazione</button>
        </form>
      </div>

      <div v-else-if="isWaitlisted" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="text-yellow-700 font-medium">Sei in lista d'attesa (posizione: {{ registration?.position }})</p>
        <form :action="`/workshops/${workshop.id}/register`" method="POST">
          <input type="hidden" name="_method" value="DELETE" />
          <button type="submit" class="mt-2 text-red-600 hover:text-red-800">Cancella</button>
        </form>
      </div>

      <form v-else :action="`/workshops/${workshop.id}/register`" method="POST" class="mt-4">
        <button
          type="submit"
          :disabled="workshop.available_slots <= 0 && !registration"
          class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ workshop.available_slots > 0 ? 'Iscriviti' : 'Iscriviti (lista attesa)' }}
        </button>
      </form>
    </div>
  </div>
</template>