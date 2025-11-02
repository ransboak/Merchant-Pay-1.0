<template>
  <div
    v-if="visible"
    class="flex justify-center items-center py-8"
  >
    <div
      class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"
    ></div>
    <span v-if="text" class="ml-3 text-gray-600 text-sm font-medium">
      {{ text }}
    </span>
  </div>
</template>

<script>
export default {
  name: "Loader",
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    text: {
      type: String,
      default: "Loading...",
    },
  },
};
</script>
