<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    quiz: {
        type: Array,
        required: true,
    },
    flashcardId: {
        type: [String, Number],
        required: true,
    },
});

const quiz = ref([]);
const current = ref(0);
const score = ref(0);
const difficulty = new URL(window.location.href).searchParams.get('difficulty');
const answered = ref(false);
const selected = ref(null);

function shuffle(array) {
    return array.sort(() => Math.random() - 0.5);
}

onMounted(() => {
    quiz.value = props.quiz.map((entry, index, arr) => {
        const isReverse = Math.random() < 0.5;

        if (isReverse) {
            // Generate reverse question: show the answer, ask for the question
            let otherQuestions = arr
                .filter((_, i) => i !== index)
                .map(item => item.question);
            otherQuestions = shuffle(otherQuestions).slice(0, 4);

            const options = [...otherQuestions];
            const correctIndex = Math.floor(Math.random() * 5);
            options.splice(correctIndex, 0, entry.question);

            return {
                display: entry.correctAnswer, // Show answer
                options,
                correct: entry.question,
                mode: 'reverse',
            };
        } else {
            // Normal mode: show question, ask for correct answer
            return {
                display: entry.question, // Show question
                options: entry.answers,
                correct: entry.correctAnswer,
                mode: 'normal',
            };
        }
    });
});

const isCorrect = computed(() =>
    selected.value === quiz.value[current.value]?.correct
);

function selectAnswer(ans) {
    if (answered.value) return;

    selected.value = ans;
    answered.value = true;

    if (isCorrect.value) score.value += 1;

    const q = quiz.value[current.value];
    const payload =
        q.mode === 'normal'
            ? { question: q.display, answer: ans, correct: isCorrect.value }
            : { question: ans, answer: q.display, correct: isCorrect.value };

    logAnswer(payload);

    setTimeout(() => {
        nextQuestion();
    }, 1000);
}

function logAnswer({ question, answer, correct }) {
    axios
        .post(`/api/flashcard/${props.flashcardId}/test/answer`, {
            question,
            answer,
            correct,
        })
        .then(() => console.log('Answer stored'))
        .catch(error => console.error('Failed to log answer', error));
}

function nextQuestion() {
    current.value += 1;
    answered.value = false;
    selected.value = null;
}

function tryAgain() {
    window.location.reload();
}

const resultFeedback = computed(() => {
    let message = '';
    if (!quiz.value.length) return message;

    const resultPercent = (score.value / quiz.value.length) * 100;

    switch (true) {
        case resultPercent == 100:
            message = 'Perfect!';
            break;
        case resultPercent >= 80:
            message = 'Excellent!';
            break;
        case resultPercent >= 60:
            message = 'Good job!';
            break;
        case resultPercent >= 40:
            message = 'Not bad, but you need more practice.';
            break;
        default:
            message = 'Not great. You need a lot more practice!';
            break;
    }
    return message;
});
</script>

<template>
    <Head title="Flashcard - Quiz" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Flashcard - Quiz
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="quiz">
                            <div v-if="quiz.length && current < quiz.length">
                                <h2>Question {{ current + 1 }} of {{ quiz.length }}</h2>
                                <h4>Difficulty {{ difficulty }}</h4>
                                <p>
                                    <span>Q: What is <strong>'{{ quiz[current]?.display }}'</strong> in <span v-if="quiz[current]?.mode === 'normal'">English</span><span v-else>Lithuanian</span> </span>
                                </p>

                                <ul>
                                    <li
                                        v-for="(ans, idx) in quiz[current]?.options"
                                        :key="idx"
                                    >
                                        <button class="btn quiz-button"
                                            :disabled="answered"
                                            @click="selectAnswer(ans)"
                                            :class="{
                                                correct: answered && ans === quiz[current]?.correct,
                                                wrong: answered && ans === selected && ans !== quiz[current]?.correct
                                            }"
                                            @touchend="$event.target.blur()"
                                        >
                                            {{ ans }}
                                        </button>
                                    </li>
                                </ul>

                                <div v-if="answered">
                                    <p v-if="isCorrect">✅ Correct!</p>
                                    <p v-else>
                                        ❌ Incorrect. Correct:
                                        <span v-if="quiz[current]?.mode === 'normal'">{{ quiz[current]?.correct }}</span>
                                        <span v-else>Question: {{ quiz[current]?.correct }}</span>
                                    </p>
                                    <button @click="nextQuestion">Next</button>
                                </div>

                            </div>

                            <div v-else-if="quiz.length">
                                <h2>Quiz Complete!</h2>
                                <p>Your score: {{ score }}/{{ quiz.length }}</p>
                                <p>{{ resultFeedback }}</p>
                                <p>
                                    <button class="btn btn-success" @click="tryAgain">
                                        Try again
                                    </button>
                                </p>
                                <a
                                    :href="`/flashcard/${flashcardId}/quizResults`"
                                    class="btn btn-primary mt-4"
                                >
                                    See your stats from all quizzes on this flashcard
                                </a>
                            </div>

                            <div v-else>
                                <p>Loading quiz...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.quiz {
    max-width: 500px;
    margin: 2rem auto;
}
button {
    margin: 0.5rem 0;
    min-width: 200px;
}
.quiz-button, .quiz-button:focus, .quiz-button:active {
    background: #d7e5e7 !important;
    border: 1px solid #777 !important;
    color: #000;
}
.quiz-button:hover {
    background: #fff;
}
.correct {
    background: #b5e7a0;
    border: 1px solid #56cb27;
    color: #000;
}
.wrong {
    background: #ffb6b6;
    border: 1px solid #ed3a3a;
    color: #000;
}
</style>
