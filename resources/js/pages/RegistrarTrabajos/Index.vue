<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
    <!-- Header Section -->
    <div class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <div class="flex items-center">
            <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-purple-500 rounded-lg flex items-center justify-center mr-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-400 to-purple-500 bg-clip-text text-transparent">
                Trabajos Registrados
              </h1>
              <p class="text-gray-400 mt-1">Gestión de todos los trabajos y su estado</p>
            </div>
          </div>
                     <div class="mt-4 sm:mt-0">
             <button @click="crearNuevoTrabajo" class="bg-gradient-to-r from-orange-400 to-purple-500 text-white px-6 py-3 rounded-lg font-medium hover:from-orange-500 hover:to-purple-600 transition-all duration-200 flex items-center">
               <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
               </svg>
               + Nuevo Trabajo
             </button>
           </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 flex items-center">
        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-green-400 font-medium">{{ successMessage }}</span>
      </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-200 mb-4 lg:mb-0">Filtros de Búsqueda</h2>
          
          <!-- View Toggles -->
          <div class="flex space-x-2">
            <button 
              @click="vistaActual = 'individual'" 
              :class="[
                'px-4 py-2 rounded-lg font-medium flex items-center transition-all duration-200',
                vistaActual === 'individual' 
                  ? 'bg-orange-500 text-white' 
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Vista Individual
            </button>
            <button 
              @click="vistaActual = 'consolidada'" 
              :class="[
                'px-4 py-2 rounded-lg font-medium flex items-center transition-all duration-200',
                vistaActual === 'consolidada' 
                  ? 'bg-orange-500 text-white' 
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
              Vista Consolidada
            </button>
          </div>
        </div>

        <!-- Filter Dropdowns -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Cliente</label>
            <select 
              v-model="filtros.clienteId" 
              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            >
              <option value="">Todos los clientes</option>
              <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                {{ cliente.nombre }} {{ cliente.apellido }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Estado del Trabajo</label>
            <select 
              v-model="filtros.estadoId" 
              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            >
              <option value="">Todos los estados</option>
              <option v-for="estado in estadosTrabajo" :key="estado.id" :value="estado.id">
                {{ estado.nombre }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Estado del Pago</label>
            <select 
              v-model="filtros.estadoPagoId" 
              class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            >
              <option value="">Todos los estados</option>
              <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                {{ estado.nombre }}
              </option>
            </select>
          </div>
        </div>

        <!-- Filter Button -->
        <div class="mt-4 flex justify-end">
          <button 
            @click="aplicarFiltros" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Filtrar
          </button>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  CLIENTE
                </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                   SERVICIO
                 </th>
                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                   RESPONSABLE
                 </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  CANTIDAD
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  TOTAL
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  A CUENTA
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  SALDO
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  FECHA ENTREGA
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                  ESTADO TRABAJO
                </th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                   ESTADO PAGO
                 </th>
                 <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                   ACCIONES
                 </th>
              </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
              <tr v-for="trabajo in trabajosFiltrados" :key="trabajo.id" class="hover:bg-gray-700 transition-colors duration-150">
                                 <!-- CLIENTE -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div>
                     <div class="text-sm font-medium text-gray-200">
                       {{ trabajo.cliente.nombre }} {{ trabajo.cliente.apellido }}
                     </div>
                     <div class="text-sm text-gray-400">
                       Nr. Ref: 
                     </div>
                     <div v-if="trabajo.cliente.telefono" class="text-sm text-gray-400">
                       📞 {{ trabajo.cliente.telefono }}
                     </div>
                   </div>
                 </td>

                                 <!-- SERVICIO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div v-for="servicio in trabajo.servicios" :key="servicio.id" class="mb-1 last:mb-0">
                     <div class="text-sm font-medium text-gray-200">
                       {{ servicio.nombreServicio }}
                     </div>
                     <div class="text-sm text-gray-400">
                       <div class="mb-1">
                         <span class="text-gray-300">{{ servicio.cantidad || 1 }} × {{ formatPrecio(servicio.precio) }} Bs</span>
                       </div>
                       <div v-if="getDescuento(servicio) > 0" class="text-xs">
                         <span class="text-red-400 line-through">
                           Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                         </span>
                         <span class="text-orange-400 block">
                           -{{ formatPrecio(getDescuento(servicio)) }} Bs desc.
                         </span>
                         <span class="text-green-400 font-medium">
                           Final: {{ formatPrecio(getSubtotalFinal(servicio)) }} Bs
                         </span>
                       </div>
                       <div v-else class="text-green-400">
                         Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                       </div>
                     </div>
                   </div>
                 </td>
                                   <!-- RESPONSABLE -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="trabajo.responsable" class="flex items-center">
                      <!-- Círculo con inicial del responsable -->
                      <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-white font-bold text-sm">
                          {{ getInitial(trabajo.responsable.nombre) }}
                        </span>
                      </div>
                      <!-- Información del responsable -->
                      <div>
                        <div class="text-sm font-medium text-gray-200">
                          {{ trabajo.responsable.nombre }} {{ trabajo.responsable.apellido }}
                        </div>
                        <div class="text-sm text-gray-400">
                          {{ trabajo.responsable.rol }}
                        </div>
                      </div>
                    </div>
                    <div v-else class="text-sm text-gray-400 italic">
                      No asignado
                    </div>
                  </td>

                <!-- CANTIDAD -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-for="servicio in trabajo.servicios" :key="servicio.id" class="mb-1 last:mb-0">
                    <span class="text-sm text-gray-200">{{ servicio.cantidad || 1 }}</span>
                  </div>
                </td>

                                 <!-- TOTAL -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div class="text-sm font-bold text-gray-200">
                     {{ getTotalTrabajo(trabajo).toFixed(2) }} Bs
                   </div>
                 </td>

                 <!-- A CUENTA -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div class="text-sm text-gray-200">
                     {{ (Number(trabajo.pagado) || 0).toFixed(2) }} Bs
                   </div>
                 </td>

                 <!-- SALDO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div class="text-sm font-medium text-gray-200">
                     {{ (getTotalTrabajo(trabajo) - (Number(trabajo.pagado) || 0)).toFixed(2) }} Bs
                   </div>
                 </td>

                <!-- FECHA ENTREGA -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-200">
                    {{ trabajo.fechaEntrega ? new Date(trabajo.fechaEntrega).toLocaleDateString('es-ES') : 'No especificada' }}
                  </div>
                </td>

                <!-- ESTADO TRABAJO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="[
                      'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                      trabajo.estado.nombre === 'Completado' ? 'bg-green-100 text-green-800' :
                      trabajo.estado.nombre === 'En Proceso' ? 'bg-blue-100 text-blue-800' :
                      trabajo.estado.nombre === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-gray-100 text-gray-800'
                    ]"
                  >
                    {{ trabajo.estado.nombre }}
                  </span>
                </td>

                                 <!-- ESTADO PAGO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <span 
                     :class="[
                       'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                       trabajo.estadoPago.nombre === 'Pagado' ? 'bg-green-100 text-green-800' :
                       trabajo.estadoPago.nombre === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                       trabajo.estadoPago.nombre === 'Parcial' ? 'bg-orange-100 text-orange-800' :
                       'bg-gray-100 text-gray-800'
                     ]"
                   >
                     {{ trabajo.estadoPago.nombre }}
                   </span>
                 </td>

                                   <!-- ACCIONES -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div class="flex space-x-2">
                     <!-- Botón Ver Detalle -->
                     <button 
                       @click="verDetalleTrabajo(trabajo)"
                       class="text-blue-400 hover:text-blue-300 transition-colors duration-150"
                       title="Ver detalle"
                     >
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                       </svg>
                     </button>
                     
                     <!-- Botón Editar -->
                     <button 
                       @click="editarTrabajo(trabajo)"
                       class="text-green-400 hover:text-green-300 transition-colors duration-150"
                       title="Editar trabajo"
                     >
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                       </svg>
                     </button>
                     
                     <!-- Botón Agregar Pago/Cuota -->
                     <button 
                       @click="agregarCuota(trabajo)"
                       class="text-yellow-400 hover:text-yellow-300 transition-colors duration-150"
                       title="Agregar pago/cuota"
                       v-if="trabajo.saldo > 0"
                     >
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                       </svg>
                     </button>
                     
                     <!-- Botón Eliminar -->
                     <button 
                       @click="eliminarTrabajo(trabajo)"
                       class="text-red-400 hover:text-red-300 transition-colors duration-150"
                       title="Eliminar trabajo"
                     >
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                       </svg>
                     </button>
                   </div>
                 </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-if="trabajosFiltrados.length === 0" class="text-center py-12">
          <div class="mx-auto w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-200 mb-2">No se encontraron trabajos</h3>
          <p class="text-gray-400 mb-4">
            {{ filtros.clienteId || filtros.estadoId || filtros.estadoPagoId ? 
               'Intenta ajustar los filtros de búsqueda.' : 
               'Comienza creando tu primer trabajo.' }}
          </p>
          <button class="bg-gradient-to-r from-orange-400 to-purple-500 text-white px-6 py-2 rounded-lg font-medium hover:from-orange-500 hover:to-purple-600 transition-all duration-200">
            Crear Trabajo
          </button>
        </div>
      </div>
    </div>
    </div>

    <!-- Modal Agregar Cuota -->
    <ModalAgregarCuota 
      :mostrar="mostrarModalCuota"
      :trabajo="trabajoSeleccionado"
      :estados-pago="estadosPago"
      @cerrar="cerrarModalCuota"
      @cuota-procesada="onCuotaProcesada"
    />

  </AppShell>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import ModalAgregarCuota from './components/ModalAgregarCuota.vue'

