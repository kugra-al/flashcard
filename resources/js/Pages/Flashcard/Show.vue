<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Flashcard" />

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
                        <h1 class="text-2xl font-bold mb-4">Flashcard</h1>
                        <p><strong>ID:</strong> {{ flashcard.id }}</p>
                        <p><strong>User:</strong> {{ flashcard.user.name }}</p>
                        <p><strong>Name:</strong> {{ flashcard.name }}</p>
                        <p><strong>Language From:</strong> {{ flashcard.primary_language.name }}</p>
                        <p><strong>Language To:</strong> {{ flashcard.secondary_language.name }}</p>
                        <p><a :href="`/flashcard/${flashcard.id}/test?questions=5`">Start Test</a></p>
                        <div>
                            <table class="table task-table">
                                <thead>
                                    <th>Question</th>
                                    <th>Answer</th>
                                </thead>
                                <tbody>
                                    <tr v-for="entry in flashcard.entries" :key="entry.id" class="border p-4 mb-2 rounded">
                                        <td>{{ entry.question }}</td><td>{{ entry.answer }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
    flashcard: Object,
  }
}
</script>
