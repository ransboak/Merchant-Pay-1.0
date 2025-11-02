<template>
  <div class="flex flex-wrap items-center gap-3 mb-4">
    <div>
      <label class="text-sm text-gray-600 block mb-1">From</label>
      <input type="date" v-model="from" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>
    <div>
      <label class="text-sm text-gray-600 block mb-1">To</label>
      <input type="date" v-model="to" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>
    <PrimaryButton @click="applyFilter">Filter</PrimaryButton>
  </div>
</template>

<script>
import PrimaryButton from "../buttons/PrimaryButton.vue";

export default {
  name: "DateFilter",
  components: { PrimaryButton },
  emits: ["filter"],
  data() {
    return {
      from: "",
      to: "",
    };
  },
  methods: {
    applyFilter() {
      this.$emit("filter", { from: this.from, to: this.to });
    },
  },
};
</script>
