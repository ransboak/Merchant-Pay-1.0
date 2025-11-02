<template>
  <button
    @click="$emit('click')"
    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-sm transition duration-200 flex items-center gap-1"
  >
    <slot />
  </button>
</template>

<script>
export default {
  name: "PrimaryButton",
};
</script>
