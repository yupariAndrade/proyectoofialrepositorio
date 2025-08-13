<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Detalle del Pago</h1>
              <p class="text-slate-300">Información completa del pago</p>
            </div>
            <div class="flex items-center gap-3">
              <Link :href="route('pagos.edit', pago.idPago)" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:to-indigo-700 transition-all">Editar</Link>
              <Link :href="route('pagos')" class="px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg border border-white/10 hover:from-slate-700 hover:to-slate-500 transition-all">Volver</Link>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="p-8">
          <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
              <!-- Asignación -->
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Asignación de Trabajo</h2>
                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <div class="text-slate-400 text-sm">Trabajo</div>
                    <div class="text-white font-semibold">#{{ pago.trabajo?.id || pago.idTrabajo }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Cliente</div>
                    <div class="text-white font-semibold">{{ pago.trabajo?.cliente?.nombre || '—' }} {{ pago.trabajo?.cliente?.apellido || '' }}</div>
                  </div>
                </div>
              </div>

              <!-- Detalles del Pago -->
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Detalles del Pago</h2>
                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <div class="text-slate-400 text-sm">Monto</div>
                    <div class="text-white font-semibold text-2xl">{{ formatCurrency(pago.monto) }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Estado</div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" :class="getEstadoColor(pago.estadoPago?.nombre)">
                      {{ pago.estadoPago?.nombre || 'No especificado' }}
                    </span>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Fecha</div>
                    <div class="text-white font-semibold">{{ formatDate(pago.fecha) }}</div>
                  </div>
                  <div v-if="pago.comentario">
                    <div class="text-slate-400 text-sm">Comentario</div>
                    <div class="text-white">{{ pago.comentario }}</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Sistema</h2>
                <div class="space-y-3">
                  <div>
                    <div class="text-slate-400 text-sm">ID del Pago</div>
                    <div class="text-white">#{{ pago.idPago }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Creado</div>
                    <div class="text-white">{{ formatDate(pago.created_at) }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm">Actualizado</div>
                    <div class="text-white">{{ formatDate(pago.updated_at) }}</div>
                  </div>
                </div>
              </div>
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Acciones</h2>
                <div class="space-y-3">
                  <Link :href="route('pagos.edit', pago.idPago)" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-xl hover:from-cyan-600 hover:to-indigo-700 transition-all">Editar Pago</Link>
                  <button @click="deletePago" class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl hover:from-red-500 hover:to-pink-500 transition-all">Eliminar Pago</button>
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

const props = defineProps({ pago: { type: Object, required: true } })

const formatCurrency = (amount) => new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(amount || 0)
const formatDate = (d) => { try { return new Date(d).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) } catch { return '—' } }
const getEstadoColor = (estadoNombre) => {
  if (!estadoNombre) return 'bg-slate-500/20 text-slate-300'
  const e = estadoNombre.toLowerCase()
  if (e.includes('pagado') || e.includes('completado')) return 'bg-green-500/20 text-green-300'
  if (e.includes('pendiente')) return 'bg-yellow-500/20 text-yellow-300'
  if (e.includes('cancelado') || e.includes('rechazado')) return 'bg-red-500/20 text-red-300'
  return 'bg-blue-500/20 text-blue-300'
}

const deletePago = () => { if (confirm('¿Eliminar este pago?')) router.delete(route('pagos.destroy', props.pago.idPago)) }
</script>
