<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';

    const props = defineProps({
        topCorrect: {
            type: Array,
            required: true
        },
        topWrong: {
            type: Array,
            required: true
        },
        flashcard: {
            type: Object,
            required: true
        }
    });
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
                        <section>
                            <h2 class="text-xl font-semibold mb-3">Top 10 Correct Words</h2>
                            <div class="container">
                                <div v-for="item in topCorrect" :key="item.question_text" class="row mb-2">
                                    <div class="col-3">
                                        {{ item.question_text }}
                                    </div>
                                    <div class="col-3">
                                        <span class="correct">{{ item.answer_text }}</span>
                                    </div>
                                    <div class="col-3">
                                        <strong>{{ item.count }}</strong> times
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="mt-8">
                            <h2 class="text-xl font-semibold mb-3">Top 10 Wrong Words</h2>
                            <div class="container">
                                <div v-for="item in topWrong" :key="item.question_text" class="row mb-2">
                                    <div class="col-3">
                                        {{ item['question'] }}
                                    </div>
                                    <div class="col-3">
                                        <span class="correct">{{ item['correct_answer'] }}</span>
                                    </div>
                                    <div class="col-3">
                                        <strong>{{ item['count'] }}</strong> times
                                    </div>

                                    <div class="row mb-2">
                                        <ul class="list-group list-group-horizontal-md">
                                            <li class="list-group-item">Wrong answers</li>
                                            <li class="list-group-item wrong" v-if="item && item['common_wrong_answers']" v-for="wrong in item['common_wrong_answers']">{{ wrong['answer_text'] }} </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="mt-8">
                            <StartQuizButton :flashcard="flashcard" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import StartQuizButton from '@/Components/StartQuizButton.vue';

export default {
    components: {
        StartQuizButton,
    },
    props: {
        flashcard: Object,
    }
}
</script>

<style scoped>
.wrong {
    background: #ffb6b6;
}
.correct {
    background: #b6ffc9;
}
</style>
