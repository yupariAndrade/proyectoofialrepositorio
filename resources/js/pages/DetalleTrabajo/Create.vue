<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-white bg-gradient-to-r from-amber-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
              Crear Detalle de Trabajo
            </h1>
            <p class="text-slate-400 mt-2">
              Registra un nuevo detalle específico para un trabajo
            </p>
          </div>
          <Link
            :href="route('detalle-trabajo.index')"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg hover:from-slate-600 hover:to-slate-500 transition-all duration-300 shadow-lg hover:shadow-xl"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
          </Link>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 shadow-2xl p-8">
          <form @submit.prevent="submit" class="space-y-8">
            <!-- Asignación de Trabajo -->
            <div class="space-y-6">
              <div class="border-b border-white/10 pb-4">
                <h3 class="text-xl font-semibold text-white mb-2">
                  Asignación de Trabajo
                </h3>
                <p class="text-slate-400 text-sm">
                  Selecciona el trabajo al que pertenece este detalle
                </p>
              </div>

              <div>
                <label for="idTrabajo" class="block text-sm font-medium text-slate-300 mb-2">
                  Trabajo *
                </label>
                <select
                  id="idTrabajo"
                  v-model="form.idTrabajo"
                  class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                  required
                >
                  <option value="">Seleccione un trabajo</option>
                  <option
                    v-for="trabajo in trabajos"
                    :key="trabajo.idTrabajo"
                    :value="trabajo.idTrabajo"
                  >
                    Trabajo #{{ trabajo.idTrabajo }}
                  </option>
                </select>
                <div v-if="form.errors.idTrabajo" class="text-red-400 text-sm mt-1">
                  {{ form.errors.idTrabajo }}
                </div>
              </div>
            </div>

            <!-- Información del Detalle -->
            <div class="space-y-6">
              <div class="border-b border-white/10 pb-4">
                <h3 class="text-xl font-semibold text-white mb-2">
                  Información del Detalle
                </h3>
                <p class="text-slate-400 text-sm">
                  Especifica los detalles del trabajo
                </p>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Descripción -->
                <div class="md:col-span-2">
                  <label for="descripcion" class="block text-sm font-medium text-slate-300 mb-2">
                    Descripción
                  </label>
                  <textarea
                    id="descripcion"
                    v-model="form.descripcion"
                    rows="3"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Describe los detalles específicos del trabajo"
                  ></textarea>
                  <div v-if="form.errors.descripcion" class="text-red-400 text-sm mt-1">
                    {{ form.errors.descripcion }}
                  </div>
                </div>

                <!-- Tamaño -->
                <div>
                  <label for="tamano" class="block text-sm font-medium text-slate-300 mb-2">
                    Tamaño
                  </label>
                  <input
                    id="tamano"
                    v-model="form.tamano"
                    type="text"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Ej: A4, 10x15, Grande"
                  />
                  <div v-if="form.errors.tamano" class="text-red-400 text-sm mt-1">
                    {{ form.errors.tamano }}
                  </div>
                </div>

                <!-- Color -->
                <div>
                  <label for="color" class="block text-sm font-medium text-slate-300 mb-2">
                    Color
                  </label>
                  <input
                    id="color"
                    v-model="form.color"
                    type="text"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Ej: Blanco, Negro, Color"
                  />
                  <div v-if="form.errors.color" class="text-red-400 text-sm mt-1">
                    {{ form.errors.color }}
                  </div>
                </div>

                <!-- Modelo -->
                <div>
                  <label for="modelo" class="block text-sm font-medium text-slate-300 mb-2">
                    Modelo
                  </label>
                  <input
                    id="modelo"
                    v-model="form.modelo"
                    type="text"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Ej: Estándar, Premium"
                  />
                  <div v-if="form.errors.modelo" class="text-red-400 text-sm mt-1">
                    {{ form.errors.modelo }}
                  </div>
                </div>

                <!-- Cantidad -->
                <div>
                  <label for="cantidad" class="block text-sm font-medium text-slate-300 mb-2">
                    Cantidad *
                  </label>
                  <input
                    id="cantidad"
                    v-model="form.cantidad"
                    type="number"
                    min="1"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Cantidad de unidades"
                    required
                  />
                  <div v-if="form.errors.cantidad" class="text-red-400 text-sm mt-1">
                    {{ form.errors.cantidad }}
                  </div>
                </div>

                <!-- Tipo de Documento -->
                <div>
                  <label for="tipoDocumento" class="block text-sm font-medium text-slate-300 mb-2">
                    Tipo de Documento
                  </label>
                  <input
                    id="tipoDocumento"
                    v-model="form.tipoDocumento"
                    type="text"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Ej: DNI, Pasaporte, Certificado"
                  />
                  <div v-if="form.errors.tipoDocumento" class="text-red-400 text-sm mt-1">
                    {{ form.errors.tipoDocumento }}
                  </div>
                </div>

                <!-- Tipo de Evento -->
                <div>
                  <label for="tipoEvento" class="block text-sm font-medium text-slate-300 mb-2">
                    Tipo de Evento
                  </label>
                  <input
                    id="tipoEvento"
                    v-model="form.tipoEvento"
                    type="text"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Ej: Boda, Cumpleaños, Corporativo"
                  />
                  <div v-if="form.errors.tipoEvento" class="text-red-400 text-sm mt-1">
                    {{ form.errors.tipoEvento }}
                  </div>
                </div>

                <!-- Otros -->
                <div class="md:col-span-2">
                  <label for="otros" class="block text-sm font-medium text-slate-300 mb-2">
                    Otros Detalles
                  </label>
                  <textarea
                    id="otros"
                    v-model="form.otros"
                    rows="2"
                    class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                    placeholder="Información adicional o especificaciones especiales"
                  ></textarea>
                  <div v-if="form.errors.otros" class="text-red-400 text-sm mt-1">
                    {{ form.errors.otros }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/10">
              <Link
                :href="route('detalle-trabajo.index')"
                class="px-6 py-3 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg hover:from-slate-600 hover:to-slate-500 transition-all duration-300 shadow-lg hover:shadow-xl"
              >
                Cancelar
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="px-6 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg hover:from-amber-600 hover:to-pink-600 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
              >
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

const props = defineProps({
  trabajos: {
    type: Array,
    required: true
  }
})

const form = useForm({
  idTrabajo: '',
  descripcion: '',
  tamano: '',
  color: '',
  modelo: '',
  cantidad: '',
  tipoDocumento: '',
  tipoEvento: '',
  otros: ''
})

const submit = () => {
  form.post(route('detalle-trabajo.store'))
}
</script>
