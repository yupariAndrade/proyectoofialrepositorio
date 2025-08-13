<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Detalle de Bitácora</h1>
              <p class="text-slate-300">Información del registro</p>
            </div>
            <Link :href="route('bitacora-trabajos')" class="px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg border border-white/10">Volver</Link>
          </div>
        </header>

        <main class="p-8">
          <div class="max-w-4xl mx-auto bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-white/10 rounded-2xl p-8">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <div class="text-slate-400 text-sm">Trabajo</div>
                <div class="text-white font-semibold">#{{ bitacora.idTrabajo }} — {{ bitacora.trabajo?.servicio?.nombreServicio || 'Servicio' }}</div>
              </div>
              <div>
                <div class="text-slate-400 text-sm">Usuario</div>
                <div class="text-white font-semibold">{{ bitacora.usuario?.nombre || 'Usuario' }}</div>
              </div>
              <div>
                <div class="text-slate-400 text-sm">Acción</div>
                <div class="text-white">{{ bitacora.accion }}</div>
              </div>
              <div>
                <div class="text-slate-400 text-sm">Fecha</div>
                <div class="text-white">{{ formatDateTime(bitacora.fecha) }}</div>
              </div>
              <div class="md:col-span-2" v-if="bitacora.descripcion">
                <div class="text-slate-400 text-sm">Descripción</div>
                <div class="text-white">{{ bitacora.descripcion }}</div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ bitacora: { type: Object, required: true } })
const bitacora = props.bitacora
const formatDateTime = (d) => { try { return new Date(d).toLocaleString('es-ES') } catch { return '—' } }
</script>
