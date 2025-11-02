<script setup>
import PageHeader from '../components/layouts/PageHeader.vue';
</script>
<template>
  <div class="p-8 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <PageHeader title="Transaction Management" subtitle="Simulate customer payments and view transaction history" />
      <!-- <div>
        <h1 class="text-3xl font-bold text-gray-800">Transaction Management</h1>
        <p class="text-gray-500 mt-1">Simulate customer payments and view transaction history</p>
      </div> -->
    </div>

    <!-- Payment Simulation Form -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6 mb-10 max-w-2xl">
      <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded mr-2 text-sm">+</span>
        Simulate Customer Payment
      </h2>

      <form @submit.prevent="makePayment" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Merchant</label>
          <select
            v-model="payment.merchant_id"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          >
            <option value="">Select Merchant</option>
            <option v-for="m in merchants" :key="m.id" :value="m.id">
              {{ m.business_name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Amount (₵)</label>
          <input
            v-model="payment.amount"
            type="number"
            min="1"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-600 mb-1">Payment Reference</label>
          <input
            v-model="payment.payment_reference"
            type="text"
            placeholder="e.g., TXN-12345"
            class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg w-full p-2.5"
            required
          />
        </div>

        <div class="flex items-end md:col-span-2">
          <button
            type="submit"
            class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-all w-full md:w-auto"
          >
            Submit Payment
          </button>
        </div>
      </form>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-4 mb-6 max-w-3xl flex flex-wrap items-end gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Start Date</label>
        <input
          v-model="filters.start_date"
          type="date"
          class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg p-2.5"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">End Date</label>
        <input
          v-model="filters.end_date"
          type="date"
          class="border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg p-2.5"
        />
      </div>

      <button
        @click="filterTransactions"
        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-all"
      >
        Filter
      </button>

      <button
        @click="resetFilters"
        class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition-all"
      >
        Reset
      </button>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white shadow-sm border border-gray-100 rounded-2xl p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Transaction History</h2>
        <p class="text-sm text-gray-500">
          Total: <span class="font-semibold">{{ transactions.length }}</span>
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="p-3 text-gray-600 text-sm">Reference</th>
              <th class="p-3 text-gray-600 text-sm">Merchant</th>
              <th class="p-3 text-gray-600 text-sm">Amount</th>
              <th class="p-3 text-gray-600 text-sm">Status</th>
              <th class="p-3 text-gray-600 text-sm">Date</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tx in transactions"
              :key="tx.id"
              class="hover:bg-gray-50 border-b transition"
            >
              <td class="p-3 font-medium text-gray-800">{{ tx.payment_reference }}</td>
              <td class="p-3 text-gray-700">{{ tx.merchant?.business_name }}</td>
              <td class="p-3 font-semibold text-blue-600">₵{{ tx.amount }}</td>
              <td class="p-3">
                <span
                  :class="[
                    'px-2 py-1 rounded text-white text-xs font-medium',
                    statusColor(tx.status),
                  ]"
                >
                  {{ tx.status }}
                </span>
              </td>
              <td class="p-3 text-gray-600">{{ new Date(tx.created_at).toLocaleString() }}</td>
            </tr>

            <tr v-if="transactions.length === 0">
              <td colspan="5" class="text-center p-4 text-gray-500">
                No transactions found.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../api";

export default {
  data() {
    return {
      merchants: [],
      transactions: [],
      payment: { merchant_id: "", amount: "", payment_reference: "" },
      filters: { start_date: "", end_date: "" },
    };
  },

  async created() {
    await this.fetchData();
  },

  methods: {
    async fetchData(params = {}) {
      try {
        const [merchantsRes, transactionsRes] = await Promise.all([
          api.get("/merchants"),
          api.get("/transactions", { params }),
        ]);
        this.merchants = merchantsRes.data.data || merchantsRes.data;
        this.transactions = transactionsRes.data.data || transactionsRes.data;
      } catch (err) {
        console.error(err);
        alert("Error fetching data");
      }
    },

    async makePayment() {
      try {
        await api.post("/transactions", this.payment);
        alert("Payment simulated successfully!");
        this.payment = { merchant_id: "", amount: "", payment_reference: "" };
        await this.fetchData();
      } catch (err) {
        console.error(err);
        alert("Error processing payment");
      }
    },

    async filterTransactions() {
      await this.fetchData({
        start_date: this.filters.start_date,
        end_date: this.filters.end_date,
      });
    },

    async resetFilters() {
      this.filters = { start_date: "", end_date: "" };
      await this.fetchData();
    },

    statusColor(status) {
      switch (status?.toLowerCase()) {
        case "successful":
          return "bg-green-500";
        case "failed":
          return "bg-red-500";
        default:
          return "bg-yellow-500";
      }
    },
  },
};
</script>
