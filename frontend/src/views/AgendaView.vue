<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { agendaApi, authApi, tokenStore } from '../services/api'

const router = useRouter()
const userName = localStorage.getItem('agenda_user') || 'Pengguna'

const agendas = ref([])
const loading = ref(false)
const saving = ref(false)
const notice = ref('')
const errors = ref({})
const editingId = ref(null)

const empty = () => ({ judul: '', tanggal: '', waktu: '', lokasi: '', keterangan: '' })
const form = reactive(empty())

const formTitle = computed(() => (editingId.value ? 'Ubah Agenda' : 'Tambah Agenda'))

function flash(msg) {
  notice.value = msg
  setTimeout(() => (notice.value = ''), 2500)
}

async function load() {
  loading.value = true
  try {
    const { data } = await agendaApi.list()
    agendas.value = data.data
  } finally {
    loading.value = false
  }
}

function resetForm() {
  Object.assign(form, empty())
  editingId.value = null
  errors.value = {}
}

function edit(item) {
  editingId.value = item.id
  errors.value = {}
  Object.assign(form, {
    judul: item.judul,
    tanggal: item.tanggal,
    waktu: item.waktu || '',
    lokasi: item.lokasi,
    keterangan: item.keterangan || '',
  })
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

async function save() {
  saving.value = true
  errors.value = {}
  const payload = { ...form, waktu: form.waktu || null, keterangan: form.keterangan || null }
  try {
    if (editingId.value) {
      await agendaApi.update(editingId.value, payload)
      flash('Agenda berhasil diperbarui')
    } else {
      await agendaApi.create(payload)
      flash('Agenda berhasil ditambahkan')
    }
    resetForm()
    await load()
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors
    else flash('Gagal menyimpan data')
  } finally {
    saving.value = false
  }
}

async function remove(item) {
  if (!confirm(`Hapus agenda "${item.judul}"?`)) return
  await agendaApi.remove(item.id)
  if (editingId.value === item.id) resetForm()
  flash('Agenda berhasil dihapus')
  await load()
}

async function logout() {
  try { await authApi.logout() } catch (_) { /* abaikan */ }
  tokenStore.clear()
  localStorage.removeItem('agenda_user')
  router.push({ name: 'login' })
}

function formatTanggal(t) {
  return new Date(t + 'T00:00:00').toLocaleDateString('id-ID', {
    weekday: 'short', day: 'numeric', month: 'short', year: 'numeric',
  })
}

onMounted(load)
</script>

<template>
  <header class="topbar">
    <div>
      <strong>Agenda Kegiatan</strong>
      <span class="muted"> · {{ userName }}</span>
    </div>
    <button class="btn ghost" @click="logout">Keluar</button>
  </header>

  <main class="container">
    <p v-if="notice" class="toast">{{ notice }}</p>

    <section class="card">
      <h2>{{ formTitle }}</h2>
      <form class="grid" @submit.prevent="save">
        <label class="span-2">Judul kegiatan
          <input v-model="form.judul" placeholder="mis. Rapat koordinasi" />
          <small v-if="errors.judul" class="err">{{ errors.judul[0] }}</small>
        </label>
        <label>Tanggal
          <input v-model="form.tanggal" type="date" />
          <small v-if="errors.tanggal" class="err">{{ errors.tanggal[0] }}</small>
        </label>
        <label>Waktu
          <input v-model="form.waktu" type="time" />
          <small v-if="errors.waktu" class="err">{{ errors.waktu[0] }}</small>
        </label>
        <label class="span-2">Lokasi
          <input v-model="form.lokasi" placeholder="mis. Ruang 2.1" />
          <small v-if="errors.lokasi" class="err">{{ errors.lokasi[0] }}</small>
        </label>
        <label class="span-2">Keterangan
          <textarea v-model="form.keterangan" rows="2" placeholder="Opsional" />
        </label>
        <div class="span-2 actions">
          <button class="btn primary" :disabled="saving">
            {{ saving ? 'Menyimpan…' : editingId ? 'Simpan Perubahan' : 'Tambah' }}
          </button>
          <button v-if="editingId" type="button" class="btn ghost" @click="resetForm">Batal</button>
        </div>
      </form>
    </section>

    <section class="card">
      <h2>Daftar Agenda <span class="count">{{ agendas.length }}</span></h2>
      <p v-if="loading" class="muted">Memuat…</p>
      <p v-else-if="!agendas.length" class="muted">Belum ada agenda.</p>
      <div v-else class="table-wrap">
        <table>
          <thead>
            <tr><th>Tanggal</th><th>Waktu</th><th>Kegiatan</th><th>Lokasi</th><th></th></tr>
          </thead>
          <tbody>
            <tr v-for="a in agendas" :key="a.id" :class="{ active: a.id === editingId }">
              <td class="nowrap">{{ formatTanggal(a.tanggal) }}</td>
              <td>{{ a.waktu || '—' }}</td>
              <td>
                <strong>{{ a.judul }}</strong>
                <div v-if="a.keterangan" class="muted small">{{ a.keterangan }}</div>
              </td>
              <td>{{ a.lokasi }}</td>
              <td class="nowrap right">
                <button class="btn small" @click="edit(a)">Ubah</button>
                <button class="btn small danger" @click="remove(a)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</template>
