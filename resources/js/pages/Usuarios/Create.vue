<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">➕ Crear Nuevo Usuario</h1>
                <p class="text-slate-300 text-lg">Registra un nuevo usuario en el sistema</p>
              </div>
              <Link :href="route('usuarios')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white hover:from-slate-600 hover:to-slate-700 text-lg px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg backdrop-blur-sm border border-white/10">
                <svg class="w-6 h-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
              </Link>
            </div>
          </div>
        </div>

        <!-- Formulario principal -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <form @submit.prevent="crearUsuario" class="space-y-8">
              <!-- Información Personal -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Información Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Nombre -->
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Nombre <span class="text-red-400">*</span></label>
                    <input v-model="form.nombre" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" required />
                  </div>

                  <!-- Apellido Paterno -->
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Paterno</label>
                    <input v-model="form.apellidoPaterno" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>

                  <!-- Apellido Materno -->
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Materno</label>
                    <input v-model="form.apellidoMaterno" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>

                  <!-- CI -->
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">CI</label>
                    <input v-model="form.ci" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                </div>
              </div>

              <!-- Información de Contacto -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  Información de Contacto
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                    <input v-model="form.email" type="email" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Teléfono</label>
                    <input v-model="form.telefono" type="tel" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Dirección</label>
                    <input v-model="form.direccion" type="text" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                </div>
              </div>

              <!-- Información Laboral -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  Información Laboral
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Rol <span class="text-red-400">*</span></label>
                    <select v-model="form.idRol" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white backdrop-blur-sm" required>
                      <option value="">Seleccione un rol</option>
                      <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha de Ingreso</label>
                    <input v-model="form.fechaIngreso" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha Final</label>
                    <input v-model="form.fechaFinal" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" />
                  </div>
                  <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input v-model="form.estado" type="checkbox" class="sr-only peer" />
                      <div class="w-11 h-6 bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                      <span class="ml-3 text-sm font-medium text-slate-300">Usuario Activo</span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Botones de acción -->
              <div class="flex items-center justify-end pt-8 border-t border-slate-600/50">
                <Link :href="route('usuarios')" class="px-6 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl font-semibold transition-all duration-200 mr-4 backdrop-blur-sm border border-slate-600/50">Cancelar</Link>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">Crear Usuario</button>
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

const props = defineProps({
  roles: { type: Array, required: true }
})

const form = ref({
  nombre: '',
  apellidoPaterno: '',
  apellidoMaterno: '',
  ci: '',
  telefono: '',
  direccion: '',
  email: '',
  fechaIngreso: '',
  fechaFinal: '',
  estado: true,
  idRol: ''
})

const crearUsuario = () => {
  if (!form.value.nombre || !form.value.idRol) return alert('Nombre y Rol son obligatorios')
  router.post(route('usuarios.store'), form.value, { onSuccess: () => router.visit(route('usuarios')) })
}
</script>
                  
