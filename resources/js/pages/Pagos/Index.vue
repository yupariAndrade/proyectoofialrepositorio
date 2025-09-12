<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">Control de Pagos</h1>
            <p class="text-slate-400 mt-2">Gestión y seguimiento de pagos según el tipo de trabajo</p>
          </div>
          <div class="flex space-x-3">
            <Link :href="route('pagos.create')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg hover:from-amber-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              Nuevo Pago
            </Link>
            <button @click="generateReport" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Generar Reporte
            </button>
          </div>
        </div>
      </div>

      <!-- Resumen por cliente -->
      <div class="mb-6 grid md:grid-cols-5 gap-4">
        <div class="md:col-span-2 bg-gradient-to-r from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-4">
          <label class="block text-sm font-medium text-slate-300 mb-2">Cliente</label>
          <select v-model="clienteSeleccionado" @change="onClienteChange" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50">
            <option value="">Todos</option>
            <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }} {{ c.apellido || '' }}</option>
          </select>
        </div>
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-4">
          <div class="text-slate-400 text-sm">Total trabajos</div>
          <div class="text-white text-xl font-semibold">{{ currency(resumen?.totalTrabajos || 0) }}</div>
        </div>
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-4">
          <div class="text-slate-400 text-sm">Total pagado</div>
          <div class="text-emerald-300 text-xl font-semibold">{{ currency(resumen?.totalPagado || 0) }}</div>
        </div>
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-4">
          <div class="text-slate-400 text-sm">Pendiente</div>
          <div class="text-white text-xl font-semibold">{{ currency(resumen?.totalPendiente || 0) }}</div>
        </div>
      </div>

      <div v-if="clienteSeleccionado && trabajosPendientes?.length" class="mb-6 flex items-center justify-between bg-gradient-to-r from-cyan-500/10 to-indigo-600/10 border border-cyan-500/20 rounded-xl p-4">
        <div class="text-slate-300">Trabajos pendientes de cobrar: <span class="font-semibold text-white">{{ trabajosPendientes.length }}</span></div>
        <button @click="cobrarPendientes" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:to-indigo-700 transition">Cobrar trabajos del cliente</button>
      </div>

      <!-- Search and Filter Section -->
      <div class="mb-6 space-y-4">
        <!-- Search -->
        <div class="relative">
          <input v-model="searchTerm" type="text" placeholder="Buscar pagos..." class="w-full px-4 py-3 pl-12 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300" />
          <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Filtrar por Trabajo</label>
            <select v-model="filterTrabajo" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300">
              <option value="">Todos los trabajos</option>
              <option v-for="trabajo in availableTrabajos" :key="trabajo.idTrabajo" :value="trabajo.idTrabajo">Trabajo #{{ trabajo.idTrabajo }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Filtrar por Estado</label>
            <select v-model="filterEstado" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300">
              <option value="">Todos los estados</option>
              <option v-for="estado in availableEstados" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Filtrar por Fecha</label>
            <input v-model="filterDate" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300" />
          </div>
        </div>
      </div>

      <!-- Pagos Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="pago in filteredPagos"
          :key="pago.idPago"
          class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl border border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105"
        >
          <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                  {{ getInitials(pago.monto) }}
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-white">{{ formatCurrency(pago.monto) }}</h3>
                  <p class="text-slate-400 text-sm">Trabajo #{{ pago.idTrabajo }}</p>
                </div>
              </div>
              <span 
                :class="getEstadoColor(pago.estadoPago?.nombre)"
                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium border"
              >
                {{ pago.estadoPago?.nombre || 'Sin estado' }}
              </span>
            </div>

            <!-- Info -->
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
                <span class="text-slate-300">ID: {{ pago.idPago }}</span>
              </div>
              <div class="flex items-center text-sm">
                <svg class="w-4 h-4 text-emerald-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-slate-300">{{ formatDate(pago.fecha) }}</span>
              </div>
              <div v-if="pago.comentario" class="flex items-center text-sm">
                <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span class="text-slate-300 truncate">{{ pago.comentario }}</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2">
              <Link
                :href="route('pagos.show', pago.idPago)"
                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 text-sm"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Ver
              </Link>
              <Link
                :href="route('pagos.edit', pago.idPago)"
                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg hover:from-amber-600 hover:to-pink-600 transition-all duration-300 text-sm"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
              </Link>
              <button
                @click="deletePago(pago.idPago)"
                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-300 text-sm"
              >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Eliminar
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredPagos.length === 0" class="text-center py-12">
        <div class="w-24 h-24 bg-gradient-to-r from-slate-700 to-slate-600 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No hay pagos registrados</h3>
        <p class="text-slate-400 mb-6">Comienza registrando el primer pago para el sistema</p>
        <Link
          :href="route('pagos.create')"
          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg hover:from-amber-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Registrar Primer Pago
        </Link>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ pagos: Array, filtroTrabajo: [String, Number], filtroEstado: [String, Number], clientes: Array, resumen: Object, trabajosPendientes: Array })

