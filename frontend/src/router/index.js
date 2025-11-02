// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';
import Merchants from '../views/Merchants.vue';
import Transactions from '../views/Transactions.vue';
import Settlements from '../views/Settlements.vue';

const routes = [
  { path: '/', name: 'login', component: Login, meta: { guestOnly: true } },
  { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/merchants', name: 'merchants', component: Merchants, meta: { requiresAuth: true } },
  { path: '/transactions', name: 'transactions', component: Transactions, meta: { requiresAuth: true } },
  { path: '/settlements', name: 'settlements', component: Settlements, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  // If route requires authentication but no token, go to login
  if (to.meta.requiresAuth && !token) {
    next({ name: 'login' });
  }
  // If route is guest only and user is already logged in, go to dashboard
  else if (to.meta.guestOnly && token) {
    next({ name: 'dashboard' });
  }
  // Otherwise, proceed as normal
  else {
    next();
  }
});

export default router;

