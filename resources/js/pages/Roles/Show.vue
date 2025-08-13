<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">👁️ Detalles del Rol</h1>
              <p class="text-slate-300 text-lg">Información completa del rol seleccionado</p>
            </div>
            <div class="flex items-center space-x-4">
              <button @click="generarReporte" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white px-6 py-3 rounded-xl border border-white/10">Generar Reporte</button>
              <Link :href="route('roles')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white px-6 py-3 rounded-xl border border-white/10">Volver</Link>
            </div>
          </div>
        </div>

        <div class="max-w-6xl mx-auto px-8 py-8">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 p-8">
                <div class="flex items-center justify-between mb-8">
                  <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-amber-400 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg">{{ rol.nombre?.charAt(0)?.toUpperCase() }}</div>
                    <div>
                      <h2 class="text-3xl font-bold text-white">{{ rol.nombre }}</h2>
                      <p class="text-slate-400">ID: #{{ rol.id }}</p>
                    </div>
                  </div>
                </div>

                <div class="space-y-6">
                  <div>
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                      <div class="w-6 h-6 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                      </div>
                      Información del Rol
                    </h3>
                    <div class="bg-gradient-to-r from-slate-700/50 to-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-600/50">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <span class="text-sm font-medium text-slate-400">Nombre del Rol</span>
                          <p class="text-lg font-semibold text-white mt-1">{{ rol.nombre }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div>
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                      <div class="w-6 h-6 mr-3 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      </div>
                      Información del Registro
                    </h3>
                    <div class="bg-gradient-to-r from-slate-700/50 to-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-600/50">
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                          <span class="text-sm font-medium text-slate-400">Fecha de Creación</span>
                          <p class="text-lg font-semibold text-white mt-1">{{ formatDate(rol.created_at) }}</p>
                        </div>
                        <div>
                          <span class="text-sm font-medium text-slate-400">Última Actualización</span>
                          <p class="text-lg font-semibold text-white mt-1">{{ formatDate(rol.updated_at) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-slate-600/50">
                  <Link :href="route('roles.edit', rol.id)" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl">Editar Rol</Link>
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Acciones</h3>
                <div class="space-y-3">
                  <Link :href="route('roles.edit', rol.id)" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-xl font-semibold transition-all duration-200 hover:from-amber-600 hover:to-pink-600 shadow-lg">Editar Rol</Link>
                  <button @click="eliminarRol" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl font-semibold transition-all duration-200 hover:from-red-600 hover:to-pink-600 shadow-lg">Eliminar Rol</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ rol: { type: Object, required: true } })
const rol = props.rol

const formatDate = (date) => { try { return new Date(date).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) } catch { return '—' } }
const eliminarRol = () => { if (confirm('¿Eliminar rol?')) router.delete(route('roles.destroy', rol.id)) }
const generarReporte = () => alert('Reporte en desarrollo')
</script>
