<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">➕ Crear Nuevo Rol</h1>
              <p class="text-slate-300 text-lg">Registra un nuevo rol en el sistema</p>
            </div>
            <Link :href="route('roles')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white hover:from-slate-600 hover:to-slate-700 text-lg px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg backdrop-blur-sm border border-white/10">Volver</Link>
          </div>
        </div>

        <!-- Formulario principal -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <form @submit.prevent="crearRol" class="space-y-8">
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                  </div>
                  Información del Rol
                </h3>

                <div class="grid grid-cols-1 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Nombre del Rol <span class="text-red-400">*</span></label>
                    <input v-model="form.nombre" type="text" placeholder="Ej: Administrador" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" required />
                    <p class="text-sm text-slate-400 mt-2">El nombre debe ser único en el sistema</p>
                  </div>
                </div>
              </div>

              <div class="flex items-center justify-between pt-8 border-t border-slate-600/50">
                <Link :href="route('roles')" class="px-8 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-slate-600/50">Cancelar</Link>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">
                  <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  Crear Rol
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
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const form = ref({ nombre: '' })

const crearRol = () => {
  if (!form.value.nombre.trim()) return alert('Ingresa el nombre del rol')
  router.post(route('roles.store'), form.value)
}
</script>