const clientes = props.clientes || []
const resumen = props.resumen || null
const trabajosPendientes = props.trabajosPendientes || []
const clienteSeleccionado = ref(resumen?.clienteId || '')

const onClienteChange = () => {
  const q = clienteSeleccionado.value ? { cliente: clienteSeleccionado.value } : {}
  router.get(route('pagos'), q, { preserveState: true, preserveScroll: true })
}

const cobrarPendientes = () => {
  if (!clienteSeleccionado.value || !trabajosPendientes.length) return
  router.get(route('pagos.checkout', { trabajos: trabajosPendientes }))
}

const searchTerm = ref('')
const filterTrabajo = ref(props.filtroTrabajo || '')
const filterEstado = ref(props.filtroEstado || '')
const filterDate = ref('')

const filteredPagos = computed(() => {
  let filtered = props.pagos
  if (searchTerm.value) filtered = filtered.filter((p) => p.monto.toString().includes(searchTerm.value) || (p.comentario || '').toLowerCase().includes(searchTerm.value.toLowerCase()))
  if (filterTrabajo.value) filtered = filtered.filter((p) => p.idTrabajo == filterTrabajo.value)
  if (filterEstado.value) filtered = filtered.filter((p) => p.idEstadoPago == filterEstado.value)
  if (filterDate.value) filtered = filtered.filter((p) => p.fecha === filterDate.value)
  return filtered
})

const availableTrabajos = computed(() => {
  const trabajos = [...new Set(props.pagos.map((p) => p.idTrabajo))]
  return trabajos.map((id) => ({ idTrabajo: id }))
})

const availableEstados = computed(() => props.pagos.filter((p) => p.estadoPago).map((p) => p.estadoPago).filter((e, i, self) => self.findIndex((x) => x.id === e.id) === i))

const deletePago = (id) => { if (confirm('¿Estás seguro de que quieres eliminar este pago?')) router.delete(route('pagos.destroy', id)) }
const generateReport = () => alert('Función de reporte en desarrollo')
const getInitials = () => 'PA'
const formatCurrency = (n) => new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(n || 0)
const currency = formatCurrency
const formatDate = (d) => { try { return new Date(d).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) } catch { return 'Fecha inválida' } }
const getEstadoColor = (estadoNombre) => {
  if (!estadoNombre) return 'bg-slate-500/20 text-slate-400 border-slate-500/30'
  const estado = estadoNombre.toLowerCase()
  if (estado.includes('pagado') || estado.includes('completado')) return 'bg-green-500/20 text-green-400 border-green-500/30'
  if (estado.includes('pendiente')) return 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'
  if (estado.includes('cancelado') || estado.includes('rechazado')) return 'bg-red-500/20 text-red-400 border-red-500/30'
  if (estado.includes('parcial')) return 'bg-blue-500/20 text-blue-400 border-blue-500/30'
  return 'bg-slate-500/20 text-slate-400 border-slate-500/30'
}
</script>













