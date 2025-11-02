<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-700 text-white flex flex-col">
      <div class="p-6 text-center border-b border-blue-600">
        <h1 class="text-2xl font-bold">Merchant Wallet</h1>
      </div>

      <nav class="flex-1 mt-6 space-y-2 px-4">
        <RouterLink
          to="/dashboard"
          class="block px-4 py-2 rounded hover:bg-blue-600"
          active-class="bg-blue-900"
        >
          Dashboard
        </RouterLink>

        <RouterLink
          to="/merchants"
          class="block px-4 py-2 rounded hover:bg-blue-600"
          active-class="bg-blue-900"
        >
          Merchants
        </RouterLink>

        <RouterLink
          to="/transactions"
          class="block px-4 py-2 rounded hover:bg-blue-600"
          active-class="bg-blue-900"
        >
          Transactions
        </RouterLink>

        <RouterLink
          to="/settlements"
          class="block px-4 py-2 rounded hover:bg-blue-600"
          active-class="bg-blue-900"
        >
          Settlements
        </RouterLink>

        <!-- <RouterLink
          to="/reports"
          class="block px-4 py-2 rounded hover:bg-blue-600"
          active-class="bg-blue-900"
        >
          Reports
        </RouterLink> -->
      </nav>

      <div class="p-4 border-t border-blue-600">
        <button
          @click="logout"
          class="w-full bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white font-medium"
        >
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
      <RouterView />
    </main>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      localStorage.removeItem('token')
      this.$router.push('/')
    }
  }
}
</script>
