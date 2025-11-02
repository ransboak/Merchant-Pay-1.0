<script setup>
import PageHeader from '../components/layouts/PageHeader.vue';
</script>
<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <!-- <div class="mb-10">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
      <p class="text-gray-500 mt-1">Overview of platform performance and activities</p>
    </div> -->
    <PageHeader title="Dashboard" subtitle="Overview of platform performance and activities" />

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
      <div
        v-for="card in statCards"
        :key="card.label"
        class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6 hover:shadow-md transition"
      >
        <h2 class="text-sm font-medium text-gray-500">{{ card.label }}</h2>
        <p :class="card.color" class="text-3xl font-bold mt-2">
          {{ card.prefix }}{{ card.value }}
        </p>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
      <div class="flex justify-between items-center mb-4">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">Recent Transactions</h2>
          <p class="text-gray-500 text-sm">Latest 5 processed payments</p>
        </div>
        <router-link
          to="/transactions"
          class="text-blue-600 hover:text-blue-700 hover:underline text-sm font-medium"
        >
          View all
        </router-link>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-t border-gray-100">
          <thead class="bg-gray-50 uppercase text-gray-600 text-xs font-medium">
            <tr>
              <th class="py-3 px-4">Reference</th>
              <th class="py-3 px-4">Merchant</th>
              <th class="py-3 px-4">Amount</th>
              <th class="py-3 px-4">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tx in recentTransactions"
              :key="tx.id"
              class="border-b last:border-0 hover:bg-gray-50 transition"
            >
              <td class="py-3 px-4 font-medium text-gray-800">{{ tx.payment_reference }}</td>
              <td class="py-3 px-4 text-gray-700">{{ tx.merchant?.business_name || '—' }}</td>
              <td class="py-3 px-4 font-semibold text-blue-600">₵{{ tx.amount.toLocaleString() }}</td>
              <td class="py-3 px-4">
                <span
                  :class="statusColor(tx.status)"
                  class="px-2 py-1 rounded text-white text-xs font-semibold"
                >
                  {{ tx.status }}
                </span>
              </td>
            </tr>

            <tr v-if="recentTransactions.length === 0">
              <td colspan="4" class="py-6 text-center text-gray-500">
                No recent transactions available.
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
      stats: {
        merchants: 0,
        transactions: 0,
        settlements: 0,
        fees: 0,
      },
      recentTransactions: [],
    }
  },
  computed: {
    statCards() {
      return [
        {
          label: 'Total Merchants',
          value: this.stats.merchants,
          color: 'text-blue-600',
          prefix: '',
        },
        {
          label: 'Total Transactions',
          value: this.stats.transactions,
          color: 'text-green-600',
          prefix: '',
        },
        {
          label: 'Total Settlements',
          value: this.stats.settlements,
          color: 'text-indigo-600',
          prefix: '',
        },
        {
          label: 'Platform Fees Earned',
          value: this.stats.fees,
          color: 'text-purple-600',
          prefix: '₵',
        },
      ]
    },
  },
  async created() {
    try {
      const statsRes = await api.get('/reports/summary')
      this.stats = statsRes.data.data

      const txRes = await api.get('/transactions')
      this.recentTransactions = txRes.data.data.slice(0, 5)
    } catch (err) {
      console.error('Error fetching dashboard data:', err)
    }
  },
  methods: {
    statusColor(status) {
      switch (status) {
        case 'successful':
          return 'bg-green-500'
        case 'failed':
          return 'bg-red-500'
        default:
          return 'bg-yellow-500'
      }
    },
  },
}
</script>
