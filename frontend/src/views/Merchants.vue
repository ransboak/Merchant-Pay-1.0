<script setup>
import PageHeader from '../components/layouts/PageHeader.vue';
</script>
<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <PageHeader title="Merchant Management" subtitle="Add and manage registered merchants" />
      <!-- <div>
        <h1 class="text-3xl font-bold text-gray-800">Merchant Management</h1>
        <p class="text-gray-500 mt-1">Add and manage registered merchants</p>
      </div> -->
    </div>

    <!-- Add Merchant Form -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6 mb-10 max-w-2xl">
      <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded mr-2 text-sm">+</span>
        Add New Merchant
      </h2>

      <form @submit.prevent="addMerchant" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Name</label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Merchant name"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="merchant@example.com"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Business Name</label>
          <input
            v-model="form.business_name"
            type="text"
            placeholder="e.g. Doe Ventures"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Account Number</label>
          <input
            v-model="form.account_number"
            type="text"
            placeholder="Account number"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Bank Name</label>
          <input
            v-model="form.bank_name"
            type="text"
            placeholder="e.g. OmniBSIC"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div class="flex items-end">
          <button
            type="submit"
            class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-all"
          >
            Add Merchant
          </button>
        </div>
      </form>
    </div>

    <!-- Merchant List -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Merchant List</h2>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="p-3 text-gray-600 text-sm">Name</th>
              <th class="p-3 text-gray-600 text-sm">Email</th>
              <th class="p-3 text-gray-600 text-sm">Business</th>
              <th class="p-3 text-gray-600 text-sm">Wallet Balance</th>
              <th class="p-3 text-gray-600 text-sm">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="merchant in merchants"
              :key="merchant.id"
              class="hover:bg-gray-50 border-b"
            >
              <td class="p-3 text-gray-700">{{ merchant.name }}</td>
              <td class="p-3 text-gray-700">{{ merchant.email }}</td>
              <td class="p-3 text-gray-700">{{ merchant.business_name }}</td>
              <td class="p-3 font-semibold text-blue-600">₵{{ merchant.wallet?.balance || 0 }}</td>
              <td class="p-3">
                <button
                  @click="viewTransactions(merchant)"
                  class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition mr-2"
                >
                  Transactions
                </button>
                <button
                  @click="viewSettlements(merchant)"
                  class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg hover:bg-green-200 transition"
                >
                  Settlements
                </button>
              </td>
            </tr>

            <tr v-if="merchants.length === 0">
              <td colspan="5" class="text-center p-4 text-gray-500">No merchants found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Transaction Modal -->
    <div
      v-if="selectedMerchant && showTransactions"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
      <div
        class="bg-white rounded-xl shadow-2xl w-11/12 max-w-4xl p-6 overflow-y-auto max-h-[90vh]"
      >
        <div class="flex justify-between items-center mb-4 border-b pb-2">
          <h2 class="text-xl font-bold text-gray-800">
            {{ selectedMerchant.name }} — Transactions
          </h2>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-red-500 text-2xl font-bold"
          >
            &times;
          </button>
        </div>

        <div class="flex space-x-3 mb-4">
          <input type="date" v-model="filters.start_date" class="border rounded-lg p-2" />
          <input type="date" v-model="filters.end_date" class="border rounded-lg p-2" />
          <button
            @click="fetchMerchantTransactions"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
          >
            Filter
          </button>
        </div>

        <table class="w-full border-collapse text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-gray-600">Reference</th>
              <th class="p-2 text-gray-600">Amount</th>
              <th class="p-2 text-gray-600">Status</th>
              <th class="p-2 text-gray-600">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tx in transactions"
              :key="tx.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-2">{{ tx.payment_reference }}</td>
              <td class="p-2">₵{{ tx.amount }}</td>
              <td class="p-2">
                <span
                  :class="statusColor(tx.status)"
                  class="px-2 py-1 rounded text-white text-xs font-medium"
                >
                  {{ tx.status }}
                </span>
              </td>
              <td class="p-2">{{ new Date(tx.created_at).toLocaleString() }}</td>
            </tr>
            <tr v-if="transactions.length === 0">
              <td colspan="4" class="text-center p-3 text-gray-500">No transactions found</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Settlement Modal -->
    <div
      v-if="selectedMerchant && showSettlements"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
      <div
        class="bg-white rounded-xl shadow-2xl w-11/12 max-w-3xl p-6 overflow-y-auto max-h-[90vh]"
      >
        <div class="flex justify-between items-center mb-4 border-b pb-2">
          <h2 class="text-xl font-bold text-gray-800">
            {{ selectedMerchant.name }} — Settlements
          </h2>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-red-500 text-2xl font-bold"
          >
            &times;
          </button>
        </div>

        <table class="w-full border-collapse text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 text-gray-600">Amount</th>
              <th class="p-2 text-gray-600">Reference</th>
              <th class="p-2 text-gray-600">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="settlement in settlements"
              :key="settlement.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-2">₵{{ settlement.amount }}</td>
              <td class="p-2">{{ settlement.reference }}</td>
              <td class="p-2">{{ new Date(settlement.created_at).toLocaleString() }}</td>
            </tr>
            <tr v-if="settlements.length === 0">
              <td colspan="3" class="text-center p-3 text-gray-500">No settlements found</td>
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
      merchants: [],
      form: { name: '', email: '', business_name: '', account_number: '', bank_name: '' },
      selectedMerchant: null,
      showTransactions: false,
      showSettlements: false,
      transactions: [],
      settlements: [],
      filters: { start_date: '', end_date: '' }
    }
  },
  async created() {
    await this.fetchMerchants()
  },
  methods: {
    async fetchMerchants() {
      try {
        const res = await api.get('/merchants')
        this.merchants = res.data.data || res.data
      } catch (err) {
        console.error('Error fetching merchants:', err)
      }
    },
    async addMerchant() {
      try {
        await api.post('/merchants', this.form)
        alert('✅ Merchant added successfully!')
        this.form = { name: '', email: '', business_name: '', account_number: '', bank_name: '' }
        await this.fetchMerchants()
      } catch (err) {
        alert('Failed to add merchant')
        console.error('Error adding merchant:', err)
      }
    },
    async viewTransactions(merchant) {
      this.selectedMerchant = merchant
      this.showTransactions = true
      this.showSettlements = false
      await this.fetchMerchantTransactions()
    },
    async fetchMerchantTransactions() {
      try {
        const { start_date, end_date } = this.filters
        const res = await api.get(`/transactions`, {
          params: { merchant_id: this.selectedMerchant.id, start_date, end_date }
        })
        this.transactions = res.data.data || res.data
      } catch {
        this.transactions = []
      }
    },
    async viewSettlements(merchant) {
      this.selectedMerchant = merchant
      this.showSettlements = true
      this.showTransactions = false
      try {
        const res = await api.get(`/settlements`, { params: { merchant_id: merchant.id } })
        this.settlements = res.data.data || res.data
      } catch {
        this.settlements = []
      }
    },
    closeModal() {
      this.showTransactions = false
      this.showSettlements = false
      this.selectedMerchant = null
    },
    statusColor(status) {
      switch (status?.toLowerCase()) {
        case 'successful':
          return 'bg-green-500'
        case 'failed':
          return 'bg-red-500'
        default:
          return 'bg-yellow-500'
      }
    }
  }
}
</script>
