// stores/auth.js
import { defineStore } from 'pinia'
import api from '../api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
  }),
  actions: {
    async fetchUser() {
      try {
        const res = await api.get('/user') // Sanctum /api/user endpoint
        this.user = res.data
      } catch {
        this.user = null
      }
    },
    logout() {
      this.user = null
      api.post('/logout')
    }
  }
})
