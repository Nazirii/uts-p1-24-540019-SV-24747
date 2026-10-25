// Alamat API diambil dari variabel lingkungan Vite (VITE_API_URL),
// tidak ditulis mati di komponen.
//  - Lokal (npm run dev): isi di frontend/.env  -> VITE_API_URL=http://localhost:8000/api
//  - Docker: diisi saat build (build arg) -> /api, lalu Nginx meneruskan ke service "backend"
export const API_URL = import.meta.env.VITE_API_URL || '/api'
