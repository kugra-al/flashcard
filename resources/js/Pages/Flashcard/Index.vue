<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Flashcards" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Flashcards
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-for="card in flashcards" :key="card.id" class="border p-4 mb-2 rounded">
                            <p><strong>Name:</strong> <a :href="`/flashcard/${card.id}`">{{ card.name }}</a></p>
                            <p><strong>Language From:</strong> {{ card.primary_language.name }}</p>
                            <p><strong>Language To:</strong> {{ card.secondary_language.name }}</p>
                            <p><strong>Entries:</strong> {{ card.entries.length }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  props: {
    flashcards: Array,
  }
}
</script>
