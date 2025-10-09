<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">✏️ Editar Cliente</h1>
              <p class="text-slate-300 text-lg">Modifica la información del cliente</p>
            </div>
            <Link :href="route('clientes')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white hover:from-slate-600 hover:to-slate-700 text-lg px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg backdrop-blur-sm border border-white/10">Volver</Link>
          </div>
        </div>

        <!-- Formulario principal -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            
            <!-- Mensaje de Éxito -->
            <div v-if="$page.props.flash?.success" class="mb-4 flex items-center justify-center">
              <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 animate-pulse">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold text-sm">{{ $page.props.flash.success }}</span>
              </div>
            </div>

            <!-- Mensaje de Error -->
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="mb-6 p-4 bg-red-900/80 border border-red-400/50 text-red-200 rounded-xl backdrop-blur-sm">
              <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                  <p class="font-medium">⚠️ Errores en el formulario:</p>
                  <ul class="text-sm mt-1 space-y-1">
                    <li v-for="(error, field) in $page.props.errors" :key="field" class="flex items-center gap-2">
                      <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                      <span class="text-red-300">{{ error }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Información del Cliente
                </h3>

                <div class="grid grid-cols-1 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Nombre <span class="text-red-400">*</span></label>
                    <input 
                      v-model="form.nombre" 
                      type="text" 
                      placeholder="Ej: Juan Carlos" 
                      maxlength="50"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                      required 
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Apellido <span class="text-red-400">*</span></label>
                    <input 
                      v-model="form.apellido" 
                      type="text" 
                      placeholder="Ej: Pérez García" 
                      maxlength="50"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                      required 
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">CI <span class="text-red-400">*</span></label>
                    <input 
                      v-model="form.ci" 
                      type="text" 
                      placeholder="Ej: 12345678" 
                      maxlength="20"
                      pattern="[0-9]*"
                      inputmode="numeric"
                      @input="form.ci = form.ci.replace(/[^0-9]/g, '')"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                      required 
                    />
                    <div v-if="$page.props.errors?.ci" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.ci }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Teléfono <span class="text-red-400">*</span></label>
                    <input 
                      v-model="form.telefono" 
                      type="tel" 
                      placeholder="Ej: 70123456" 
                      maxlength="20"
                      pattern="[0-9]*"
                      inputmode="numeric"
                      @input="form.telefono = form.telefono.replace(/[^0-9]/g, '')"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                      required 
                    />
                    <div v-if="$page.props.errors?.telefono" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.telefono }}
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Correo Electrónico <span class="text-red-400">*</span></label>
                    <input 
                      v-model="form.correoElectronico" 
                      type="email" 
                      placeholder="Ej: juan@email.com" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                      required 
                    />
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between pt-8 border-t border-slate-600/50">
                <Link :href="route('clientes')" class="px-8 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-slate-600/50">Cancelar</Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                  <svg v-if="form.processing" class="w-5 h-5 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  <svg v-else class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ form.processing ? 'Actualizando...' : 'Actualizar Cliente' }}
                </button>
              </div>
            </form>
          </div>
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
  cliente: { type: Object, required: true }
})

// Formulario usando Inertia con datos precargados
const form = useForm({
  nombre: props.cliente.nombre || '',
  apellido: props.cliente.apellido || '',
  ci: props.cliente.ci || '',
  telefono: props.cliente.telefono || '',
  correoElectronico: props.cliente.correoElectronico || ''
})

// Función de envío
const submit = () => {
  form.put(route('clientes.update', props.cliente.id))
}
</script>