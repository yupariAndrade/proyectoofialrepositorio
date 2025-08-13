<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Detalle de Trabajo</h1>
              <p class="text-slate-300">Información completa del detalle</p>
            </div>
            <div class="flex items-center gap-3">
              <Link :href="route('detalle-trabajo.edit', detalleTrabajo.id)" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:to-indigo-700 transition-all">Editar</Link>
              <Link :href="route('detalle-trabajo')" class="px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg border border-white/10 hover:from-slate-700 hover:to-slate-500 transition-all">Volver</Link>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="p-8">
          <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Trabajo</h2>
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <div class="text-slate-400 text-sm">Servicio</div>
                    <div class="text-white font-semibold">{{ detalleTrabajo.trabajo?.servicio?.nombreServicio || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Cliente</div>
                    <div class="text-white font-semibold">{{ detalleTrabajo.trabajo?.cliente?.nombre || '—' }} {{ detalleTrabajo.trabajo?.cliente?.apellido || '' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Trabajo #</div>
                    <div class="text-white font-mono">{{ detalleTrabajo.trabajo?.id || detalleTrabajo.idTrabajo }}</div>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Especificaciones</h2>
                <div class="grid md:grid-cols-2 gap-4">
                  <div>
                    <div class="text-slate-400 text-sm">Descripción</div>
                    <div class="text-white">{{ detalleTrabajo.descripcion || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Tamaño</div>
                    <div class="text-white">{{ detalleTrabajo.tamano || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Color</div>
                    <div class="text-white">{{ detalleTrabajo.color || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Modelo</div>
                    <div class="text-white">{{ detalleTrabajo.modelo || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Cantidad</div>
                    <div class="text-white">{{ detalleTrabajo.cantidad || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Tipo de Documento</div>
                    <div class="text-white">{{ detalleTrabajo.tipoDocumento || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Tipo de Evento</div>
                    <div class="text-white">{{ detalleTrabajo.tipoEvento || '—' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Otros</div>
                    <div class="text-white">{{ detalleTrabajo.otros || '—' }}</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Sistema</h2>
                <div class="space-y-3">
                  <div>
                    <div class="text-slate-400 text-sm">Creado</div>
                    <div class="text-white">{{ formatDate(detalleTrabajo.created_at) }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Actualizado</div>
                    <div class="text-white">{{ formatDate(detalleTrabajo.updated_at) }}</div>
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

<script setup>
import { router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ detalleTrabajo: { type: Object, required: true } })

const formatDate = (dateString) => {
  if (!dateString) return 'No disponible'
  try { return new Date(dateString).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }) } catch { return 'Fecha inválida' }
}
</script>
