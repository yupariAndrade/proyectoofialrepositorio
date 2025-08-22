<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              👁️ Detalles del Trabajo
            </h2>
            <p class="text-slate-300">Información completa del trabajo</p>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Header con título y botones de acción -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Detalles del Trabajo</h2>
            <div class="flex justify-end space-x-4 pt-6">
              <Link 
                :href="route('registrar-trabajos.edit', trabajo.slug)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow"
              >
                Editar Trabajo
              </Link>
              <button 
                @click="confirmarEliminacion"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow"
              >
                Eliminar Trabajo
              </button>
              <Link 
                :href="route('registrar-trabajos')"
                class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded shadow"
              >
                Volver a la Lista
              </Link>
            </div>
          </div>

          <!-- Información del Cliente -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Información del Cliente</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Nombre Completo</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Número de Referencia</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.cliente?.telefono }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Servicio -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Servicio</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Servicio</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.servicio?.nombreServicio }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Precio Unitario</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.servicio?.precioReferencial }} Bs
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Detalles del Trabajo -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Detalles del Trabajo</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Tamaño</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.tamano || 'No especificado' }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Color</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.color || 'No especificado' }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Modelo</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.modelo || 'No especificado' }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Cantidad</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.detalleTrabajo?.cantidad }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Documento</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.tipoDocumento || 'No especificado' }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Evento</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.tipoEvento || 'No especificado' }}
                  </p>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-400 mb-1">Descripción</label>
                  <p class="text-lg text-white">
                    {{ trabajo.detalleTrabajo?.descripcion || 'No especificado' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Trabajo -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Información del Trabajo</h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Fecha de Registro</label>
                  <p class="text-lg font-medium text-white">
                    {{ formatDate(trabajo.fechaRegistro) }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Fecha de Entrega</label>
                  <p class="text-lg font-medium text-white">
                    {{ formatDate(trabajo.fechaEntrega) }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Estado del Trabajo</label>
                  <span :class="getEstadoTrabajoClass(trabajo.estado?.nombre)">
                    {{ trabajo.estado?.nombre }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Pago -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Información del Pago</h3>
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Total</label>
                  <p class="text-2xl font-bold text-white">
                    {{ trabajo.pagos?.[0]?.total || 0 }} Bs
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">A Cuenta</label>
                  <p class="text-2xl font-bold text-green-400">
                    {{ trabajo.pagos?.[0]?.aCuenta || 0 }} Bs
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Saldo</label>
                  <p class="text-2xl font-bold" :class="getSaldoClass(trabajo.pagos?.[0]?.saldo)">
                    {{ trabajo.pagos?.[0]?.saldo || 0 }} Bs
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Estado del Pago</label>
                  <span :class="getEstadoPagoClass(trabajo.pagos?.[0]?.estadoPago?.nombre)">
                    {{ trabajo.pagos?.[0]?.estadoPago?.nombre || 'Sin pago' }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Historial de Pagos -->
          <div v-if="trabajo.pagos && trabajo.pagos.length > 0" class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white">Historial de Pagos</h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                  <thead class="bg-gray-800">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Fecha
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Total
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        A Cuenta
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Saldo
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Estado
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <tr v-for="pago in trabajo.pagos" :key="pago.idPago" class="hover:bg-gray-800">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ formatDate(pago.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                        {{ pago.total }} Bs
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ pago.aCuenta }} Bs
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ pago.saldo }} Bs
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="getEstadoPagoClass(pago.estadoPago?.nombre)">
                          {{ pago.estadoPago?.nombre }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Otros Trabajos del Cliente -->
          <div class="bg-gray-900 bg-opacity-70 backdrop-blur-sm rounded-lg shadow overflow-hidden">
            <div class="px-4 py-5">
              <h3 class="text-lg leading-6 font-medium text-blue-400">
                📋 Otros Trabajos del Cliente: {{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido }}
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-blue-300">
                Historial completo de servicios solicitados
              </p>
            </div>
            <div class="border-t border-gray-700">
              <dl>
                <div class="bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-blue-300">
                    Total de Trabajos
                  </dt>
                  <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                    {{ otrosTrabajosCliente?.length || 0 }} trabajos registrados
                  </dd>
                </div>
                <div class="bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                  <dt class="text-sm font-medium text-blue-300">
                    Servicios Solicitados
                  </dt>
                  <dd class="mt-1 text-sm text-white sm:mt-0 sm:col-span-2">
                    <div class="space-y-2">
                      <div v-for="trabajoCliente in otrosTrabajosCliente" :key="trabajoCliente.id" 
                           class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                        <div class="flex-1">
                          <span class="font-medium text-white">
                            {{ trabajoCliente.servicio?.nombreServicio }}
                          </span>
                          <span class="text-sm text-gray-400 ml-2">
                            ({{ trabajoCliente.detalleTrabajo?.cantidad }} unidades)
                          </span>
                        </div>
                        <div class="flex items-center space-x-2">
                          <span class="text-sm text-gray-400">
                            {{ trabajoCliente.fechaRegistro }}
                          </span>
                          <Link 
                            :href="route('registrar-trabajos.show', trabajoCliente.slug)"
                            class="text-indigo-400 hover:text-indigo-200 text-sm font-medium"
                          >
                            Ver
                          </Link>
                        </div>
                      </div>
                    </div>
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div v-if="mostrarModalEliminacion" class="fixed inset-0 bg-black bg-opacity-70 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-gray-900 text-gray-200">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-800">
            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-white mt-4">Confirmar Eliminación</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-400">
              ¿Estás seguro de que quieres eliminar este trabajo?
            </p>
            <p class="text-sm text-gray-300 mt-2">
              <span class="font-semibold text-white">{{ trabajo?.cliente?.nombre }} {{ trabajo?.cliente?.apellido }}</span> - 
              <span class="font-semibold text-white">{{ trabajo?.servicio?.nombreServicio }}</span>
            </p>
            <p class="text-xs text-red-400 mt-2">
              ⚠️ Esta acción no se puede deshacer
            </p>
          </div>
          <div class="flex justify-center space-x-3 mt-4">
            <button 
              @click="cancelarEliminacion"
              class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Cancelar
            </button>
            <button 
              @click="eliminarTrabajo"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppShell>
</template>


<script setup>
import { ref, computed } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  trabajo: Object,
  otrosTrabajosCliente: Array,
})

// Acceder a los mensajes flash
const page = usePage()

// Estado para eliminación
const mostrarModalEliminacion = ref(false)

// Computed properties
const trabajo = computed(() => props.trabajo)
const otrosTrabajosCliente = computed(() => props.otrosTrabajosCliente)

// Métodos
const formatDate = (date) => {
  if (!date) return 'No definida'
  return new Date(date).toLocaleDateString('es-ES')
}

const getEstadoTrabajoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full',
    'En Proceso': 'px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full',
    'Completado': 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full',
    'Entregado': 'px-3 py-1 text-sm font-medium bg-purple-100 text-purple-800 rounded-full',
    'Cancelado': 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full',
  }
  return classes[estado] || 'px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full'
}

const getEstadoPagoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full',
    'Parcial': 'px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-full',
    'Completado': 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full',
    'Cancelado': 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full',
  }
  return classes[estado] || 'px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full'
}

const getSaldoClass = (saldo) => {
  if (saldo === 0) return 'text-green-600'
  if (saldo > 0) return 'text-red-600'
  return 'text-gray-900'
}

// Métodos de eliminación
const confirmarEliminacion = () => {
  mostrarModalEliminacion.value = true
}

const cancelarEliminacion = () => {
  mostrarModalEliminacion.value = false
}

const eliminarTrabajo = () => {
  if (trabajo.value) {
    const form = useForm({
      _method: 'DELETE',
    })

    form.post(route('registrar-trabajos.destroy', trabajo.value.slug), {
      onSuccess: () => {
        mostrarModalEliminacion.value = false
        // Usar Inertia para redirigir en lugar de window.location.href
        router.visit(route('registrar-trabajos'))
      },
      onError: (errors) => {
        console.error('Error al eliminar el trabajo:', errors)
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
      },
    })
  }
}
</script>
