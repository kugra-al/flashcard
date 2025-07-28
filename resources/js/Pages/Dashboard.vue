<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios'
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
                        <form @submit.prevent="handleSubmit">
                            <p>Create new flashcard set</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Name</span>
                                </div>
                                <input v-model="form.name" class="form-control" placeholder="Enter name" />
                            </div>
                            <div v-if="errors.name" class="alert alert-warning">{{ errors.name[0] }}</div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Primary language</span>
                                </div>
                                <select v-model="form.primary_language" class="form-control" id="language1">
                                    <option disabled value="">Select language from</option>
                                    <option v-for="lang in languages" :key="lang.id" :value="lang.id">
                                        {{ lang.name }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="errors.primary_language" class="alert alert-warning">{{ errors.primary_language[0] }}</div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Secondary language</span>
                                </div>
                                <select v-model="form.secondary_language" class="form-control" id="language2">
                                    <option disabled value="">Select language to:</option>
                                    <option v-for="lang in languages" :key="'lang2-' + lang.id" :value="lang.id">
                                        {{ lang.name }}
                                    </option>
                                </select>
                            </div>
                            <div v-if="errors.secondary_language" class="alert alert-warning">{{ errors.secondary_language[0] }}</div>
                            <div class="input-group">
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>

export default {
    data() {
        return {
            form: {
                name: '',
                primary_language: '',
                secondary_language: '',
            },
            languages: {},
            errors: {}
        }
    },
    mounted() {
        axios.get('/api/languages').then(response => {
            this.languages = response.data;
        });
    },
    methods: {
        async handleSubmit() {
            this.errors = {}; // Clear previous errors
            try {
                const response = await axios.post('/api/submit-flashcard', this.form);
                console.log('Success:', response.data);
                window.location.href = `/flashcard/${response.data.data.id}`;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error('Unexpected error:', error);
                }
            }
        }
    }
}
</script>


