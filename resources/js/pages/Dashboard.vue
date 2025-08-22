<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header Principal Centrado -->
    <div class="relative overflow-hidden">
      <!-- Carrusel de Fondo con Efecto Cristal -->
      <div class="absolute inset-0 z-0">
        <ServiceCarousel v-if="servicios && servicios.length" :servicios="servicios" class="h-96" />
      </div>
      
      <!-- Overlay con Efecto Cristal -->
      <div class="absolute inset-0 z-10 bg-black/20 backdrop-blur-sm"></div>
      
      <!-- Header Centrado con Efecto Cristal -->
      <div class="relative z-20 flex items-center justify-center h-96">
        <div class="text-center p-8 rounded-3xl bg-white/10 backdrop-blur-md shadow-2xl">
          <div class="flex items-center justify-center mb-4">
            <div class="w-6 h-6 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full mr-3 animate-pulse"></div>
            <h1 class="text-4xl md:text-6xl font-bold text-white">
              Bienvenidos a la App
            </h1>
          </div>
          <h2 class="text-2xl md:text-4xl font-bold bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-500 bg-clip-text text-transparent mb-4">
            Foto Estudio EU
          </h2>
          <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">
            Explora y gestiona servicios, usuarios y roles del estudio
          </p>
        </div>
      </div>
    </div>

    <!-- Contenido Principal -->
    <div class="relative z-30 bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
      <!-- Catálogo de Servicios en Scroll Horizontal -->
      <div class="py-12 px-6">
        <div class="max-w-7xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Catálogo de Servicios
          </h3>
          
                      <!-- Scroll Horizontal de Servicios -->
            <div class="relative">
              <div class="flex space-x-4 overflow-x-auto pb-6 scrollbar-hide">
                              <div 
                  v-for="servicio in servicios" 
                  :key="servicio.id"
                  class="flex-shrink-0 w-72 bg-white/10 backdrop-blur-md rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                <div class="p-6">
                  <!-- Imagen del Servicio -->
                  <div class="w-full h-48 rounded-xl overflow-hidden mb-4">
                    <img 
                      :src="getImageUrl(servicio.imagenReferencia)" 
                      :alt="servicio.nombreServicio"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  
                  <!-- Información del Servicio -->
                  <div class="text-center">
                    <h4 class="text-xl font-bold text-white mb-2">
                      {{ servicio.nombreServicio }}
                    </h4>
                    
                    <!-- Estado del Servicio -->
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-3"
                         :class="servicio.estado ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'">
                      <div class="w-2 h-2 rounded-full mr-2"
                           :class="servicio.estado ? 'bg-green-400' : 'bg-red-400'"></div>
                      {{ servicio.estado ? 'Activo' : 'Inactivo' }}
                    </div>
                    
                    <!-- Precio -->
                    <div class="text-2xl font-bold bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                      Bs. {{ Number(servicio.precioReferencial).toFixed(2) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Indicadores de Scroll -->
            <div class="flex justify-center mt-6 space-x-2">
              <div class="w-3 h-3 rounded-full bg-white/30"></div>
              <div class="w-3 h-3 rounded-full bg-white/60"></div>
              <div class="w-3 h-3 rounded-full bg-white/30"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Estadísticas Principales -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Estadísticas del Sistema
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Servicios -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Total Servicios</h4>
              <p class="text-3xl font-bold text-blue-400">{{ stats.servicios.total }}</p>
              <div class="flex justify-center space-x-2 mt-2">
                <span class="text-green-400 text-sm">{{ stats.servicios.activos }} activos</span>
                <span class="text-red-400 text-sm">{{ stats.servicios.inactivos }} inactivos</span>
              </div>
            </div>

            <!-- Total Clientes -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Total Clientes</h4>
              <p class="text-3xl font-bold text-green-400">{{ stats.clientes.total }}</p>
            </div>

            <!-- Total Trabajos -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Total Trabajos</h4>
              <p class="text-3xl font-bold text-orange-400">{{ stats.trabajos.total }}</p>
            </div>

            <!-- Ingresos del Día -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Ingresos Hoy</h4>
              <p class="text-3xl font-bold text-yellow-400">{{ formatCurrency(stats.ingresos.hoy) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Estados de Trabajos -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Estados de Trabajos
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center">
              <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Pendientes</h4>
              <p class="text-3xl font-bold text-yellow-400">{{ stats.trabajos.pendientes }}</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center">
              <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">En Proceso</h4>
              <p class="text-3xl font-bold text-blue-400">{{ stats.trabajos.enProceso }}</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center">
              <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Completados</h4>
              <p class="text-3xl font-bold text-green-400">{{ stats.trabajos.completados }}</p>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center">
              <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Cancelados</h4>
              <p class="text-3xl font-bold text-red-400">{{ stats.trabajos.cancelados }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Trabajos Recientes -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Trabajos Recientes
          </h3>
          
          <div class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-white/10">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Servicio</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Fecha</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                  <tr v-for="trabajo in trabajosRecientes" :key="trabajo.id" class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-white">{{ trabajo.cliente?.nombre || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-white/90">{{ trabajo.servicio?.nombreServicio || 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            :class="getEstadoClass(trabajo.estado?.nombre || '')">
                        {{ trabajo.estado?.nombre || 'N/A' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white/70">
                      {{ formatDate(trabajo.fechaRegistro) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Navegación Rápida -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Navegación Rápida
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                         <a href="/registrar-trabajos" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-purple-300 transition-colors">Registrar Trabajo</h4>
              </div>
            </a>

                         <a href="/servicios" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-blue-300 transition-colors">Gestionar Servicios</h4>
              </div>
            </a>

                         <a href="/clientes" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-green-300 transition-colors">Gestionar Clientes</h4>
              </div>
            </a>

                         <a href="/usuarios" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-orange-300 transition-colors">Gestionar Usuarios</h4>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import ServiceCarousel from '@/components/ServiceCarousel.vue'

interface Props {
  stats: {
    servicios: {
      total: number
      activos: number
      inactivos: number
    }
    clientes: {
      total: number
    }
    trabajos: {
      pendientes: number
      enProceso: number
      completados: number
      cancelados: number
      total: number
    }
    ingresos: {
      hoy: number
      mes: number
      total: number
    }
    usuarios: {
      total: number
      activos: number
    }
  }
  trabajosRecientes: Array<{
    id: number
    cliente?: {
      nombre: string
    }
    servicio?: {
      nombreServicio: string
    }
    estado?: {
      nombre: string
    }
    fechaRegistro: string
  }>
  servicios: Array<{
    id: number
    nombreServicio: string
    precioReferencial: number | string
    imagenReferencia: string | null
    estado: boolean
  }>
}

const props = defineProps<Props>()

// Debug temporal para ver los datos
console.log('Servicios recibidos:', props.servicios)

// Funciones auxiliares
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-BO', {
    style: 'currency',
    currency: 'BOB',
    minimumFractionDigits: 2
  }).format(amount)
}

const getEstadoClass = (estado: string | undefined) => {
  if (!estado) return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
  
  switch (estado) {
    case 'Pendiente':
      return 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30'
    case 'En Proceso':
      return 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
    case 'Completado':
      return 'bg-green-500/20 text-green-300 border border-green-500/30'
    case 'Cancelado':
      return 'bg-red-500/20 text-red-300 border border-red-500/30'
    default:
      return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
  }
}

const formatDate = (date: string | undefined) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-BO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const getImageUrl = (imagePath: string | null) => {
  if (!imagePath) return '/placeholder-image.jpg'
  return imagePath.startsWith('http') ? imagePath : `/storage/${imagePath}`
}
</script>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

/* Efectos de hover y transiciones */
.hover\:scale-105:hover {
  transform: scale(1.05);
}

/* Animaciones personalizadas */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}
</style>

