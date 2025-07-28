<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
//
const props = defineProps({
    quiz: {
        type: Array,
        required: true,
    },
});

const quiz = props.quiz;
const current = ref(0);
const score = ref(0);
const answered = ref(false);
const selected = ref(null);

const isCorrect = computed(() =>
    selected.value === quiz[current.value].correctAnswer
);

function selectAnswer(ans) {
    if (answered.value) return;
    selected.value = ans;
    answered.value = true;
    if (isCorrect.value) score.value += 1;

    setTimeout(() => {
        nextQuestion();
    }, 1000);
}

function nextQuestion() {
    current.value += 1;
    answered.value = false;
    selected.value = null;
}

function tryAgain() {
    window.location.reload();
}
</script>

<style scoped>
.quiz {
  max-width: 500px;
  margin: 2rem auto;
}
button {
  margin: 0.5rem 0;
  min-width: 200px;
}
.correct {
  background: #b5e7a0;
}
.wrong {
  background: #ffb6b6;
}
</style>


<template>
    <Head title="Flashcard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Flashcard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="quiz">
                            <div v-if="current < quiz.length">
                            <h2>Question {{ current + 1 }} of {{ quiz.length }}</h2>
                            <p>{{ quiz[current].question }}</p>
                            <ul>
                                <li v-for="(ans, idx) in quiz[current].answers" :key="idx">
                                <button
                                    :disabled="answered"
                                    @click="selectAnswer(ans)"
                                    :class="{
                                    correct: answered && ans === quiz[current].correctAnswer,
                                    wrong: answered && ans === selected && ans !== quiz[current].correctAnswer
                                    }"
                                >
                                    {{ ans }}
                                </button>
                                </li>
                            </ul>
                            <div v-if="answered">
                                <p v-if="isCorrect">✅ Correct!</p>
                                <p v-else>❌ Incorrect. Correct: {{ quiz[current].correctAnswer }}</p>
                                <button @click="nextQuestion">Next</button>
                            </div>
                            </div>
                            <div v-else>
                                <h2>Quiz Complete!</h2>
                                <p>Your score: {{ score }}/{{ quiz.length }}</p>
                                <p><button class="btn btn-success" @click="tryAgain">Try again</button></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


