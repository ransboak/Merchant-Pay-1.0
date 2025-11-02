<script setup>
import PageHeader from '../components/layouts/PageHeader.vue';
</script>
<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
      <!-- <div>
        <h1 class="text-3xl font-bold text-gray-800">Settlement Management</h1>
        <p class="text-gray-500 mt-1">Monitor settlement payouts and trigger manual settlements</p>
      </div> -->
      <PageHeader title="Settlements" subtitle="Manage and process merchant settlements" />

      <button
        @click="runSettlement"
        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all"
      >
        Run Settlement Job
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Settlements</h2>

      <div class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-600 mb-1">From Date</label>
          <input
            type="date"
            v-model="filters.startDate"
            class="w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg p-2.5"
          />
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-600 mb-1">To Date</label>
          <input
            type="date"
            v-model="filters.endDate"
            class="w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg p-2.5"
          />
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
          <button
            @click="filterSettlements"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition-all w-full sm:w-auto"
          >
            Filter
          </button>
          <button
            @click="resetFilters"
            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-lg transition-all w-full sm:w-auto"
          >
            Reset
          </button>
        </div>
      </div>
    </div>

    <!-- Settlements Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Settlement History</h2>
        <p class="text-sm text-gray-500">
          Total: <span class="font-semibold">{{ filteredSettlements.length }}</span>
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="p-3 text-left text-gray-600">Merchant</th>
              <th class="p-3 text-left text-gray-600">Amount</th>
              <th class="p-3 text-left text-gray-600">Settlement Date</th>
              <th class="p-3 text-left text-gray-600">Reference</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="settlement in filteredSettlements"
              :key="settlement.id"
              class="border-b hover:bg-gray-50 transition"
            >
              <td class="p-3 font-medium text-gray-800">
                {{ settlement.merchant?.business_name || '—' }}
              </td>
              <td class="p-3 font-semibold text-blue-600">
                ₵{{ settlement.amount.toLocaleString() }}
              </td>
              <td class="p-3 text-gray-700">
                {{ new Date(settlement.settlement_date).toLocaleString() }}
              </td>
              <td class="p-3 text-gray-600">
                {{ settlement.reference }}
              </td>
            </tr>

            <tr v-if="filteredSettlements.length === 0">
              <td colspan="4" class="text-center p-6 text-gray-500">
                No settlements found for the selected range.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../api'

export default {
  data() {
    return {
      settlements: [],
      filters: {
        startDate: '',
        endDate: '',
      },
    }
  },
  computed: {
    filteredSettlements() {
      const { startDate, endDate } = this.filters
      return this.settlements.filter((s) => {
        const date = new Date(s.settlement_date)
        const afterStart = !startDate || date >= new Date(startDate)
        const beforeEnd = !endDate || date <= new Date(endDate)
        return afterStart && beforeEnd
      })
    },
  },
  async created() {
    await this.fetchSettlements()
  },
  methods: {
    async fetchSettlements() {
      try {
        const res = await api.get('/settlements')
        this.settlements = res.data.data || res.data
      } catch (err) {
        console.error(err)
        alert('Error fetching settlements')
      }
    },
    async runSettlement() {
      try {
        await api.post('/settlements/run')
        alert('Settlement job executed successfully!')
        await this.fetchSettlements()
      } catch (err) {
        console.error(err)
        alert('Error running settlement')
      }
    },
    filterSettlements() {
      // handled by computed
    },
    resetFilters() {
      this.filters.startDate = ''
      this.filters.endDate = ''
    },
  },
}
</script>
