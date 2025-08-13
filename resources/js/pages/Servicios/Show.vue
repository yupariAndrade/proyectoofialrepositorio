<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">🔍 Detalles del Servicio</h1>
              <p class="text-slate-300 text-lg">Información completa del servicio fotográfico</p>
            </div>
            <Link :href="route('servicios')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white px-6 py-3 rounded-xl border border-white/10">Volver</Link>
          </div>
        </div>

        <!-- Contenido -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <div v-if="servicio?.imagenReferencia" class="mb-8">
              <img :src="getImageUrl(servicio.imagenReferencia)" :alt="servicio?.nombreServicio || 'Servicio'" class="w-full h-64 object-cover rounded-xl shadow-lg border border-slate-600/50" />
            </div>

            <div class="space-y-8">
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                  </div>
                  Información General
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Nombre del Servicio</label>
                    <p class="text-lg text-white">{{ servicio?.nombreServicio || '—' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Precio Referencial</label>
                    <p class="text-lg text-amber-400 font-bold">Bs. {{ Number(servicio?.precioReferencial || 0).toFixed(2) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Estado</label>
                    <span :class="['px-3 py-1 rounded-full text-sm font-medium', servicio?.estado ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30']">{{ servicio?.estado ? 'Activo' : 'Inactivo' }}</span>
                  </div>
                  <div class="col-span-2" v-if="servicio?.descripcion">
                    <label class="block text-sm font-semibold text-slate-400 mb-1">Descripción</label>
                    <p class="text-slate-300 whitespace-pre-line bg-slate-800/30 p-4 rounded-lg border border-slate-600/50">{{ servicio.descripcion }}</p>
                  </div>
                </div>
              </div>

              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Fotógrafo Responsable
                </h3>
                <div class="bg-gradient-to-r from-slate-700/50 to-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-600/50">
                  <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-amber-400 to-pink-500 flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="ml-4">
                      <h4 class="text-lg font-semibold text-white">{{ (usuario && usuario.nombre) ? usuario.nombre : '—' }} {{ (usuario && usuario.apellidoPaterno) ? usuario.apellidoPaterno : '' }}</h4>
                      <p v-if="usuario?.email" class="text-sm text-slate-400">{{ usuario.email }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-end pt-6 border-t border-slate-600/50">
                <Link :href="route('servicios.edit', servicio.id)" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl">Editar Servicio</Link>
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

const props = defineProps({ servicio: { type: Object, required: true }, usuario: { type: Object, required: false } })

const getImageUrl = (path) => { if (!path) return ''; const normalized = String(path).replace(/\\\\/g, '/').replace(/\\/g, '/'); return `/storage/${normalized}` }
</script>