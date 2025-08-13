<template>
  <AppShell>
    <AppSidebar />
    <!-- Contenido principal -->
    <div class="min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">📸 Servicios</h1>
            <p class="text-slate-300">Gestión del catálogo fotográfico</p>
          </div>
          <div class="flex items-center space-x-4">
            <Link :href="route('servicios.create')" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">
              <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              Nuevo Servicio
            </Link>
            <button @click="generateReport" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
              <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Generar Reporte
            </button>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <!-- Filtros y búsqueda -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6 mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar servicios</label>
              <div class="relative">
                <input v-model="searchTerm" type="text" id="search" placeholder="Buscar por nombre, precio o descripción..." class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div>
                <label for="filter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por estado</label>
                <select v-model="filterStatus" id="filter" class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm">
                  <option value="">Todos</option>
                  <option value="1">Activos</option>
                  <option value="0">Inactivos</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Lista de servicios -->
        <div v-if="filteredServicios.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
          <div v-for="servicio in filteredServicios" :key="servicio.id" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 overflow-hidden hover:shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
            <div class="relative">
              <div class="w-full h-48 bg-slate-700/50">
                <img v-if="servicio.imagenReferencia" :src="getImageUrl(servicio.imagenReferencia)" :alt="servicio.nombreServicio" class="w-full h-48 object-cover" />
                <div v-else class="w-full h-48 bg-gradient-to-br from-amber-500/20 via-pink-500/20 to-purple-600/20 flex items-center justify-center backdrop-blur-sm">
                  <div class="w-16 h-16 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                  </div>
                </div>
              </div>
              <div class="absolute top-4 right-4">
                <span :class="servicio.estado == 1 ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'" class="px-3 py-1 rounded-full text-sm font-semibold border backdrop-blur-sm">{{ servicio.estado == 1 ? 'Activo' : 'Inactivo' }}</span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-bold text-white mb-2">{{ servicio.nombreServicio }}</h3>
              <p class="text-slate-300 mb-4 leading-relaxed">{{ servicio.descripcion || 'Sin descripción' }}</p>
              <div class="flex items-center justify-between mb-4">
                <span class="text-2xl font-bold text-amber-400">Bs. {{ Number(servicio.precioReferencial).toFixed(2) }}</span>
                <div class="flex items-center space-x-2">
                  <Link :href="route('servicios.show', servicio.id)" class="text-amber-400 hover:text-amber-300 transition-colors p-2 hover:bg-amber-500/20 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </Link>
                  <Link :href="route('servicios.edit', servicio.id)" class="text-pink-400 hover:text-pink-300 transition-colors p-2 hover:bg-pink-500/20 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </Link>
                  <button @click="deleteService(servicio.id)" class="text-red-400 hover:text-red-300 transition-colors p-2 hover:bg-red-500/20 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div v-else class="text-center py-16">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-12">
            <div class="w-24 h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">No hay servicios registrados</h3>
            <p class="text-slate-300 mb-8">Comienza agregando tu primer servicio al catálogo.</p>
            <Link :href="route('servicios.create')" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              Crear Primer Servicio
            </Link>
          </div>
        </div>
      </main>
    </div>
  </AppShell>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

const props = defineProps({
  servicios: { type: Array, required: true }
})

const searchTerm = ref('')
const filterStatus = ref('')

const filteredServicios = computed(() => {
  let filtered = props.servicios || []
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase()
    filtered = filtered.filter((s) =>
      (s.nombreServicio || '').toLowerCase().includes(term) ||
      (s.descripcion || '').toLowerCase().includes(term) ||
      String(s.precioReferencial).includes(term)
    )
  }
  if (filterStatus.value !== '') {
    filtered = filtered.filter((s) => String(s.estado) === String(filterStatus.value))
  }
  return filtered
})

const generateReport = () => alert('Función de reporte en desarrollo')
const deleteService = (id) => { if (confirm('¿Eliminar servicio?')) router.delete(route('servicios.destroy', id)) }
const getImageUrl = (path) => { if (!path) return ''; const normalized = path.replace(/\\\\/g, '/').replace(/\\/g, '/'); return `/storage/${normalized}` }
</script>