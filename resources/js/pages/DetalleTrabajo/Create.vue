<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8 bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10">
        <div class="px-8 py-6 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Crear Detalle de Trabajo</h1>
            <p class="text-slate-400 mt-2">Registra un nuevo detalle específico para un trabajo</p>
          </div>
          <Link :href="route('detalle-trabajo')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg hover:from-slate-700 hover:to-slate-500 transition-all border border-white/10">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver
          </Link>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-8">
          <form @submit.prevent="submit" class="space-y-8">
            <!-- Trabajo -->
            <div>
              <label for="idTrabajo" class="block text-sm font-medium text-slate-300 mb-2">Trabajo *</label>
              <select id="idTrabajo" v-model="form.idTrabajo" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" required>
                <option value="">Seleccione un trabajo</option>
                <option v-for="trabajo in trabajos" :key="trabajo.id" :value="trabajo.id">{{ trabajo.servicio?.nombreServicio || 'Servicio' }} — {{ (trabajo.cliente?.nombre || 'Cliente') + ' ' + (trabajo.cliente?.apellido || '') }}</option>
              </select>
              <div v-if="form.errors.idTrabajo" class="text-red-400 text-sm mt-1">{{ form.errors.idTrabajo }}</div>
            </div>

            <!-- Información del Detalle -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-slate-300 mb-2">Descripción</label>
                <textarea id="descripcion" v-model="form.descripcion" rows="3" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Describe los detalles específicos del trabajo"></textarea>
                <div v-if="form.errors.descripcion" class="text-red-400 text-sm mt-1">{{ form.errors.descripcion }}</div>
              </div>
              <div>
                <label for="tamano" class="block text-sm font-medium text-slate-300 mb-2">Tamaño</label>
                <input id="tamano" v-model="form.tamano" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Ej: A4, 10x15, Grande" />
                <div v-if="form.errors.tamano" class="text-red-400 text-sm mt-1">{{ form.errors.tamano }}</div>
              </div>
              <div>
                <label for="color" class="block text-sm font-medium text-slate-300 mb-2">Color</label>
                <input id="color" v-model="form.color" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Ej: Blanco, Negro, Color" />
                <div v-if="form.errors.color" class="text-red-400 text-sm mt-1">{{ form.errors.color }}</div>
              </div>
              <div>
                <label for="modelo" class="block text-sm font-medium text-slate-300 mb-2">Modelo</label>
                <input id="modelo" v-model="form.modelo" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Ej: Estándar, Premium" />
                <div v-if="form.errors.modelo" class="text-red-400 text-sm mt-1">{{ form.errors.modelo }}</div>
              </div>
              <div>
                <label for="cantidad" class="block text-sm font-medium text-slate-300 mb-2">Cantidad *</label>
                <input id="cantidad" v-model="form.cantidad" type="number" min="1" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Cantidad de unidades" />
                <div v-if="form.errors.cantidad" class="text-red-400 text-sm mt-1">{{ form.errors.cantidad }}</div>
              </div>
              <div>
                <label for="tipoDocumento" class="block text-sm font-medium text-slate-300 mb-2">Tipo de Documento</label>
                <input id="tipoDocumento" v-model="form.tipoDocumento" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Ej: DNI, Pasaporte, Certificado" />
                <div v-if="form.errors.tipoDocumento" class="text-red-400 text-sm mt-1">{{ form.errors.tipoDocumento }}</div>
              </div>
              <div>
                <label for="tipoEvento" class="block text-sm font-medium text-slate-300 mb-2">Tipo de Evento</label>
                <input id="tipoEvento" v-model="form.tipoEvento" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Ej: Boda, Cumpleaños, Corporativo" />
                <div v-if="form.errors.tipoEvento" class="text-red-400 text-sm mt-1">{{ form.errors.tipoEvento }}</div>
              </div>
              <div class="md:col-span-2">
                <label for="otros" class="block text-sm font-medium text-slate-300 mb-2">Otros Detalles</label>
                <textarea id="otros" v-model="form.otros" rows="2" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Información adicional o especificaciones especiales"></textarea>
                <div v-if="form.errors.otros" class="text-red-400 text-sm mt-1">{{ form.errors.otros }}</div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/10">
              <Link :href="route('detalle-trabajo')" class="px-6 py-3 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg hover:from-slate-700 hover:to-slate-500 transition-all border border-white/10">Cancelar</Link>
              <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-cyan-500 via-sky-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:via-sky-600 hover:to-indigo-700 transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                <span v-if="form.processing">Creando...</span>
                <span v-else>Crear Detalle</span>
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

const props = defineProps({ trabajos: { type: Array, required: true } })

const form = useForm({ idTrabajo: '', descripcion: '', tamano: '', color: '', modelo: '', cantidad: '', tipoDocumento: '', tipoEvento: '', otros: '' })
const submit = () => { form.post(route('detalle-trabajo.store')) }
</script>
