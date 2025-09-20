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
          <!-- Mensaje de Éxito Temporal -->
          <div v-if="showSuccessMessage" class="mb-6 p-4 bg-green-900/80 border border-green-400/50 text-green-200 rounded-xl backdrop-blur-sm animate-pulse">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ successMessage }}</span>
            </div>
          </div>

          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <form @submit.prevent="actualizarUsuario" class="space-y-8">
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
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white"
                      required 
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Paterno</label>
                    <input 
                      v-model="form.apellidoPaterno" 
                      type="text" 
                      class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Apellido Materno</label>
                    <input 
                      v-model="form.apellidoMaterno" 
                      @blur="verificarCampo('apellidoMaterno')"
                      @input="limpiarError('apellidoMaterno')"
                      type="text" 
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white',
                        errores.apellidoMaterno ? 'border-red-500' : 'border-slate-600/50'
                      ]"
                    />
                    <div v-if="errores.apellidoMaterno" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.apellidoMaterno }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">CI</label>
                    <input 
                      v-model="form.ci" 
                      @blur="verificarCampo('ci')"
                      @input="validarSoloNumeros('ci', $event)"
                      type="text" 
                      placeholder="Solo números"
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white',
                        errores.ci ? 'border-red-500' : 'border-slate-600/50'
                      ]"
                    />
                    <div v-if="errores.ci" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.ci }}
                    </div>
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
                    <input 
                      v-model="form.email" 
                      @blur="verificarCampo('email')"
                      @input="limpiarError('email')"
                      type="email" 
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white',
                        errores.email ? 'border-red-500' : 'border-slate-600/50'
                      ]"
                    />
                    <div v-if="errores.email" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.email }}
                    </div>
                  </div>
es da descuentocuentto                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Teléfono</label>
                    <input 
                      v-model="form.telefono" 
                      @blur="verificarCampo('telefono')"
                      @input="validarSoloNumeros('telefono', $event)"
                      type="tel" 
                      placeholder="Solo números (ej: 600123456)"
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white',
                        errores.telefono ? 'border-red-500' : 'border-slate-600/50'
                      ]"
                    />
                    <div v-if="errores.telefono" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.telefono }}
                    </div>
                  </div>
                  <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Dirección</label>
                    <input 
                      v-model="form.direccion" 
                      @blur="verificarCampo('direccion')"
                      @input="limpiarError('direccion')"
                      type="text" 
                      :class="[
                        'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white',
                        errores.direccion ? 'border-red-500' : 'border-slate-600/50'
                      ]"
                    />
                    <div v-if="errores.direccion" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.direccion }}
                    </div>
                  </div>
                  
                  <!-- Contraseña -->
                  <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Contraseña</label>
                    <div class="relative">
                      <input 
                        v-model="form.password" 
                        :type="mostrarPassword ? 'text' : 'password'"
                        class="w-full px-4 py-3 pr-12 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all duration-200 text-white placeholder:text-slate-400 backdrop-blur-sm" 
                        placeholder="Deja vacío para mantener la contraseña actual"
                      />
                      <button 
                        type="button"
                        @click="mostrarPassword = !mostrarPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-white transition-colors"
                      >
                        <svg v-if="mostrarPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                    </div>
                    <p class="mt-2 text-slate-400 text-sm">Deja vacío para mantener la contraseña actual, o ingresa una nueva</p>
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
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Rol *</label>
                    <select v-model="form.idRol" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white" required>
                      <option value="">Seleccione un rol</option>
                      <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha de Ingreso</label>
                    <input 
                      v-model="form.fechaIngreso" 
                      type="date" 
                      readonly
                      :class="[
                        'w-full px-4 py-3 bg-slate-700/50 border border-slate-500/50 rounded-xl text-slate-300 cursor-not-allowed'
                      ]"
                    />
                    <p class="mt-2 text-slate-400 text-sm">No se puede modificar la fecha de ingreso</p>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Fecha Final</label>
                    <input v-model="form.fechaFinal" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white" />
                    <p class="mt-2 text-slate-400 text-sm">Este campo puede quedar vacío</p>
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

              <!-- Mensaje de error general -->
              <div v-if="errorGeneral" class="bg-red-500/20 border border-red-500/50 rounded-xl p-4">
                <div class="flex items-center text-red-400">
                  <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                  <span class="font-semibold">{{ errorGeneral }}</span>
                </div>
              </div>

              <div class="flex items-center justify-end pt-8 border-t border-slate-600/50">
                <Link :href="route('usuarios')" class="px-6 py-3 text-slate-300 bg-slate-700/50 hover:bg-slate-700 rounded-xl border border-slate-600/50">Cancelar</Link>
                <button 
                  type="submit" 
                  :disabled="hayErrores || procesando"
                  :class="[
                    'ml-4 px-8 py-3 rounded-xl transition-all duration-200',
                    hayErrores || procesando 
                      ? 'bg-slate-600 text-slate-400 cursor-not-allowed' 
                      : 'bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white'
                  ]"
                >
                  <span v-if="procesando">Verificando...</span>
                  <span v-else>Actualizar Usuario</span>
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
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ 
  usuario: { type: Object, required: true }, 
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
  password: '',
  fechaIngreso: '', 
  fechaFinal: '', 
  estado: true, 
  idRol: '' 
})