// Obtener la página actual
const page = usePage()

// Computed para el mensaje de éxito
const successMessage = computed(() => {
  return (page.props as any).flash?.success || null
})

// Tipos de datos
interface Cliente {
  id: number
  nombre: string
  apellido: string
  email?: string
  telefono?: string
  direccion?: string
}

interface Servicio {
  id: number
  nombreServicio: string
  precio: number
  descuento?: number
  descripcion?: string
  cantidad?: number
  tamano?: string
  color?: string
  modelo?: string
  tipoDocumento?: string
  tipoEvento?: string
}

interface EstadoTrabajo {
  id: number
  nombre: string
}

interface EstadoPago {
  id: number
  nombre: string
}

interface Responsable {
  id: number
  nombre: string
  apellido: string
  rol: string
}

interface Trabajo {
  id: number
  idCliente: number
  fechaRegistro: string
  fechaEntrega?: string
  idEstado: number
  idEstadoPago: number
  slug: string
  cliente: Cliente
  estado: EstadoTrabajo
  estadoPago: EstadoPago
  responsable?: Responsable
  servicios: Servicio[]
  totalTrabajo: number
  saldo: number
  pagado: number
}

// Props del componente
interface Props {
  trabajos: Trabajo[]
  clientes: Cliente[]
  estadosTrabajo: EstadoTrabajo[]
  estadosPago: EstadoPago[]
}

