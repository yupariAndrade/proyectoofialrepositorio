<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">👤 Detalles del Usuario</h1>
              <p class="text-slate-300 text-lg">Información completa del usuario</p>
            </div>
            <Link :href="route('usuarios')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white px-6 py-3 rounded-xl border border-white/10">Volver</Link>
          </div>
        </div>

        <!-- Contenido -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <div class="space-y-8">
              <!-- Información Personal -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Información Personal
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Nombre</label>
                    <p class="text-lg text-white">{{ usuario?.nombre || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Apellido Paterno</label>
                    <p class="text-lg text-white">{{ usuario?.apellidoPaterno || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Apellido Materno</label>
                    <p class="text-lg text-white">{{ usuario?.apellidoMaterno || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">CI</label>
                    <p class="text-lg text-white">{{ usuario?.ci || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Teléfono</label>
                    <p class="text-lg text-white">{{ usuario?.telefono || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Email</label>
                    <p class="text-lg text-white">{{ usuario?.email || '—' }}</p>
                  </div>
                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Dirección</label>
                    <p class="text-lg text-white">{{ usuario?.direccion || '—' }}</p>
                  </div>
                </div>
              </div>

              <!-- Información Laboral -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/></svg>
                  </div>
                  Información Laboral
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Rol</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                      {{ usuario?.rol?.nombre || '—' }}
                    </span>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Estado</label>
                    <span :class="['inline-flex items-center px-3 py-1 rounded-full text-sm font-medium', usuario?.estado ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30']">
                      {{ usuario?.estado ? 'Activo' : 'Inactivo' }}
                    </span>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Fecha de Ingreso</label>
                    <p class="text-lg text-white">{{ formatDate(usuario?.fechaIngreso) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Fecha Final</label>
                    <p class="text-lg text-white">{{ formatDate(usuario?.fechaFinal) }}</p>
                  </div>
                </div>
              </div>

              <!-- Información del Sistema -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  Información del Sistema
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">ID del Usuario</label>
                    <p class="text-lg text-white">{{ usuario?.id || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Fecha de Registro</label>
                    <p class="text-lg text-white">{{ formatDate(usuario?.created_at) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Última Actualización</label>
                    <p class="text-lg text-white">{{ formatDate(usuario?.updated_at) }}</p>
                  </div>
                </div>
              </div>

              <!-- Acciones -->
              <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-700/50">
                <Link :href="route('usuarios.edit', usuario.id)" class="flex-1 px-6 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 text-center">
                  <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                  Editar Usuario
                </Link>
                <Link :href="route('usuarios')" class="flex-1 px-6 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-slate-600/50 text-center">
                  <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                  </svg>
                  Volver a la Lista
                </Link>
              </div>
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

const props = defineProps({
  usuario: { type: Object, required: true }
})

// Función para formatear fechas
const formatDate = (dateString) => {
  if (!dateString) return '—'
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>