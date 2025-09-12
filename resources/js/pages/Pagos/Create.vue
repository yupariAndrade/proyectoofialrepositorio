<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8 bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10">
        <div class="px-8 py-6 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Registrar Pago</h1>
            <p class="text-slate-400">Registra un nuevo pago en el sistema</p>
          </div>
          <Link :href="route('pagos')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg hover:from-slate-700 hover:to-slate-500 transition-all border border-white/10">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
          </Link>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-8">
          <form @submit.prevent="submit" class="space-y-8">
            <!-- Asignación -->
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="idTrabajo" class="block text-sm font-medium text-slate-300 mb-2">Trabajo *</label>
                <select id="idTrabajo" v-model="form.idTrabajo" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                  <option value="">Seleccionar trabajo</option>
                  <option v-for="trabajo in trabajos" :key="trabajo.id" :value="trabajo.id">
                    {{ trabajo.servicio?.nombreServicio || 'Servicio' }} — {{ (trabajo.cliente?.nombre || 'Cliente') + ' ' + (trabajo.cliente?.apellido || '') }}
                  </option>
                </select>
                <div v-if="form.errors.idTrabajo" class="text-red-400 text-sm mt-1">{{ form.errors.idTrabajo }}</div>
              </div>
              <div>
                <label for="idEstadoPago" class="block text-sm font-medium text-slate-300 mb-2">Estado del Pago *</label>
                <select id="idEstadoPago" v-model="form.idEstadoPago" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                  <option value="">Seleccionar estado</option>
                  <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
                </select>
                <div v-if="form.errors.idEstadoPago" class="text-red-400 text-sm mt-1">{{ form.errors.idEstadoPago }}</div>
              </div>
            </div>

            <!-- Datos del pago -->
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="monto" class="block text-sm font-medium text-slate-300 mb-2">Monto *</label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">Bs.</span>
                  <input id="monto" v-model="form.monto" type="number" step="0.01" min="0" class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="0.00" required />
                </div>
                <div v-if="form.errors.monto" class="text-red-400 text-sm mt-1">{{ form.errors.monto }}</div>
              </div>
              <div>
                <label for="fecha" class="block text-sm font-medium text-slate-300 mb-2">Fecha *</label>
                <input id="fecha" v-model="form.fecha" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required />
                <div v-if="form.errors.fecha" class="text-red-400 text-sm mt-1">{{ form.errors.fecha }}</div>
              </div>
            </div>

            <div>
              <label for="comentario" class="block text-sm font-medium text-slate-300 mb-2">Comentario</label>
              <textarea id="comentario" v-model="form.comentario" rows="3" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Comentario opcional (máx. 255)"></textarea>
              <div v-if="form.errors.comentario" class="text-red-400 text-sm mt-1">{{ form.errors.comentario }}</div>
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-white/10">
              <Link :href="route('pagos')" class="px-6 py-3 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg hover:from-slate-700 hover:to-slate-500 transition-all border border-white/10">Cancelar</Link>
              <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-cyan-500 via-sky-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:via-sky-600 hover:to-indigo-700 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                {{ form.processing ? 'Registrando...' : 'Registrar Pago' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ trabajos: { type: Array, required: true }, estadosPago: { type: Array, required: true } })

const form = useForm({ idTrabajo: '', idEstadoPago: '', monto: '', fecha: '', comentario: '' })

const submit = () => { form.post(route('pagos.store'), { onSuccess: () => form.reset() }) }
</script>













