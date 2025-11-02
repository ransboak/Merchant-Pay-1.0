<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
      <h1 class="text-2xl font-semibold mb-6 text-center">Merchant Wallet Login</h1>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label class="block mb-1 text-gray-600">Email</label>
          <input v-model="email" type="email" class="w-full border p-2 rounded" required />
        </div>
        <div class="mb-4">
          <label class="block mb-1 text-gray-600">Password</label>
          <input v-model="password" type="password" class="w-full border p-2 rounded" required />
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
          Login
        </button>
        <p v-if="error" class="text-red-500 text-sm mt-3 text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const email = ref('');
const password = ref('');
const error = ref('');

const login = async () => {
  try {
    const res = await api.post('/login', { email: email.value, password: password.value });
    localStorage.setItem('token', res.data.data.token);
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed';
  }
};
</script>
