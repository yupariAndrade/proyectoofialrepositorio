<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-gray-800">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-lg border-b border-red-500/20 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
          <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent mb-2 sm:mb-3 flex items-center">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10 mr-2 sm:mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="hidden sm:inline">Detalles del Usuario</span>
                <span class="sm:hidden">Detalles</span>
              </h1>
              <p class="text-gray-300 text-sm sm:text-base lg:text-lg">Información completa del usuario</p>
            </div>
            <Link :href="route('usuarios')" class="bg-gradient-to-r from-gray-600/50 to-gray-700/50 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl border border-gray-600/50 hover:from-gray-600 hover:to-gray-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center text-sm sm:text-base">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
              Volver
            </Link>
          </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-red-500/20 p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">
            <!-- Datos principales -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                Información Personal
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Nombre
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.nombre }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Apellido Paterno
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.apellidoPaterno || '—' }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Apellido Materno
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.apellidoMaterno || '—' }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    CI
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.ci || '—' }}</p>
                </div>
              </div>
            </div>

            <!-- Contacto -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                Información de Contacto
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.email || '—' }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Teléfono
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.telefono || '—' }}</p>
                </div>
                <div class="md:col-span-2 bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Dirección
                  </label>
                  <p class="text-lg text-white font-medium">{{ usuario.direccion || '—' }}</p>
                </div>
              </div>
            </div>

            <!-- Laboral -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                Información Laboral
              </h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    Rol
                  </label>
                  <p class="text-lg text-red-400 font-semibold">{{ usuario.rol?.nombre || 'Sin rol' }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Estado
                  </label>
                  <span :class="['px-3 py-1 rounded-full text-sm font-medium', usuario.estado ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30']">{{ usuario.estado ? 'Activo' : 'Inactivo' }}</span>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Fecha de Ingreso
                  </label>
                  <p class="text-lg text-white font-medium">{{ formatDate(usuario.fechaIngreso) }}</p>
                </div>
                <div class="bg-black/30 rounded-xl p-4 border border-gray-600/30">
                  <label class="block text-sm font-semibold text-gray-400 mb-1 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Fecha Final
                  </label>
                  <p class="text-lg text-white font-medium">{{ formatDate(usuario.fechaFinal) }}</p>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end pt-4 sm:pt-6 border-t border-gray-600/50">
              <Link :href="route('usuarios.edit', usuario.id)" class="px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 flex items-center justify-center text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span class="hidden sm:inline">Editar Usuario</span>
                <span class="sm:hidden">Editar</span>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ usuario: { type: Object, required: true } })
const usuario = props.usuario

const formatDate = (dateString) => { if (!dateString) return '—'; try { return new Date(dateString).toLocaleDateString('es-ES') } catch { return '—' } }
</script>