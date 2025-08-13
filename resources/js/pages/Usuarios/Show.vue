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

        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 p-8 space-y-8">
            <!-- Datos principales -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                Información Personal
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Nombre</label><p class="text-lg text-white">{{ usuario.nombre }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Apellido Paterno</label><p class="text-lg text-white">{{ usuario.apellidoPaterno || '—' }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Apellido Materno</label><p class="text-lg text-white">{{ usuario.apellidoMaterno || '—' }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">CI</label><p class="text-lg text-white">{{ usuario.ci || '—' }}</p></div>
              </div>
            </div>

            <!-- Contacto -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                Información de Contacto
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Email</label><p class="text-lg text-white">{{ usuario.email || '—' }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Teléfono</label><p class="text-lg text-white">{{ usuario.telefono || '—' }}</p></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-slate-400 mb-1">Dirección</label><p class="text-lg text-white">{{ usuario.direccion || '—' }}</p></div>
              </div>
            </div>

            <!-- Laboral -->
            <div>
              <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-8 h-8 mr-3 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                Información Laboral
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Rol</label><p class="text-lg text-amber-400 font-semibold">{{ usuario.rol?.nombre || 'Sin rol' }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Estado</label><span :class="['px-3 py-1 rounded-full text-sm font-medium', usuario.estado ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30']">{{ usuario.estado ? 'Activo' : 'Inactivo' }}</span></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Fecha de Ingreso</label><p class="text-lg text-white">{{ formatDate(usuario.fechaIngreso) }}</p></div>
                <div><label class="block text-sm font-semibold text-slate-400 mb-1">Fecha Final</label><p class="text-lg text-white">{{ formatDate(usuario.fechaFinal) }}</p></div>
              </div>
            </div>

            <div class="flex items-center justify-end pt-6 border-t border-slate-600/50">
              <Link :href="route('usuarios.edit', usuario.id)" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl">Editar Usuario</Link>
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