const mostrarPassword = ref(false)
const errores = ref({})
const errorGeneral = ref('')
const procesando = ref(false)
const showSuccessMessage = ref(false)
const successMessage = ref('')

// Computed para verificar si hay errores
const hayErrores = computed(() => {
  return Object.keys(errores.value).length > 0 || !form.value.nombre || !form.value.idRol
})

onMounted(() => {
  const u = props.usuario || {}
  form.value = { 
    nombre: u.nombre || '', 
    apellidoPaterno: u.apellidoPaterno || '', 
    apellidoMaterno: u.apellidoMaterno || '', 
    ci: u.ci || '', 
    telefono: u.telefono || '', 
    direccion: u.direccion || '', 
    email: u.email || '', 
    password: '', // No cargar la contraseña actual
    fechaIngreso: u.fechaIngreso || '', 
    fechaFinal: u.fechaFinal || '', 
    estado: Boolean(u.estado), 
    idRol: u.idRol || '' 
  }
})

// Limpiar error de un campo específico
const limpiarError = (campo) => {
  if (errores.value[campo]) {
    delete errores.value[campo]
  }
}

// Función para validar que solo se ingresen números
const validarSoloNumeros = (campo, event) => {
  const input = event.target
  const valor = input.value
  
  // Remover cualquier carácter que no sea número
  const soloNumeros = valor.replace(/[^0-9]/g, '')
  
  // Actualizar el valor del input
  input.value = soloNumeros
  form.value[campo] = soloNumeros
  
  // Limpiar error si el campo es válido
  if (soloNumeros.length > 0) {
    delete errores.value[campo]
  } else if (campo === 'telefono') {
    errores.value[campo] = 'El teléfono debe contener solo números'
  } else if (campo === 'ci') {
    errores.value[campo] = 'El CI debe contener solo números'
  }
}

// Verificar si un campo ya existe (excluyendo el usuario actual)
const verificarCampo = async (campo) => {
  if (!form.value[campo] || campo === 'fechaFinal') return
  
  try {
    procesando.value = true
    const response = await fetch(`/api/usuarios/verificar-campo`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({
        campo: campo,
        valor: form.value[campo],
        excludeId: props.usuario.id
      })
    })
    
    const data = await response.json()
    
    if (data.existe) {
      errores.value[campo] = `El ${campo} ya existe en otro usuario`
    } else {
      limpiarError(campo)
    }
  } catch (error) {
    console.error('Error al verificar campo:', error)
  } finally {
    procesando.value = false
  }
}

const actualizarUsuario = async () => {
  if (!form.value.nombre || !form.value.idRol) {
    errorGeneral.value = 'Nombre y Rol son obligatorios'
    return
  }
  
  // Verificar todos los campos antes de enviar
  const camposParaVerificar = ['nombre', 'apellidoPaterno', 'apellidoMaterno', 'ci', 'telefono', 'direccion', 'email', 'fechaIngreso']
  
  for (const campo of camposParaVerificar) {
    if (form.value[campo]) {
      await verificarCampo(campo)
    }
  }
  
  // Si hay errores, no enviar
  if (Object.keys(errores.value).length > 0) {
    errorGeneral.value = 'Por favor, corrige los errores antes de continuar'
    return
  }
  
  // Si no hay contraseña nueva, no enviar el campo
  const datosParaEnviar = { ...form.value }
  if (!datosParaEnviar.password) {
    delete datosParaEnviar.password
  }
  
  // Limpiar errores y enviar
  errorGeneral.value = ''
  router.put(route('usuarios.update', props.usuario.id), datosParaEnviar, { 
    onSuccess: () => {
      showSuccessMessage.value = true
      successMessage.value = 'Usuario actualizado exitosamente'
      setTimeout(() => {
        router.visit(route('usuarios'))
      }, 2000)
    },
    onError: (errors) => {
      // Manejar errores del backend
      Object.keys(errors).forEach(key => {
        if (key !== 'general') {
          errores.value[key] = errors[key]
        }
      })
      if (errors.general) {
        errorGeneral.value = errors.general
      }
    }
  })
}
</script>