<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header Principal Centrado -->
    <div class="relative overflow-hidden">
      <!-- Carrusel de Fondo con Efecto Cristal -->
      <div class="absolute inset-0 z-0">
        <ServiceCarousel v-if="servicios && servicios.length > 0" :servicios="servicios" class="h-96" />
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
          <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto mb-6">
            Explora y gestiona servicios, usuarios y roles del estudio
          </p>
          
          <!-- Botón de Iniciar Sesión -->
          <div v-if="!user" class="flex justify-center">
            <a href="/login" class="group relative inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
              <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              Iniciar Sesión
              <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-pink-700 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </a>
          </div>
          
          <!-- Mensaje para usuarios autenticados -->
          <div v-else class="flex justify-center">
            <div class="bg-green-500/20 border border-green-500/30 rounded-full px-6 py-3">
              <p class="text-green-300 font-medium">
                ¡Bienvenido, {{ user?.nombre || 'Usuario' }}! 
                <a href="/dashboard-admin" class="text-green-200 hover:text-green-100 underline ml-2">
                  Ir al Dashboard Admin
                </a>
              </p>
            </div>
          </div>
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
                v-for="servicio in (servicios || [])" 
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

      <!-- Información del Estudio -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            ¿Por qué elegir Foto Estudio EU?
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Calidad Profesional -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Calidad Profesional</h4>
              <p class="text-white/80">Utilizamos equipos de última generación y técnicas profesionales para garantizar resultados excepcionales.</p>
            </div>

            <!-- Experiencia -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Años de Experiencia</h4>
              <p class="text-white/80">Con años de experiencia en el mercado, conocemos las mejores técnicas para cada tipo de sesión fotográfica.</p>
            </div>

            <!-- Atención Personalizada -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300">
              <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <h4 class="text-xl font-bold text-white mb-3">Atención Personalizada</h4>
              <p class="text-white/80">Cada cliente es único. Trabajamos contigo para crear la sesión perfecta que refleje tu personalidad.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Galería de Trabajos Destacados -->
      <div class="py-12 px-6">
        <div class="max-w-6xl mx-auto">
          <h3 class="text-3xl font-bold text-white text-center mb-8">
            Nuestros Trabajos Destacados
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Trabajo 1 -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden hover:bg-white/15 transition-all duration-300 group">
              <div class="h-48 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="p-4">
                <h4 class="text-lg font-semibold text-white mb-2">Sesión de Retratos</h4>
                <p class="text-white/70 text-sm">Capturamos la esencia y personalidad única de cada cliente.</p>
              </div>
            </div>

            <!-- Trabajo 2 -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden hover:bg-white/15 transition-all duration-300 group">
              <div class="h-48 bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="p-4">
                <h4 class="text-lg font-semibold text-white mb-2">Eventos Especiales</h4>
                <p class="text-white/70 text-sm">Documentamos los momentos más importantes de tu vida.</p>
              </div>
            </div>

            <!-- Trabajo 3 -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden hover:bg-white/15 transition-all duration-300 group">
              <div class="h-48 bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <div class="p-4">
                <h4 class="text-lg font-semibold text-white mb-2">Fotografía Comercial</h4>
                <p class="text-white/70 text-sm">Ayudamos a tu negocio a destacar con imágenes profesionales.</p>
              </div>
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
                         <a :href="user ? '/registrar-trabajos' : '/login'" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-purple-300 transition-colors">Registrar Trabajo</h4>
                <p class="text-sm text-white/70 mt-2">{{ user ? 'Acceder ahora' : 'Inicia sesión para acceder' }}</p>
              </div>
            </a>

                         <a :href="user ? '/servicios' : '/login'" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-blue-300 transition-colors">Gestionar Servicios</h4>
                <p class="text-sm text-white/70 mt-2">{{ user ? 'Acceder ahora' : 'Inicia sesión para acceder' }}</p>
              </div>
            </a>

                         <a :href="user ? '/clientes' : '/login'" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-green-300 transition-colors">Gestionar Clientes</h4>
                <p class="text-sm text-white/70 mt-2">{{ user ? 'Acceder ahora' : 'Inicia sesión para acceder' }}</p>
              </div>
            </a>

              <a :href="user ? '/usuarios' : '/login'" class="group">
               <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 group-hover:scale-105">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <h4 class="text-lg font-semibold text-white group-hover:text-orange-300 transition-colors">Gestionar Usuarios</h4>
                <p class="text-sm text-white/70 mt-2">{{ user ? 'Acceder ahora' : 'Inicia sesión para acceder' }}</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import ServiceCarousel from '@/components/ServiceCarousel.vue'
import { computed } from 'vue'

interface Props {
  stats?: {
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
  trabajosRecientes?: Array<{
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
  servicios?: Array<{
    id: number
    nombreServicio: string
    precioReferencial: number | string
    imagenReferencia: string | null
    estado: boolean
  }>
}

const props = defineProps<Props>()

// Obtener datos del usuario autenticado
const page = usePage()
const user = computed(() => page.props.auth.user)

// Debug temporal para ver los datos
console.log('Servicios recibidos:', props.servicios)
console.log('Props completas:', props)
console.log('Usuario autenticado:', user.value)

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

