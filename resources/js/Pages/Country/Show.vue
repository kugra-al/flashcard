<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">{{ country.name }}</h1>
    <p><strong>Code 2:</strong> {{ country.country_code_2 }}</p>
    <p><strong>Code 3:</strong> {{ country.country_code_3 }}</p>
  </div>
</template>

<script>
export default {
  props: {
    country: Object,
  }
}
</script>
