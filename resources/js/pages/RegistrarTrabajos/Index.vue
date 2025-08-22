<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              📝 Trabajos Registrados
            </h2>
            <p class="text-slate-300">Gestión de todos los trabajos y su estado</p>
          </div>
          <Link 
            :href="route('registrar-trabajos.create')"
            class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-amber-500/25"
          >
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Nuevo Trabajo
          </Link>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div v-if="page.props.flash?.success" class="mb-4 p-4 bg-green-900 border border-green-400 text-green-200 rounded-lg">
          {{ page.props.flash.success }}
        </div>
        
        <div v-if="page.props.flash?.error" class="mb-4 p-4 bg-red-900 border border-red-400 text-red-200 rounded-lg">
          {{ page.props.flash.error }}
        </div>

        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 p-6 rounded-xl shadow-lg mb-6 border border-white/10">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-slate-200">Filtros de Búsqueda</h3>
            <div class="flex space-x-2">
              <button 
                @click="vistaConsolidada = false"
                :class="!vistaConsolidada ? 'bg-amber-500 text-white' : 'bg-slate-700 text-slate-300'"
                class="px-4 py-2 rounded-lg transition-colors duration-200"
              >
                📋 Vista Individual
              </button>
              <button 
                @click="vistaConsolidada = true"
                :class="vistaConsolidada ? 'bg-amber-500 text-white' : 'bg-slate-700 text-slate-300'"
                class="px-4 py-2 rounded-lg transition-colors duration-200"
              >
                💰 Vista Consolidada
              </button>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Cliente</label>
              <select 
                v-model="filtros.cliente" 
                class="w-full bg-slate-800/50 border border-slate-600/50 rounded-xl text-white px-4 py-3 shadow-inner"
              >
                <option value="">Todos los clientes</option>
                <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                  {{ cliente.nombre }} {{ cliente.apellido }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Estado del Trabajo</label>
              <select 
                v-model="filtros.estadoTrabajo" 
                class="w-full bg-slate-800/50 border border-slate-600/50 rounded-xl text-white px-4 py-3 shadow-inner"
              >
                <option value="">Todos los estados</option>
                <option v-for="estado in estadosTrabajo" :key="estado.id" :value="estado.id">
                  {{ estado.nombre }}
                </option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-300 mb-2">Estado del Pago</label>
              <select 
                v-model="filtros.estadoPago" 
                class="w-full bg-slate-800/50 border border-slate-600/50 rounded-xl text-white px-4 py-3 shadow-inner"
              >
                <option value="">Todos los estados</option>
                <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                  {{ estado.nombre }}
                </option>
              </select>
            </div>
            <div class="flex items-end">
              <button 
                @click="aplicarFiltros"
                class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-xl transition-colors duration-200"
              >
                Filtrar
              </button>
            </div>
          </div>
        </div>

        <div class="bg-slate-900/50 overflow-hidden shadow-lg sm:rounded-lg border border-white/10">
          <!-- Vista Consolidada -->
          <div v-if="vistaConsolidada" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
              <thead class="bg-gradient-to-r from-slate-800/70 to-slate-900/70">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Cliente
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Trabajos
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Total General
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Total A Cuenta
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Saldo Total
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Estados Trabajo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Estados Pago
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-slate-900/50 divide-y divide-white/10">
                <tr v-for="cliente in consolidacionPorCliente" :key="cliente.id" class="hover:bg-slate-800/50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-slate-200">
                      {{ cliente.nombre }}
                    </div>
                    <div class="text-sm text-slate-400">
                      Ref: {{ cliente.telefono }}
                    </div>
                    <div class="text-xs text-slate-500">
                      {{ cliente.trabajos.length }} trabajo{{ cliente.trabajos.length !== 1 ? 's' : '' }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-slate-200">
                      {{ cliente.trabajos.length }}
                    </div>
                    <div class="text-xs text-slate-400">
                      <span v-if="cliente.trabajosPendientes > 0" class="text-yellow-400">
                        {{ cliente.trabajosPendientes }} pendiente{{ cliente.trabajosPendientes !== 1 ? 's' : '' }}
                      </span>
                      <span v-if="cliente.trabajosEnProceso > 0" class="text-blue-400 ml-1">
                        {{ cliente.trabajosEnProceso }} en proceso
                      </span>
                      <span v-if="cliente.trabajosCompletados > 0" class="text-green-400 ml-1">
                        {{ cliente.trabajosCompletados }} completado{{ cliente.trabajosCompletados !== 1 ? 's' : '' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-200">
                    {{ (cliente.totalGeneral || 0).toFixed(2) }} Bs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                    {{ (cliente.totalACuenta || 0).toFixed(2) }} Bs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="(cliente.totalSaldo || 0) > 0 ? 'text-red-400 font-bold' : 'text-green-400 font-bold'">
                      {{ (cliente.totalSaldo || 0).toFixed(2) }} Bs
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-wrap gap-1">
                      <span v-if="cliente.trabajosPendientes > 0" class="px-2 py-1 text-xs bg-yellow-400/20 text-yellow-400 rounded-full">
                        {{ cliente.trabajosPendientes }} Pendiente{{ cliente.trabajosPendientes !== 1 ? 's' : '' }}
                      </span>
                      <span v-if="cliente.trabajosEnProceso > 0" class="px-2 py-1 text-xs bg-blue-400/20 text-blue-400 rounded-full">
                        {{ cliente.trabajosEnProceso }} En Proceso
                      </span>
                      <span v-if="cliente.trabajosCompletados > 0" class="px-2 py-1 text-xs bg-green-400/20 text-green-400 rounded-full">
                        {{ cliente.trabajosCompletados }} Completado{{ cliente.trabajosCompletados !== 1 ? 's' : '' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex flex-wrap gap-1">
                      <span v-if="cliente.pagosPendientes > 0" class="px-2 py-1 text-xs bg-yellow-400/20 text-yellow-400 rounded-full">
                        {{ cliente.pagosPendientes }} Pendiente{{ cliente.pagosPendientes !== 1 ? 's' : '' }}
                      </span>
                      <span v-if="cliente.pagosParciales > 0" class="px-2 py-1 text-xs bg-orange-400/20 text-orange-400 rounded-full">
                        {{ cliente.pagosParciales }} Parcial{{ cliente.pagosParciales !== 1 ? 'es' : '' }}
                      </span>
                      <span v-if="cliente.pagosCompletados > 0" class="px-2 py-1 text-xs bg-green-400/20 text-green-400 rounded-full">
                        {{ cliente.pagosCompletados }} Completado{{ cliente.pagosCompletados !== 1 ? 's' : '' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <button 
                        @click="verDetallesCliente(cliente)"
                        class="text-amber-400 hover:text-amber-300 p-2 rounded-lg hover:bg-amber-500/10"
                        title="Ver detalles del cliente"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                      </button>
                      <Link 
                        :href="route('registrar-trabajos.create', { cliente_id: cliente.id })"
                        class="text-green-400 hover:text-green-300 p-2 rounded-lg hover:bg-green-500/10"
                        title="Agregar trabajo para este cliente"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                      </Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Vista Individual (original) -->
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
              <thead class="bg-gradient-to-r from-slate-800/70 to-slate-900/70">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Cliente
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Servicio
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Cantidad
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Total
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    A Cuenta
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Saldo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Fecha Entrega
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Estado Trabajo
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Estado Pago
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-slate-900/50 divide-y divide-white/10">
                <tr v-for="trabajo in trabajosFiltrados" :key="trabajo.id" class="hover:bg-slate-800/50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-slate-200">
                      {{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido }}
                    </div>
                    <div class="text-sm text-slate-400">
                      Ref: {{ trabajo.cliente?.telefono }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-slate-200">
                      {{ trabajo.servicio?.nombreServicio }}
                    </div>
                    <div class="text-sm text-slate-400">
                      {{ trabajo.servicio?.precioReferencial }} Bs
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                    {{ trabajo.detalleTrabajo?.cantidad }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-200">
                    {{ trabajo.pagos?.[0]?.total || 0 }} Bs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                    {{ trabajo.pagos?.[0]?.aCuenta || 0 }} Bs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                    {{ trabajo.pagos?.[0]?.saldo || 0 }} Bs
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                    {{ formatDate(trabajo.fechaEntrega) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getEstadoTrabajoClass(trabajo.estado?.nombre)">
                      {{ trabajo.estado?.nombre }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getEstadoPagoClass(trabajo.pagos?.[0]?.estadoPago?.nombre)">
                      {{ trabajo.pagos?.[0]?.estadoPago?.nombre || 'Sin pago' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                      <Link 
                        v-if="trabajo.slug"
                        :href="route('registrar-trabajos.show', trabajo.slug)"
                        class="text-amber-400 hover:text-amber-300 p-2 rounded-lg hover:bg-amber-500/10"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      </Link>
                      <Link 
                        v-if="trabajo.slug"
                        :href="route('registrar-trabajos.edit', trabajo.slug)"
                        class="text-pink-400 hover:text-pink-300 p-2 rounded-lg hover:bg-pink-500/10"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                      </Link>
                      <button 
                        @click="confirmarEliminacion(trabajo)"
                        class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-if="trabajosFiltrados.length === 0" class="text-center py-12">
          <p class="text-gray-400 text-lg">No se encontraron trabajos registrados</p>
        </div>
      </main>
    </div>
  </AppShell>

  <div v-if="mostrarModalEliminacion" class="fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative p-8 border border-white/10 w-96 shadow-lg rounded-xl bg-slate-900/90 text-slate-200">
      <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-800/30 border border-red-500/50">
          <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium mt-4">Confirmar Eliminación</h3>
        <div class="mt-2 px-7 py-3">
          <p class="text-sm text-slate-400">
            ¿Estás seguro de que quieres eliminar el trabajo de 
            <span class="font-semibold text-white">{{ trabajoAEliminar?.cliente?.nombre }} {{ trabajoAEliminar?.cliente?.apellido }}</span>?
          </p>
          <p class="text-sm text-slate-400 mt-2">
            <span class="font-semibold text-white">{{ trabajoAEliminar?.servicio?.nombreServicio }}</span> - 
            Cantidad: <span class="font-semibold text-white">{{ trabajoAEliminar?.detalleTrabajo?.cantidad }}</span>
          </p>
          <p class="text-xs text-red-400 mt-2">
            ⚠️ Esta acción no se puede deshacer
          </p>
        </div>
        <div class="flex justify-center space-x-3 mt-4">
          <button 
            @click="cancelarEliminacion"
            class="px-4 py-2 bg-slate-700 text-slate-300 rounded-md hover:bg-slate-600 transition-colors duration-200"
          >
            Cancelar
          </button>
          <button 
            @click="eliminarTrabajo"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200"
          >
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

// Props
const props = defineProps({
  trabajos: Array,
  clientes: Array,
  estadosTrabajo: Array,
  estadosPago: Array,
})

// Acceder a los mensajes flash
const page = usePage()

// Filtros
const filtros = ref({
  cliente: '',
  estadoTrabajo: '',
  estadoPago: '',
})

// Vista consolidada (true) o individual (false)
const vistaConsolidada = ref(false)

// Trabajos filtrados
const trabajosFiltrados = computed(() => {
  let trabajos = props.trabajos

  if (filtros.value.cliente) {
    trabajos = trabajos.filter(t => t.idCliente == filtros.value.cliente)
  }

  if (filtros.value.estadoTrabajo) {
    trabajos = trabajos.filter(t => t.idEstado == filtros.value.estadoTrabajo)
  }

  if (filtros.value.estadoPago) {
    trabajos = trabajos.filter(t => 
      t.pagos?.[0]?.idEstadoPago == filtros.value.estadoPago
    )
  }

  return trabajos
})

// Consolidación de pagos por cliente
const consolidacionPorCliente = computed(() => {
  const consolidacion = {}
  
  trabajosFiltrados.value.forEach(trabajo => {
    const clienteId = trabajo.idCliente
    const clienteNombre = `${trabajo.cliente?.nombre || ''} ${trabajo.cliente?.apellido || ''}`.trim()
    const clienteTelefono = trabajo.cliente?.telefono || ''
    
    if (!consolidacion[clienteId]) {
      consolidacion[clienteId] = {
        id: clienteId,
        nombre: clienteNombre || `Cliente ${clienteId}`,
        telefono: clienteTelefono,
        trabajos: [],
        totalGeneral: 0,
        totalACuenta: 0,
        totalSaldo: 0,
        trabajosPendientes: 0,
        trabajosEnProceso: 0,
        trabajosCompletados: 0,
        pagosPendientes: 0,
        pagosParciales: 0,
        pagosCompletados: 0
      }
    }
    
    const pago = trabajo.pagos?.[0]
    const total = parseFloat(pago?.total) || 0
    const aCuenta = parseFloat(pago?.aCuenta) || 0
    const saldo = parseFloat(pago?.saldo) || 0
    
    consolidacion[clienteId].trabajos.push(trabajo)
    consolidacion[clienteId].totalGeneral += total
    consolidacion[clienteId].totalACuenta += aCuenta
    consolidacion[clienteId].totalSaldo += saldo
    
    // Contar estados de trabajo
    switch(trabajo.estado?.nombre) {
      case 'Pendiente':
        consolidacion[clienteId].trabajosPendientes++
        break
      case 'En Proceso':
        consolidacion[clienteId].trabajosEnProceso++
        break
      case 'Completado':
      case 'Entregado':
        consolidacion[clienteId].trabajosCompletados++
        break
    }
    
    // Contar estados de pago
    switch(pago?.estadoPago?.nombre) {
      case 'Pendiente':
        consolidacion[clienteId].pagosPendientes++
        break
      case 'Parcial':
        consolidacion[clienteId].pagosParciales++
        break
      case 'Completado':
        consolidacion[clienteId].pagosCompletados++
        break
    }
  })
  
  return Object.values(consolidacion)
})

// Métodos
const aplicarFiltros = () => {
  // Los filtros se aplican automáticamente con computed
}

const verDetallesCliente = (cliente) => {
  // Filtrar solo los trabajos de este cliente
  filtros.value.cliente = cliente.id
  // Cambiar a vista individual para ver los detalles
  vistaConsolidada.value = false
}

const formatDate = (date) => {
  if (!date) return 'No definida'
  return new Date(date).toLocaleDateString('es-ES')
}

const getEstadoTrabajoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-2 py-1 text-xs font-medium bg-yellow-400/20 text-yellow-400 rounded-full',
    'En Proceso': 'px-2 py-1 text-xs font-medium bg-blue-400/20 text-blue-400 rounded-full',
    'Completado': 'px-2 py-1 text-xs font-medium bg-green-400/20 text-green-400 rounded-full',
    'Entregado': 'px-2 py-1 text-xs font-medium bg-purple-400/20 text-purple-400 rounded-full',
    'Cancelado': 'px-2 py-1 text-xs font-medium bg-red-400/20 text-red-400 rounded-full',
  }
  return classes[estado] || 'px-2 py-1 text-xs font-medium bg-gray-400/20 text-gray-400 rounded-full'
}

const getEstadoPagoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-2 py-1 text-xs font-medium bg-yellow-400/20 text-yellow-400 rounded-full',
    'Parcial': 'px-2 py-1 text-xs font-medium bg-orange-400/20 text-orange-400 rounded-full',
    'Completado': 'px-2 py-1 text-xs font-medium bg-green-400/20 text-green-400 rounded-full',
    'Cancelado': 'px-2 py-1 text-xs font-medium bg-red-400/20 text-red-400 rounded-full',
  }
  return classes[estado] || 'px-2 py-1 text-xs font-medium bg-gray-400/20 text-gray-400 rounded-full'
}

// Métodos de eliminación
const trabajoAEliminar = ref(null)
const mostrarModalEliminacion = ref(false)

const confirmarEliminacion = (trabajo) => {
  trabajoAEliminar.value = trabajo
  mostrarModalEliminacion.value = true
}

const cancelarEliminacion = () => {
  trabajoAEliminar.value = null
  mostrarModalEliminacion.value = false
}

const eliminarTrabajo = () => {
  if (trabajoAEliminar.value) {
    const form = useForm({
      _method: 'DELETE',
    })
    
    form.post(route('registrar-trabajos.destroy', trabajoAEliminar.value.slug), {
      onSuccess: () => {
        mostrarModalEliminacion.value = false
        trabajoAEliminar.value = null
        // Inertia se encargará de actualizar la página automáticamente
      },
      onError: (errors) => {
        console.error('Error al eliminar:', errors)
        // Mostrar error más específico
        let errorMessage = 'Error al eliminar el trabajo. Por favor, inténtalo de nuevo.'
        
        if (errors.error) {
          errorMessage = errors.error
        } else if (errors.message) {
          errorMessage = errors.message
        } else if (typeof errors === 'string') {
          errorMessage = errors
        }
        
        alert(errorMessage)
      }
    })
  }
}
</script>