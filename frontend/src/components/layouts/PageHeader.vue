<template>
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">{{ title }}</h2>
    <p v-if="subtitle" class="text-gray-500">{{ subtitle }}</p>
  </div>
</template>

<script>
export default {
  name: "PageHeader",
  props: {
    title: String,
    subtitle: String,
  },
};
</script>
