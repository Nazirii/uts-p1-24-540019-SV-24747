import axios from 'axios'
import { API_URL } from '../config'

const TOKEN_KEY = 'agenda_token'

export const tokenStore = {
  get: () => localStorage.getItem(TOKEN_KEY),
  set: (t) => localStorage.setItem(TOKEN_KEY, t),
  clear: () => localStorage.removeItem(TOKEN_KEY),
}

const api = axios.create({
  baseURL: API_URL,
  headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
})

// Kirim token di setiap request
api.interceptors.request.use((config) => {
  const token = tokenStore.get()
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

// Token kadaluarsa / tidak valid -> kembali ke login
api.interceptors.response.use(
  (res) => res,
  (err) => {
    if (err.response?.status === 401 && !err.config.url.endsWith('/login')) {
      tokenStore.clear()
      window.location.href = '/login'
    }
    return Promise.reject(err)
  }
)

export const authApi = {
  login: (email, password) => api.post('/login', { email, password }),
  logout: () => api.post('/logout'),
  me: () => api.get('/me'),
}

export const agendaApi = {
  list: () => api.get('/agendas'),
  get: (id) => api.get(`/agendas/${id}`),
  create: (data) => api.post('/agendas', data),
  update: (id, data) => api.put(`/agendas/${id}`, data),
  remove: (id) => api.delete(`/agendas/${id}`),
}

export default api