const props = defineProps<Props>()

// Estado reactivo
const vistaActual = ref<'consolidada' | 'individual'>('individual')
const filtros = ref({
  clienteId: '',
  estadoId: '',
  estadoPagoId: ''
})

// Estado del modal de agregar cuota
const mostrarModalCuota = ref(false)
const trabajoSeleccionado = ref<any>(null)

// Trabajos filtrados
const trabajosFiltrados = computed(() => {
  let filtrados = props.trabajos

  if (filtros.value.clienteId) {
    filtrados = filtrados.filter(t => t.idCliente === parseInt(filtros.value.clienteId))
  }

  if (filtros.value.estadoId) {
    filtrados = filtrados.filter(t => t.idEstado === parseInt(filtros.value.estadoId))
  }

  if (filtros.value.estadoPagoId) {
    filtrados = filtrados.filter(t => t.idEstadoPago === parseInt(filtros.value.estadoPagoId))
  }

  return filtrados
})

// Métodos
const aplicarFiltros = () => {
  // Los filtros se aplican automáticamente con computed
  console.log('Filtros aplicados:', filtros.value)
}

const verDetalleTrabajo = (trabajo: Trabajo) => {
  // Navegar al detalle del trabajo usando slug
  window.location.href = `/registrar-trabajos/${trabajo.slug}`
}

