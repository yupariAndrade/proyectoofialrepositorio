<template>
  <AppShell>
    <AppSidebar>
      <!-- Contenido principal -->
      <div class="min-h-screen overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-red-500 via-white to-gray-300 bg-clip-text text-transparent mb-2">📸 Servicios</h1>
              <p class="text-slate-300">GESTIONAR SERVICIOS</p>
            </div>
            <div class="flex items-center space-x-4">
              <Link href="/servicios/create" class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-red-700 hover:via-red-800 hover:to-red-900 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Servicio
              </Link>
              <button @click="generateReport" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
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
                  <input
                    v-model="searchTerm"
                    type="text"
                    id="search"
                    placeholder="Buscar por nombre, precio o descripción..."
                    class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm"
                  >
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                  </div>
                </div>
              </div>
              
              <div class="flex items-center space-x-4">
                <div>
                  <label for="filter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por estado</label>
                  <select
                    v-model="filterStatus"
                    id="filter"
                    class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm"
                  >
                    <option value="">Todos</option>
                    <option value="1">Disponibles</option>
                    <option value="0">No disponibles</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- Lista de servicios -->
          <div v-if="filteredServicios.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <div 
              v-for="servicio in filteredServicios" 
              :key="servicio.id"
              class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 overflow-hidden hover:shadow-amber-500/20 transition-all duration-300 transform hover:-translate-y-1 hover:scale-102"
            >
              <!-- Imagen del servicio -->
              <div class="relative">
                <div class="w-full h-40 bg-slate-700/50 overflow-hidden">
                  <img
                    v-if="servicio.imagenReferencia"
                    :src="getImageUrl(servicio.imagenReferencia)"
                    :alt="servicio.nombreServicio"
                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                  >
                  <div v-else class="w-full h-full bg-gradient-to-br from-amber-500/20 via-pink-500/20 to-purple-600/20 flex items-center justify-center backdrop-blur-sm">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center shadow-lg">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
                <!-- Badge de estado -->
                <div class="absolute top-80 right-2">
                  <span :class="servicio.estado == 1 ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'" class="px-2 py-1 rounded-full text-xs font-semibold border backdrop-blur-sm">
                    {{ servicio.estado == 1 ? 'Disponible' : 'No disponible' }}
                  </span>
                </div>
              </div>
              
              <!-- Contenido del servicio -->
              <div class="p-4">
                <h3 class="text-lg font-bold text-white mb-2 line-clamp-2">{{ servicio.nombreServicio }}</h3>
                <p class="text-slate-300 mb-3 text-sm leading-relaxed line-clamp-2">{{ servicio.descripcion || 'Sin descripción' }}</p>
                
                <!-- Precio -->
                <div class="flex items-center justify-between mb-3">
                  <span class="text-xl font-bold text-amber-400">Bs. {{ Number(servicio.precioReferencial).toFixed(2) }}</span>
                </div>

                
                
                <!-- Botones de acción -->
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-1">
                    <Link :href="`/servicios/${servicio.id}`" class="text-amber-400 hover:text-amber-300 transition-colors p-1.5 hover:bg-amber-500/20 rounded-lg" title="Ver detalles">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </Link>
                    <Link :href="`/servicios/${servicio.id}/edit`" class="text-pink-400 hover:text-pink-300 transition-colors p-1.5 hover:bg-pink-500/20 rounded-lg" title="Editar">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </Link>
                   ><!-- <button @click="deleteService(servicio.id)" class="text-red-400 hover:text-red-300 transition-colors p-1.5 hover:bg-red-500/20 rounded-lg" title="Eliminar">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>-->
                  </div>
                  
                  <!-- Fecha de registro 
                  <div class="text-xs text-slate-400">
                    {{ formatDate(servicio.created_at) }}
                  </div>-->
                </div>
              </div>
            </div>
          </div>

          <!-- Estado vacío -->
          <div v-else class="text-center py-16">
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-12">
              <div class="w-24 h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">No hay servicios registrados</h3>
              <p class="text-slate-300 mb-8">Comienza agregando tu primer servicio fotográfico al catálogo.</p>
              <Link href="/servicios/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Primer Servicio
              </Link>
            </div>
          </div>
        </main>
      </div>
    </AppSidebar>
  </AppShell>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props que vienen desde el controlador
const props = defineProps<{
  servicios: Array<{
    id: number
    nombreServicio: string
    descripcion?: string | null
    precioReferencial: number
    estado: number | boolean
    imagenReferencia?: string | null
    created_at: string
  }>
}>()

// Estados reactivos
const searchTerm = ref('')
const filterStatus = ref('')

// Servicios filtrados
const filteredServicios = computed(() => {
  let filtered = props.servicios || []

  // Filtrar por término de búsqueda
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase()
    filtered = filtered.filter(servicio => 
      servicio.nombreServicio.toLowerCase().includes(term) ||
      (servicio.descripcion && servicio.descripcion.toLowerCase().includes(term)) ||
      servicio.precioReferencial.toString().includes(term)
    )
  }

  // Filtrar por estado
  if (filterStatus.value !== '') {
    filtered = filtered.filter(servicio => 
      servicio.estado.toString() === filterStatus.value
    )
  }

  return filtered
})

// Funciones
const generateReport = () => {
  // Generar reporte PDF de servicios
  window.open('/reportes/servicios/pdf', '_blank');
}

const deleteService = (id: number) => {
  if (confirm('¿Estás seguro de que deseas eliminar este servicio?')) {
    // Crear un formulario temporal para enviar la petición DELETE
    const form = document.createElement('form')
    form.method = 'POST'
    form.action = `/servicios/${id}`
    
    const methodInput = document.createElement('input')
    methodInput.type = 'hidden'
    methodInput.name = '_method'
    methodInput.value = 'DELETE'
    
    const tokenInput = document.createElement('input')
    tokenInput.type = 'hidden'
    tokenInput.name = '_token'
    tokenInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    
    form.appendChild(methodInput)
    form.appendChild(tokenInput)
    document.body.appendChild(form)
    form.submit()
  }
}

const formatDate = (dateString: string) => {
  if (!dateString) return 'N/A'
  return new Date(dateString).toLocaleDateString('es-ES')
}

// Normaliza rutas de imagen con barras invertidas (Windows) a barras válidas en URL
const getImageUrl = (path: string | null | undefined) => {
  if (!path) return ''
  const normalized = path.replace(/\\\\/g, '/').replace(/\\/g, '/')
  return `/storage/${normalized}`
}

onMounted(() => {
  console.log('Servicios Index cargado correctamente')
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Mejoras de responsividad */
@media (max-width: 640px) {
  .grid {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
}

@media (min-width: 641px) and (max-width: 1024px) {
  .grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (min-width: 1025px) {
  .grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (min-width: 1280px) {
  .grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}
</style>