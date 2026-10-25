# =============================================================
# Frontend Vue - multi-stage build (soal 5b)
# =============================================================

# ---------- Tahap 1: build dengan Node.js ----------
FROM node:22.11-alpine AS build
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
# Alamat API diatur saat build. Default "/api" -> diproxy Nginx ke service backend.
ARG VITE_API_URL=/api
ENV VITE_API_URL=$VITE_API_URL
RUN npm run build

# ---------- Tahap 2: sajikan hasil build dengan Nginx ----------
FROM nginx:1.27-alpine
COPY nginx.conf /etc/nginx/conf.d/default.conf
# Hanya folder dist yang disalin -> image akhir TIDAK berisi node_modules
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
