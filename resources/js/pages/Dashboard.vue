<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black text-slate-100">
    <div class="bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 text-white py-10 px-6 shadow-lg">
      <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight drop-shadow">✨ Bienvenidos a la App Foto Estudio EU</h1>
        <p class="text-white/90 mt-2">Explora y gestiona servicios, usuarios y roles del estudio</p>
      </div>
    </div>

    <div class="max-w-6xl mx-auto p-6 space-y-8">
      <!-- Carrusel de servicios -->
      <ServiceCarousel v-if="servicios && servicios.length" :servicios="servicios" />

      <!-- Estadísticas -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-2">
        <div class="bg-white/10 backdrop-blur border border-white/10 rounded-2xl shadow-lg p-6 text-center">
          <div class="mx-auto mb-3 w-12 h-12 rounded-full bg-gradient-to-r from-pink-500 to-fuchsia-500 grid place-items-center text-white text-xl">📸</div>
          <h3 class="text-2xl font-extrabold">24</h3>
          <p class="text-white/80">Servicios</p>
        </div>
        <div class="bg-white/10 backdrop-blur border border-white/10 rounded-2xl shadow-lg p-6 text-center">
          <div class="mx-auto mb-3 w-12 h-12 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 grid place-items-center text-white text-xl">💰</div>
          <h3 class="text-2xl font-extrabold">$12,450</h3>
          <p class="text-white/80">Ingresos</p>
        </div>
        <div class="bg-white/10 backdrop-blur border border-white/10 rounded-2xl shadow-lg p-6 text-center">
          <div class="mx-auto mb-3 w-12 h-12 rounded-full bg-gradient-to-r from-sky-400 to-indigo-500 grid place-items-center text-white text-xl">👥</div>
          <h3 class="text-2xl font-extrabold">15</h3>
          <p class="text-white/80">Clientes</p>
        </div>
        <div class="bg-white/10 backdrop-blur border border-white/10 rounded-2xl shadow-lg p-6 text-center">
          <div class="mx-auto mb-3 w-12 h-12 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 grid place-items-center text-white text-xl">⏱️</div>
          <h3 class="text-2xl font-extrabold">7</h3>
          <p class="text-white/80">Pendientes</p>
        </div>
      </div>

      <!-- Navegación rápida -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="/servicios" class="bg-white/10 border border-white/10 rounded-2xl shadow p-6 hover:border-pink-500/50 hover:shadow-pink-500/20 transition-all text-center">
          <div class="text-4xl mb-4">📸</div>
          <h3 class="text-lg font-semibold">Servicios</h3>
          <p class="text-white/80 text-sm">Gestionar catálogo</p>
        </a>
        
        <a href="/usuarios" class="bg-white/10 border border-white/10 rounded-2xl shadow p-6 hover:border-fuchsia-500/50 hover:shadow-fuchsia-500/20 transition-all text-center">
          <div class="text-4xl mb-4">👥</div>
          <h3 class="text-lg font-semibold">Usuarios</h3>
          <p class="text-white/80 text-sm">Gestionar personal</p>
        </a>
        
        <a href="/roles" class="bg-white/10 border border-white/10 rounded-2xl shadow p-6 hover:border-amber-500/50 hover:shadow-amber-500/20 transition-all text-center">
          <div class="text-4xl mb-4">👑</div>
          <h3 class="text-lg font-semibold">Roles</h3>
          <p class="text-white/80 text-sm">Configurar permisos</p>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ServiceCarousel from '@/components/ServiceCarousel.vue'

const props = defineProps<{
  servicios?: Array<{
    id: number
    nombreServicio: string
    precioReferencial: number | string
    imagenReferencia: string | null
  }>
}>()

console.log('Dashboard cargado correctamente')
</script>
