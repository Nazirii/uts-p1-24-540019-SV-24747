<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authApi, tokenStore } from '../services/api'

const router = useRouter()
const email = ref('admin@agenda.test')
const password = ref('')
const loading = ref(false)
const error = ref('')

async function submit() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await authApi.login(email.value, password.value)
    tokenStore.set(data.token) // simpan token
    localStorage.setItem('agenda_user', data.user.name)
    router.push({ name: 'agenda' })
  } catch (e) {
    error.value =
      e.response?.data?.errors?.email?.[0] ||
      e.response?.data?.message ||
      'Tidak dapat terhubung ke server.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="login-wrap">
    <form class="card login" @submit.prevent="submit">
      <p class="eyebrow">UTS KEPL 2026</p>
      <h1>Agenda Kegiatan</h1>
      <p class="muted">Masuk untuk mengelola agenda.</p>

      <label>Email
        <input v-model="email" type="email" required autocomplete="username" />
      </label>
      <label>Password
        <input v-model="password" type="password" required autocomplete="current-password" />
      </label>

      <p v-if="error" class="alert">{{ error }}</p>

      <button class="btn primary" :disabled="loading">
        {{ loading ? 'Memproses…' : 'Masuk' }}
      </button>
      <p class="hint">Akun demo: admin@agenda.test / password123</p>
    </form>
  </main>
</template>
