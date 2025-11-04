<template>
    <div class="card">
        <div class="card-header">
            <h5>Start Quiz</h5>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">Questions</div>
            </div>
            <select class="form-control" name="questions">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
            <div class="input-group-prepend">
                <div class="input-group-text">Difficulty</div>
            </div>
            <select class="form-control" name="difficulty">
                <option v-for="(name, id) in levels" :key="id" :value="id">
                    {{ name }}
                </option>
            </select>
            <button class="btn btn-success btn-card-flat" @click="startQuiz">
                Start Quiz
            </button>
        </div>
        <div class="card-body"><a class="card-link" :href="`/flashcard/${flashcard.id}/quizResults`">See your results for this flashcard</a></div>
    </div>
</template>

<script>
export default {
    props: {
        flashcard: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            levels: {}
        };
    },
    methods: {
        startQuiz() {
            let questions = document.querySelector('select[name="questions"]').value;
            let difficulty = document.querySelector('select[name="difficulty"]').value;
            window.location.href = `/flashcard/${this.flashcard.id}/quiz?difficulty=${difficulty}&questions=${questions}`;
        },
    },
    mounted() {
        fetch('/api/levels')
        .then(res => res.json())
        .then(data => {
            this.levels = data;
        });
    }
}
</script>
