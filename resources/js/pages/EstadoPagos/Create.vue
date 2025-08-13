<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
              Crear Estado de Pago
            </h1>
            <p class="text-slate-400 mt-2">
              Registra un nuevo estado para el control de pagos
            </p>
          </div>
          <Link
            :href="route('estado-pagos')"
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
            <!-- Información del Estado -->
            <div class="space-y-6">
              <div class="border-b border-white/10 pb-4">
                <h3 class="text-xl font-semibold text-white mb-2">
                  Información del Estado
                </h3>
                <p class="text-slate-400 text-sm">
                  Define el nombre del estado de pago
                </p>
              </div>

              <div>
                <label for="nombre" class="block text-sm font-medium text-slate-300 mb-2">
                  Nombre del Estado *
                </label>
                <input
                  id="nombre"
                  v-model="form.nombre"
                  type="text"
                  class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300"
                  placeholder="Ej: Pendiente, Pagado, Cancelado"
                  required
                />
                <div v-if="form.errors.nombre" class="text-red-400 text-sm mt-1">
                  {{ form.errors.nombre }}
                </div>
              </div>
            </div>

            <!-- Ejemplos de Estados Comunes -->
            <div class="bg-gradient-to-r from-slate-800/30 to-slate-700/30 rounded-xl p-6 border border-white/10">
              <h4 class="text-lg font-semibold text-white mb-4">
                Ejemplos de Estados Comunes
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center p-3 bg-green-500/10 border border-green-500/20 rounded-lg">
                  <div class="w-3 h-3 bg-green-400 rounded-full mr-3"></div>
                  <span class="text-green-400 font-medium">Pagado</span>
                </div>
                <div class="flex items-center p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                  <div class="w-3 h-3 bg-yellow-400 rounded-full mr-3"></div>
                  <span class="text-yellow-400 font-medium">Pendiente</span>
                </div>
                <div class="flex items-center p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                  <div class="w-3 h-3 bg-red-400 rounded-full mr-3"></div>
                  <span class="text-red-400 font-medium">Cancelado</span>
                </div>
                <div class="flex items-center p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                  <div class="w-3 h-3 bg-blue-400 rounded-full mr-3"></div>
                  <span class="text-blue-400 font-medium">Parcial</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/10">
              <Link
                :href="route('estado-pagos')"
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
                <span v-else>Crear Estado</span>
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

const form = useForm({ nombre: '' })
const submit = () => { form.post(route('estado-pagos.store')) }
</script>