const crearNuevoTrabajo = () => {
  // Navegar al formulario de creación
  window.location.href = '/registrar-trabajos/create'
}

const editarTrabajo = (trabajo: Trabajo) => {
  // Navegar al formulario de edición usando el slug
  window.location.href = `/registrar-trabajos/${trabajo.slug}/edit`
}

const eliminarTrabajo = (trabajo: Trabajo) => {
  // Confirmar antes de eliminar
  if (confirm(`¿Estás seguro de que quieres eliminar el trabajo de ${trabajo.cliente.nombre}?`)) {
    // Aquí puedes implementar la lógica de eliminación
    console.log('Eliminando trabajo:', trabajo.id)
    // Por ahora solo muestra un mensaje
    alert('Función de eliminación en desarrollo')
  }
}

const agregarCuota = (trabajo: Trabajo) => {
  console.log('agregarCuota llamado con:', trabajo)
  // Abrir modal para agregar cuota
  trabajoSeleccionado.value = trabajo
  mostrarModalCuota.value = true
  console.log('Modal debería estar abierto:', mostrarModalCuota.value)
}

const cerrarModalCuota = () => {
  mostrarModalCuota.value = false
  trabajoSeleccionado.value = null
}

const onCuotaProcesada = (trabajo: any) => {
  console.log('Cuota procesada para:', trabajo)
  // Recargar la página para actualizar los datos
  window.location.reload()
}

// Función helper para formatear precios
const formatPrecio = (precio: any): string => {
  const numPrecio = Number(precio) || 0
  return numPrecio.toFixed(2)
}

// Función helper para obtener solo la inicial del nombre
const getInitial = (nombre: string): string => {
  return nombre ? nombre.charAt(0).toUpperCase() : ''
}

// Función helper para obtener el descuento de un servicio
const getDescuento = (servicio: any): number => {
  return Number(servicio.descuento) || 0
}

// Función helper para obtener el subtotal bruto de un servicio
const getSubtotalBruto = (servicio: any): number => {
  const precioOriginal = Number(servicio.precio) || 0
  const cantidad = Number(servicio.cantidad) || 0
  return precioOriginal * cantidad
}

// Función helper para obtener el subtotal final de un servicio (después del descuento)
const getSubtotalFinal = (servicio: any): number => {
  const subtotalBruto = getSubtotalBruto(servicio)
  const descuento = getDescuento(servicio)
  return subtotalBruto - descuento
}

// Función helper para obtener el precio final de un servicio (mantenida por compatibilidad)
const getPrecioFinal = (servicio: any): number => {
  const precioOriginal = Number(servicio.precio) || 0
  const descuento = getDescuento(servicio)
  return precioOriginal - descuento
}

// Función helper para calcular el total correcto de un trabajo
const getTotalTrabajo = (trabajo: any): number => {
  if (!trabajo.servicios || trabajo.servicios.length === 0) return 0
  
  return trabajo.servicios.reduce((total: number, servicio: any) => {
    return total + getSubtotalFinal(servicio)
  }, 0)
}

// Lifecycle
onMounted(() => {
  console.log('🚀 Index.vue montado, trabajos recibidos:', props.trabajos)
})
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>