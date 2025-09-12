<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({
  cliente: {
    type: Object,
    required: true
  }
})

const getInitials = (nombre, apellido = '') => {
  const firstInitial = nombre ? nombre.charAt(0).toUpperCase() : 'C'
  const lastInitial = apellido ? apellido.charAt(0).toUpperCase() : ''
  return firstInitial + lastInitial
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black relative overflow-hidden">
        <!-- Efectos de fondo animados -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/10 via-gray-800/10 to-black/20 animate-pulse"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-900/15 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-gray-800/15 via-transparent to-transparent"></div>
        
        <!-- Header -->
        <header class="relative bg-gradient-to-r from-black/90 via-gray-900/90 to-black/90 backdrop-blur-xl border-b border-red-600/30 px-8 py-6 shadow-2xl">
          <!-- Efecto cristal -->
          <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 via-gray-600/10 to-black/10 backdrop-blur-sm"></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
          <div class="relative z-10 flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-red-500 via-white to-gray-300 bg-clip-text text-transparent mb-2">🧾 Detalles del Cliente</h1>
              <p class="text-gray-300">Información completa del cliente</p>
            </div>
            <div class="flex space-x-3">
              <Link :href="route('clientes.edit', cliente.id)" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all backdrop-blur-sm border border-red-500/50 shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
              </Link>
              <Link :href="route('clientes')" class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-3 rounded-xl font-medium border border-gray-600/50 hover:from-gray-800 hover:to-black transition-all backdrop-blur-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
              </Link>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="relative z-10 p-8">
          <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
              <!-- Información Personal -->
              <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-6 shadow-2xl">
                <!-- Efecto cristal -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
                <div class="relative z-10">
                  <h2 class="text-xl font-semibold text-white mb-4">👤 Información Personal</h2>
                  <div class="grid md:grid-cols-2 gap-6">
                    <div>
                      <div class="text-slate-400 text-sm mb-1">Nombre Completo</div>
                      <div class="text-white font-semibold text-lg">{{ cliente.nombre || 'No especificado' }} {{ cliente.apellido || '' }}</div>
                    </div>
                    <div>
                      <div class="text-slate-400 text-sm mb-1">CI</div>
                      <div class="text-white font-mono">{{ cliente.ci || 'No especificado' }}</div>
                    </div>
                    <div>
                      <div class="text-slate-400 text-sm mb-1">Teléfono</div>
                      <div class="text-white">{{ cliente.telefono || 'No especificado' }}</div>
                    </div>
                    <div>
                      <div class="text-slate-400 text-sm mb-1">Correo</div>
                      <div class="text-white break-all">{{ cliente.correoElectronico || 'No especificado' }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Usuario Asignado -->
              <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-6 shadow-2xl">
                <!-- Efecto cristal -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
                <div class="relative z-10">
                  <h2 class="text-xl font-semibold text-white mb-4">👨‍💼 Registrado por el Usuario que inicio Sesion</h2>
                  <div v-if="cliente.usuario" class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4 shadow-lg">
                      {{ getInitials(cliente.usuario.nombre, cliente.usuario.apellido || '') }}
                    </div>
                    <div>
                      <div class="text-white font-semibold">{{ cliente.usuario.nombre }} {{ cliente.usuario.apellido || '' }}</div>
                      <div class="text-slate-400 text-sm">{{ cliente.usuario.email }}</div>
                    </div>
                  </div>
                  <div v-else class="text-slate-400">
                    No hay usuario asignado
                  </div>
                </div>
              </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
              <!-- Avatar y Info Básica -->
              <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-6 shadow-2xl">
                <!-- Efecto cristal -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
                <div class="relative z-10 text-center">
                  <div class="w-24 h-24 bg-gradient-to-r from-red-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 shadow-2xl">
                    {{ getInitials(cliente.nombre, cliente.apellido) }}
                  </div>
                  <h3 class="text-xl font-bold text-white mb-2">{{ cliente.nombre }} {{ cliente.apellido || '' }}</h3>
                  <p class="text-slate-400 text-sm">Cliente  <!--{{ cliente.id }}--></p>
                </div>
              </div>

              <!-- Información de Registro -->
              <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-6 shadow-2xl">
                <!-- Efecto cristal -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
                <div class="relative z-10">
                  <h3 class="text-lg font-semibold text-white mb-4">📅 Información de Registro</h3>
                  <div class="space-y-3">
                    <div>
                      <div class="text-slate-400 text-sm">Fecha de Registro</div>
                      <div class="text-white font-medium">{{ formatDate(cliente.created_at) }}</div>
                    </div>
                    <div>
                      <div class="text-slate-400 text-sm">Última Actualización</div>
                      <div class="text-white font-medium">{{ formatDate(cliente.updated_at) }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Acciones Rápidas -->
              <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-6 shadow-2xl">
                <!-- Efecto cristal -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
                <div class="relative z-10">
                  <h3 class="text-lg font-semibold text-white mb-4">⚡ Acciones Rápidas</h3>
                  <div class="space-y-3">
                    <Link :href="route('clientes.edit', cliente.id)" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl font-medium hover:from-red-700 hover:to-red-800 transition-all backdrop-blur-sm border border-red-500/50 shadow-lg flex items-center justify-center">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                      Editar Cliente
                    </Link>
                    <Link :href="route('clientes')" class="w-full bg-gradient-to-r from-gray-700 to-gray-800 text-white px-4 py-3 rounded-xl font-medium border border-gray-600/50 hover:from-gray-800 hover:to-black transition-all backdrop-blur-sm flex items-center justify-center">
                      <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                      </svg>
                      Volver a Lista
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>