<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              📊 Reporte de Servicios
            </h2>
            <p class="text-slate-300">Generar reporte PDF de todos los servicios registrados</p>
          </div>
          <div class="flex space-x-4">
            <button 
              @click="generarPDF"
              :disabled="isGenerando"
              class="bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded flex items-center space-x-2"
            >
              <svg v-if="isGenerando" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>{{ isGenerando ? 'Generando...' : 'Generar PDF' }}</span>
            </button>
            <Link 
              :href="route('servicios')"
              class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded"
            >
              Volver
            </Link>
          </div>
        </div>
      </header>

      <!-- Main Content -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Estadísticas -->
          <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-amber-400 mb-2">{{ totalServicios }}</div>
                <div class="text-slate-400 text-sm">Total Servicios</div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-green-400 mb-2">{{ serviciosActivos }}</div>
                <div class="text-slate-400 text-sm">Servicios Activos</div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-red-400 mb-2">{{ serviciosInactivos }}</div>
                <div class="text-slate-400 text-sm">Servicios Inactivos</div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-blue-400 mb-2">{{ formatCurrency(precioPromedio) }}</div>
                <div class="text-slate-400 text-sm">Precio Promedio</div>
              </div>
            </div>
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="text-center">
                <div class="text-3xl font-bold text-purple-400 mb-2">{{ formatCurrency(precioTotal) }}</div>
                <div class="text-slate-400 text-sm">Valor Total</div>
              </div>
            </div>
          </div>

          <!-- Tabla de Servicios -->
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10">
              <h3 class="text-xl font-semibold text-white">Lista de Servicios</h3>
              <p class="text-slate-400 text-sm">Generado el {{ fechaGeneracion }}</p>
            </div>
            
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-slate-700/50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Servicio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fecha</th>
                  </tr>
                </thead>
                <tbody class="bg-slate-900/50 divide-y divide-white/10">
                  <tr v-for="(servicio, index) in servicios" :key="servicio.id" class="hover:bg-slate-800/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-200">
                      {{ index + 1 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-slate-200">
                        {{ servicio.nombreServicio }}
                      </div>
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm text-slate-300 max-w-xs truncate">
                        {{ servicio.descripcion || 'Sin descripción' }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-400 font-semibold">
                      {{ formatCurrency(servicio.precioReferencial) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getEstadoClass(servicio.estado)">
                        {{ servicio.estado ? 'Activo' : 'Inactivo' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                      {{ servicio.usuario ? `${servicio.usuario.nombre} ${servicio.usuario.apellido}` : 'No asignado' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                      {{ formatDate(servicio.created_at) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mensaje si no hay servicios -->
          <div v-if="servicios.length === 0" class="text-center py-12">
            <p class="text-gray-400 text-lg">No hay servicios registrados para generar el reporte</p>
          </div>

        </div>
      </main>
    </div>
  </AppShell>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  servicios: Array,
})

// Estado reactivo
const isGenerando = ref(false)

// Computed properties
const serviciosActivos = computed(() => {
  return props.servicios.filter(servicio => servicio.estado).length
})

const serviciosInactivos = computed(() => {
  return props.servicios.filter(servicio => !servicio.estado).length
})

const totalServicios = computed(() => {
  return props.servicios.length
})

const precioPromedio = computed(() => {
  if (props.servicios.length === 0) return 0
  const total = props.servicios.reduce((sum, servicio) => sum + parseFloat(servicio.precioReferencial || 0), 0)
  return total / props.servicios.length
})

const precioTotal = computed(() => {
  return props.servicios.reduce((sum, servicio) => sum + parseFloat(servicio.precioReferencial || 0), 0)
})

const fechaGeneracion = computed(() => {
  return new Date().toLocaleDateString('es-ES')
})

// Métodos
const formatDate = (date) => {
  if (!date) return 'No definida'
  return new Date(date).toLocaleDateString('es-ES')
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('es-BO', { 
    style: 'currency', 
    currency: 'BOB' 
  }).format(amount || 0)
}

const getEstadoClass = (estado) => {
  return estado 
    ? 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full'
    : 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full'
}

const generarPDF = () => {
  isGenerando.value = true
  
  // Crear un enlace temporal para descargar el PDF
  const link = document.createElement('a')
  link.href = window.route('reportes.servicios.pdf')
  link.download = 'reporte-servicios.pdf'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  
  // Mostrar mensaje de éxito
  setTimeout(() => {
    isGenerando.value = false
    alert('¡Reporte de servicios generado exitosamente!')
  }, 1000)
}
</script>









