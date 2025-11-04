<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import StartQuizButton from '@/Components/StartQuizButton.vue'
import AddNewWord from '@/Components/AddNewWord.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

// Props from Laravel controller
const props = defineProps({
  flashcard: Object,
  errors: Object,
  auth: Object,
})

// Access logged-in user
const page = usePage()
const user = computed(() => page.props.auth.user)
const isOwner = computed(() => user.value?.id === props.flashcard?.user_id)
</script>

<template>

    <Head title="Flashcard" />

    <AuthenticatedLayout :user="auth.user">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Flashcards
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="card">
                            <div class="card-header">
                                <h4>Flashcard</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ flashcard.name }}</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>ID:</strong> {{ flashcard.id }}</li>
                                    <li class="list-group-item"><strong>Creator:</strong> {{ flashcard.user.name }}</li>
                                    <li class="list-group-item"><strong>Questions:</strong> {{ flashcard.primary_language.name }}</li>
                                    <li class="list-group-item"><strong>Answers:</strong> {{ flashcard.secondary_language.name }}</li>
                                    <li class="list-group-item"><strong>Entries:</strong> {{ flashcard.entries.length }}</li>
                                </ul>
                            </div>
                        </div>
                        <hr/>
                        <AddNewWord :flashcard="flashcard" :user="auth.user" />
                        <hr/>
                        <StartQuizButton :flashcard="flashcard" />
                        <div>
                            <table class="table table-auto">
                                <thead>
                                    <tr>
                                        <th class="text-left px-4 py-2">Question</th>
                                        <th class="text-left px-4 py-2">Answer</th>
                                        <th v-if="isOwner" class="text-left px-4 py-2 w-auto"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="entry in flashcard.entries" :key="entry.id" class="border p-4 mb-2 rounded">
                                        <td class="px-4 py-2"><a :name="entry.question"></a>{{ entry.question }}</td>
                                        <td class="px-4 py-2">{{ entry.answer }}</td>
                                        <td v-if="isOwner" class="px-4 py-2 w-auto text-right whitespace-nowrap">
                                            <button class="btn btn-info">Edit</button>
                                            <button class="btn btn-danger">Delete</button>
                                        </td>
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

