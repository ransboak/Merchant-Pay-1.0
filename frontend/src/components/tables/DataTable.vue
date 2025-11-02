<template>
  <div class="overflow-x-auto bg-white shadow rounded-2xl border border-gray-100">
    <table class="min-w-full text-left">
      <thead class="bg-gray-50">
        <tr>
          <th v-for="header in headers" :key="header" class="py-3 px-4 text-gray-600 text-sm font-medium">
            {{ header }}
          </th>
        </tr>
      </thead>
      <tbody>
        <slot />
      </tbody>
    </table>
    <div v-if="!$slots.default" class="p-4 text-gray-400 text-sm text-center">No data available</div>
  </div>
</template>

<script>
export default {
  name: "DataTable",
  props: {
    headers: {
      type: Array,
      required: true,
    },
  },
};
</script>
