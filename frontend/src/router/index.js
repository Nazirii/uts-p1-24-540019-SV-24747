import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import AgendaView from '../views/AgendaView.vue'
import { tokenStore } from '../services/api'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/agenda' },
    { path: '/login', name: 'login', component: LoginView },
    { path: '/agenda', name: 'agenda', component: AgendaView, meta: { auth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/agenda' },
  ],
})

router.beforeEach((to) => {
  const loggedIn = !!tokenStore.get()
  if (to.meta.auth && !loggedIn) return { name: 'login' }
  if (to.name === 'login' && loggedIn) return { name: 'agenda' }
})

export default router
