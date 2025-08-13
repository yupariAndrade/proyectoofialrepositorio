<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ trabajos: { type: Array, required: true } })

const searchTerm = ref('')
const filterEstado = ref('')
const filterCliente = ref('')

const filteredTrabajos = computed(() => {
  let filtered = props.trabajos || []
  if (searchTerm.value) {
    const s = searchTerm.value.toLowerCase()
    filtered = filtered.filter((t) =>
      (t.cliente?.nombre || '').toLowerCase().includes(s) ||
      (t.cliente?.apellido || '').toLowerCase().includes(s) ||
      (t.servicio?.nombreServicio || '').toLowerCase().includes(s) ||
      (t.usuario?.nombre || '').toLowerCase().includes(s) ||
      (t.estado?.nombre || '').toLowerCase().includes(s)
    )
  }
  if (filterEstado.value) filtered = filtered.filter((t) => String(t.idEstado) === String(filterEstado.value))
  if (filterCliente.value) filtered = filtered.filter((t) => String(t.idCliente) === String(filterCliente.value))
  return filtered
})

const availableEstados = computed(() => {
  const map = new Map()
  ;(props.trabajos || []).forEach((t) => { if (t.estado) map.set(t.estado.id, t.estado.nombre) })
  return Array.from(map.entries()).map(([id, nombre]) => ({ id, nombre }))
})

const availableClientes = computed(() => {
  const map = new Map()
  ;(props.trabajos || []).forEach((t) => { if (t.cliente) map.set(t.cliente.id, `${t.cliente.nombre} ${t.cliente.apellido || ''}`) })
  return Array.from(map.entries()).map(([id, nombre]) => ({ id, nombre }))
})

const deleteTrabajo = (id) => { if (confirm('¿Eliminar trabajo?')) router.delete(route('trabajos.destroy', id)) }

const getEstadoColor = (nombre) => {
  const n = (nombre || '').toLowerCase()
  if (n.includes('pendiente') || n.includes('proceso')) return 'amber'
  if (n.includes('completado') || n.includes('finalizado')) return 'green'
  if (n.includes('cancelado') || n.includes('rechazado')) return 'red'
  if (n.includes('aprobado')) return 'blue'
  return 'purple'
}

const formatDate = (dateString) => { try { return new Date(dateString).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' }) } catch { return '—' } }
const formatCurrency = (amount) => new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(Number(amount || 0))
</script>

<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">💼 Trabajos</h1>
              <p class="text-slate-300">Gestión de trabajos del estudio fotográfico</p>
            </div>
            <div class="flex items-center space-x-4">
              <Link :href="route('trabajos.create')" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nuevo Trabajo
              </Link>
              <button @click="() => alert('Función de reporte en desarrollo')" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
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
                <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar trabajos</label>
                <div class="relative">
                  <input v-model="searchTerm" type="text" id="search" placeholder="Buscar por cliente, servicio, usuario o estado..." class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                  </div>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div>
                  <label for="estadoFilter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por estado</label>
                  <select v-model="filterEstado" id="estadoFilter" class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm">
                    <option value="">Todos</option>
                    <option v-for="estado in availableEstados" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
                  </select>
                </div>
                <div>
                  <label for="clienteFilter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por cliente</label>
                  <select v-model="filterCliente" id="clienteFilter" class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm">
                    <option value="">Todos</option>
                    <option v-for="cliente in availableClientes" :key="cliente.id" :value="cliente.id">{{ cliente.nombre }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Lista de trabajos -->
          <div v-if="filteredTrabajos.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
            <div v-for="trabajo in filteredTrabajos" :key="trabajo.id" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 overflow-hidden hover:shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
              <div class="relative">
                <div class="w-full h-32 bg-gradient-to-br from-amber-500/20 via-pink-500/20 to-purple-600/20 flex items-center justify-center backdrop-blur-sm">
                  <div class="w-16 h-16 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">💼</div>
                </div>
                <div class="absolute top-3 right-3">
                  <span :class="`bg-${getEstadoColor(trabajo.estado?.nombre || '')}-500/20 text-${getEstadoColor(trabajo.estado?.nombre || '')}-400 border-${getEstadoColor(trabajo.estado?.nombre || '')}-500/30 px-3 py-1 rounded-full text-sm font-semibold border backdrop-blur-sm`">{{ trabajo.estado?.nombre || 'Sin estado' }}</span>
                </div>
              </div>
              <div class="p-6">
                <h3 class="text-xl font-bold text-white mb-2">{{ trabajo.servicio?.nombreServicio || 'Servicio no especificado' }}</h3>
                <p class="text-slate-300 mb-2">Cliente: {{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido || '' }}</p>
                <p class="text-slate-400 mb-2">Usuario: {{ trabajo.usuario?.nombre || 'No asignado' }}</p>
                <p class="text-amber-400 mb-2 font-semibold">{{ formatCurrency(trabajo.servicio?.precioReferencial || 0) }}</p>
                <p class="text-slate-400 mb-4">Registro: {{ formatDate(trabajo.fechaRegistro) }}</p>
                <div class="flex items-center justify-between mb-4">
                  <!--<span class="text-sm text-slate-400">ID: #{{ trabajo.id }}</span> -->
                  <div class="flex items-center space-x-2">
                    <Link :href="route('trabajos.show', trabajo.id)" class="text-amber-400 hover:text-amber-300 transition-colors p-2 hover:bg-amber-500/20 rounded-lg">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </Link>
                    <Link :href="route('trabajos.edit', trabajo.id)" class="text-pink-400 hover:text-pink-300 transition-colors p-2 hover:bg-pink-500/20 rounded-lg">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </Link>
                    <button @click="deleteTrabajo(trabajo.id)" class="text-red-400 hover:text-red-300 transition-colors p-2 hover:bg-red-500/20 rounded-lg">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </div>
                </div>
              </div>
              <div class="text-xs text-slate-500">Creado: {{ formatDate(trabajo.created_at) }}</div>
            </div>
          </div>

          <div v-else class="text-center py-16">
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-12">
              <div class="w-24 h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h4a2 2 0 012 2v2m-8 0v2a2 2 0 002 2h4a2 2 0 002-2v-2"/></svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">No hay trabajos registrados</h3>
              <p class="text-slate-300 mb-8">Comienza agregando el primer trabajo.</p>
              <Link :href="route('trabajos.create')" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Crear Primer Trabajo
              </Link>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
