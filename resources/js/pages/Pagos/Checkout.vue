<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Checkout de Pagos</h1>
              <p class="text-slate-300">Confirma y registra pagos para varios trabajos</p>
            </div>
            <Link :href="route('pagos')" class="px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg border border-white/10">Volver</Link>
          </div>
        </header>

        <main class="p-8">
          <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
            <!-- Lista de trabajos -->
            <div class="lg:col-span-2 bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-white/10 rounded-2xl p-6">
              <h2 class="text-xl text-white font-semibold mb-4">Trabajos seleccionados</h2>
              <div v-if="trabajos.length" class="space-y-4">
                <div v-for="t in trabajos" :key="t.id" class="flex items-center justify-between bg-slate-800/50 rounded-xl p-4 border border-white/10">
                  <div>
                    <div class="text-white font-medium">#{{ t.id }} — {{ t.servicio?.nombreServicio || 'Servicio' }}</div>
                    <div class="text-slate-300 text-sm">Cliente: {{ t.cliente?.nombre || 'Cliente' }}</div>
                  </div>
                  <div class="text-right">
                    <div class="text-slate-400 text-xs">Precio</div>
                    <div class="text-white font-semibold">{{ currency(t.servicio?.precioReferencial) }}</div>
                  </div>
                </div>
              </div>
              <div v-else class="text-slate-300">No hay trabajos seleccionados</div>
            </div>

            <!-- Resumen -->
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-white/10 rounded-2xl p-6">
              <h2 class="text-xl text-white font-semibold mb-4">Resumen</h2>
              <div class="space-y-3 mb-6">
                <div class="flex items-center justify-between">
                  <span class="text-slate-300">Subtotal</span>
                  <span class="text-white font-semibold">{{ currency(subtotal) }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-300">Total</span>
                  <span class="text-white font-semibold">{{ currency(subtotal) }}</span>
                </div>
              </div>

              <div class="space-y-4">
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Fecha *</label>
                  <input v-model="fecha" type="date" class="w-full px-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" />
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Comentario</label>
                  <textarea v-model="comentario" rows="2" class="w-full px-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" placeholder="Opcional"></textarea>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Estado del Pago *</label>
                  <select v-model="idEstadoPago" class="w-full px-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500">
                    <option :value="estadoDefault">Pendiente</option>
                  </select>
                </div>
                <button @click="confirmar" :disabled="loading || !trabajos.length || !fecha || !idEstadoPago" class="w-full px-4 py-3 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:to-indigo-700 transition disabled:opacity-50">{{ loading ? 'Registrando...' : 'Confirmar y Registrar' }}</button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ trabajos: { type: Array, default: () => [] }, estadoDefault: { type: [Number, String], default: '' }, hoy: { type: String, default: '' } })

const fecha = ref(props.hoy || '')
const comentario = ref('')
const idEstadoPago = ref(props.estadoDefault || '')
const loading = ref(false)

const subtotal = computed(() => (props.trabajos || []).reduce((acc, t) => acc + Number(t.servicio?.precioReferencial || 0), 0))
const currency = (n) => new Intl.NumberFormat('es-BO', { style: 'currency', currency: 'BOB' }).format(n || 0)

const confirmar = () => {
  loading.value = true
  router.post(route('pagos.storeMultiple'), { trabajos: props.trabajos.map(t => t.id), fecha: fecha.value, comentario: comentario.value, idEstadoPago: idEstadoPago.value }, { onFinish: () => loading.value = false })
}
</script>
