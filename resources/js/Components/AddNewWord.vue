<script>

export default {
  props: {
    flashcard: {
      type: Object,
      required: true,
    },
    user: {
      type: Object,
      required: false,
      default: null,
    },
  },
  data() {
    return {
      question: '',
      answer: '',
      flash: null,
    }
  },
  computed: {
    isOwner() {
      return this.user?.id === this.flashcard?.user?.id
    },
  },
  methods: {
    async addQuestion() {
      if (!this.question || !this.answer) {
        alert('Please fill both fields.')
        return
      }
      const confirmed = window.confirm(
        `Add question/answer:\n\n${this.question} → ${this.answer}?`
      )
      if (!confirmed) return
      try {
        const response = await fetch(`/flashcard/${this.flashcard.id}/addWord`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            question: this.question,
            answer: this.answer,
          }),
        })

        if (!response.ok) {
          throw new Error('Failed to add word.')
        }
        sessionStorage.setItem('flash', JSON.stringify({
            message: 'Question added: ' + this.question + ' → ' + this.answer,
            type: 'success'
        }));
        this.question = ''
        this.answer = ''
        window.location.reload()
      } catch (err) {
        console.error(err)
        sessionStorage.setItem('flash', JSON.stringify({
            message: 'Failed to add question.',
            type: 'error'
        }))
        alert('Error adding word.')
      }
    },
  },
  mounted() {
    const saved = sessionStorage.getItem('flash')
    if (saved) {
      this.flash = JSON.parse(saved)
      sessionStorage.removeItem('flash')
      setTimeout(() => (this.flash = null), 4000)
    }
  },
}

</script>

<template>
    <div v-if="isOwner">
        <div v-if="flash" :class="[
            'fixed top-4 right-4 px-4 py-2 rounded shadow text-white z-50',
            flash.type === 'success' ? 'bg-green-600' : 'bg-red-600'
        ]">
            {{ flash.message }}
        </div>

        <slot />
        <div class="card">
            <div class="card-header">
                <h5>Add Question/Answer</h5>
            </div>
            <div class="input-group">
                <input v-model="question" class="form-control" placeholder="Question..." />
                <input v-model="answer" class="form-control" placeholder="Answer..." />
                <button class="btn btn-success btn-card" @click="addQuestion">
                    Add question
                </button>
            </div>
        </div>
    </div>
</template>
