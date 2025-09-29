<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">✏️ Editar Usuario</h1>
              <p class="text-slate-300 text-lg">Modifica la información del usuario</p>
            </div>
            <Link :href="route('usuarios')" class="bg-gradient-to-r from-slate-600/50 to-slate-700/50 text-white px-6 py-3 rounded-xl border border-white/10">Volver</Link>
          </div>
        </div>

        <!-- Formulario principal -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <!-- Mensaje de Éxito -->
          <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-900/80 border border-green-400/50 text-green-200 rounded-xl backdrop-blur-sm">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ $page.props.flash.success }}</span>
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

          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <form @submit.prevent="submit" class="space-y-8">
              <!-- Información Personal -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Información Personal
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Nombre *</label>
                    <input 
                      v-model="form.nombre" 
                      type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                      required 
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Paterno</label>
                    <input 
                      v-model="form.apellidoPaterno" 
                      type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Materno</label>
                    <input 
                      v-model="form.apellidoMaterno" 
                      type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">CI</label>
                    <input 
                      v-model="form.ci" 
                      type="text" 
                      pattern="[0-9]*"
                      inputmode="numeric"
                      maxlength="20"
                      placeholder="Solo números"
                      @input="form.ci = form.ci.replace(/[^0-9]/g, '')"
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                        $page.props.errors?.ci ? 'border-red-500' : ''
                      ]"
                    />
                    <div v-if="$page.props.errors?.ci" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.ci }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Información de Contacto -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  Información de Contacto
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Email</label>
                    <input 
                      v-model="form.email" 
                      type="email" 
                      maxlength="150"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Teléfono</label>
                    <input 
                      v-model="form.telefono" 
                      type="tel" 
                      pattern="[0-9]*"
                      inputmode="numeric"
                      maxlength="20"
                      placeholder="Solo números"
                      @input="form.telefono = form.telefono.replace(/[^0-9]/g, '')"
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                        $page.props.errors?.telefono ? 'border-red-500' : ''
                      ]"
                    />
                    <div v-if="$page.props.errors?.telefono" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.telefono }}
                    </div>
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Dirección</label>
                    <input 
                      v-model="form.direccion" 
                      type="text" 
                      maxlength="255"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Información Laboral -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/></svg>
                  </div>
                  Información Laboral
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Rol *</label>
                    <select 
                      v-model="form.idRol" 
                      required
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    >
                      <option value="">Seleccionar rol...</option>
                      <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                        {{ rol.nombre }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Estado *</label>
                    <select 
                      v-model="form.estado" 
                      required
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    >
                      <option value="" disabled>Seleccionar estado</option>
                      <option :value="true">Activo</option>
                      <option :value="false">Inactivo</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha de Ingreso</label>
                    <input 
                      v-model="form.fechaIngreso" 
                      type="date" 
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha Final</label>
                    <input 
                      v-model="form.fechaFinal" 
                      type="date" 
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Contraseña -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-amber-400 to-pink-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </div>
                  Contraseña
                </h3>

                <div class="grid grid-cols-1 gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Nueva Contraseña (opcional)</label>
                    <input 
                      v-model="form.password" 
                      type="password" 
                      maxlength="255"
                      placeholder="Dejar vacío para mantener la contraseña actual"
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex items-center justify-between pt-8 border-t border-slate-600/50">
                <Link :href="route('usuarios')" class="px-8 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-slate-600/50">Cancelar</Link>
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
                  {{ form.processing ? 'Actualizando...' : 'Actualizar Usuario' }}
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
  usuario: { type: Object, required: true }, 
  roles: { type: Array, required: true } 
})

// Formulario usando Inertia
const form = useForm({
  nombre: props.usuario.nombre || '',
  apellidoPaterno: props.usuario.apellidoPaterno || '',
  apellidoMaterno: props.usuario.apellidoMaterno || '',
  ci: props.usuario.ci || '',
  telefono: props.usuario.telefono || '',
  direccion: props.usuario.direccion || '',
  email: props.usuario.email || '',
  password: '',
  fechaIngreso: props.usuario.fechaIngreso ? props.usuario.fechaIngreso.split('T')[0] : '',
  fechaFinal: props.usuario.fechaFinal ? props.usuario.fechaFinal.split('T')[0] : '',
  estado: Boolean(props.usuario.estado),
  idRol: props.usuario.idRol || ''
})

// Función de envío
const submit = () => {
  form.put(route('usuarios.update', props.usuario.id), {
    onSuccess: () => {
      // El mensaje de éxito se maneja desde el controlador
      console.log('Usuario actualizado exitosamente')
    },
    onError: (errors) => {
      console.log('Errores de validación:', errors)
    }
  })
}
</script>