<template>
  <div class="bg-white shadow rounded-2xl p-5 border border-gray-100">
    <h2 class="text-gray-500 text-sm font-medium">{{ label }}</h2>
    <p :class="['text-3xl font-bold mt-2', colorClass]">{{ prefix }}{{ value }}</p>
  </div>
</template>

<script>
export default {
  name: "StatCard",
  props: {
    label: String,
    value: [String, Number],
    colorClass: {
      type: String,
      default: "text-blue-600",
    },
    prefix: {
      type: String,
      default: "",
    },
  },
};
</script>
