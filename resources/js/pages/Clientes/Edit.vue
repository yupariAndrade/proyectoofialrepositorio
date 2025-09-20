<script setup>
import { ref } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const $page = usePage()

const props = defineProps({ 
  cliente: { type: Object, required: true }
  // Ya no necesitamos usuarios como prop
})

const form = useForm({ 
  nombre: props.cliente.nombre || '', 
  apellido: props.cliente.apellido || '', 
  ci: props.cliente.ci || '', 
  telefono: props.cliente.telefono || '', 
  correoElectronico: props.cliente.correoElectronico || ''
  // idUsuario se maneja automáticamente en el backend
})

const showSuccessMessage = ref(false)
const successMessage = ref('')
const errores = ref({})

// Verificar campo individual
const verificarCampo = async (campo) => {
  if (!form[campo]) return
  
  try {
    const response = await fetch(`/api/clientes/verificar-campo`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({
        campo: campo,
        valor: form[campo],
        id: props.cliente.id // Excluir el cliente actual
      })
    })
    
    const data = await response.json()
    
    if (data.existe) {
      errores.value[campo] = data.mensaje
    } else {
      limpiarError(campo)
    }
  } catch (error) {
    console.error('Error al verificar campo:', error)
  }
}

// Verificar duplicados por nombre y CI
const verificarDuplicados = async () => {
  if (!form.nombre) return
  
  try {
    const response = await fetch(`/api/clientes/verificar-duplicados`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({
        nombre: form.nombre,
        ci: form.ci,
        id: props.cliente.id // Excluir el cliente actual
      })
    })
    
    const data = await response.json()
    
    if (data.existe) {
      errores.value.duplicados = data.motivos || [data.mensaje]
    } else {
      limpiarError('duplicados')
    }
  } catch (error) {
    console.error('Error al verificar duplicados:', error)
  }
}

// Verificar campo individual y duplicados
const verificarCampoYDuplicados = async (campo) => {
  await verificarCampo(campo)
  await verificarDuplicados()
}

// Limpiar error específico
const limpiarError = (campo) => {
  if (errores.value[campo]) {
    delete errores.value[campo]
  }
}

// Validar solo números para CI
const validarSoloNumeros = (campo, event) => {
  const valor = event.target.value
  const soloNumeros = valor.replace(/[^0-9]/g, '')
  if (valor !== soloNumeros) {
    form[campo] = soloNumeros
  }
}

const submit = () => {
  if (!form.nombre || !form.ci) {
    errores.value.general = 'Nombre y CI son obligatorios'
    return
  }
  
  // Verificar duplicados antes de enviar
  verificarDuplicados()
  
  // Verificar campos únicos antes de enviar
  const camposParaVerificar = ['nombre', 'ci']
  
  for (const campo of camposParaVerificar) {
    if (form[campo]) {
      verificarCampo(campo)
    }
  }
  
  // Si hay errores, no enviar
  if (Object.keys(errores.value).length > 0) {
    errores.value.general = 'Por favor, corrige los errores antes de continuar'
    return
  }
  
  form.put(route('clientes.update', props.cliente.id), { 
    preserveScroll: true, 
    onSuccess: () => {
      showSuccessMessage.value = true
      successMessage.value = 'Cliente actualizado exitosamente'
      setTimeout(() => {
        router.visit(route('clientes'))
      }, 2000)
    } 
  })
}
</script>

<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">✏️ Editar Cliente</h1>
              <p class="text-slate-300">Modifica la información del cliente</p>
            </div>
            <Link :href="route('clientes')" class="bg-gradient-to-r from-slate-600/60 to-slate-700/60 text-white px-6 py-3 rounded-xl font-medium border border-white/10 hover:from-slate-700/60 hover:to-slate-800/60 transition-all">Volver</Link>
          </div>
        </header>

        <!-- Form -->
        <main class="p-8">
          <!-- Notificaciones Flash -->
          <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-900/80 border border-green-400/50 text-green-200 rounded-xl backdrop-blur-sm">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ $page.props.flash.success }}</span>
            </div>
          </div>
          
          <div v-if="$page.props.flash?.error" class="mb-6 p-4 bg-red-900/80 border border-red-400/50 text-red-200 rounded-xl backdrop-blur-sm">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ $page.props.flash.error }}</span>
            </div>
          </div>

          <!-- Mensaje de Éxito Temporal -->
          <div v-if="showSuccessMessage" class="mb-6 p-4 bg-green-900/80 border border-green-400/50 text-green-200 rounded-xl backdrop-blur-sm animate-pulse">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ successMessage }}</span>
            </div>
          </div>

          <div class="max-w-4xl mx-auto bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 p-8 shadow-2xl">
            <form @submit.prevent="submit" class="space-y-8">
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Nombre <span class="text-red-400">*</span></label>
                  <input 
                    v-model="form.nombre" 
                    @blur="verificarCampoYDuplicados('nombre')"
                    @input="limpiarError('duplicados')"
                    type="text" 
                    required 
                    :class="[
                      'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                      form.errors.nombre || errores.duplicados ? 'border-red-500' : 'border-slate-600/50'
                    ]"
                  />
                  <div v-if="form.errors.nombre" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.nombre }}
                  </div>
                  <div v-if="errores.duplicados" class="mt-2 text-red-400 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>El cliente con este nombre ya fue registrado</span>
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Apellido <span class="text-red-400">*</span></label>
                  <input 
                    v-model="form.apellido" 
                    type="text" 
                    required
                    :class="[
                      'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                      form.errors.apellido ? 'border-red-500' : 'border-slate-600/50'
                    ]"
                  />
                  <div v-if="form.errors.apellido" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.apellido }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">CI <span class="text-red-400">*</span></label>
                  <input 
                    v-model="form.ci" 
                    @blur="verificarCampoYDuplicados('ci')"
                    @input="validarSoloNumeros('ci', $event); limpiarError('ci'); limpiarError('duplicados')"
                    type="text" 
                    required
                    :class="[
                      'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                      form.errors.ci || errores.ci || errores.duplicados ? 'border-red-500' : 'border-slate-600/50'
                    ]"
                  />
                  <div v-if="form.errors.ci" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.ci }}
                  </div>
                  <div v-if="errores.ci" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errores.ci }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Teléfono <span class="text-red-400">*</span></label>
                  <input 
                    v-model="form.telefono" 
                    type="text" 
                    required
                    :class="[
                      'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                      form.errors.telefono ? 'border-red-500' : 'border-slate-600/50'
                    ]"
                  />
                  <div v-if="form.errors.telefono" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.telefono }}
                  </div>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm text-slate-300 mb-2">Correo Electrónico <span class="text-red-400">*</span></label>
                  <input 
                    v-model="form.correoElectronico" 
                    type="email" 
                    required
                    :class="[
                      'w-full px-4 py-3 bg-slate-800/50 border rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent',
                      form.errors.correoElectronico ? 'border-red-500' : 'border-slate-600/50'
                    ]"
                  />
                  <div v-if="form.errors.correoElectronico" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.correoElectronico }}
                  </div>
                </div>
              </div>

              <!-- Mensaje de error general -->
              <div v-if="errores.general" class="bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-center text-red-400">
                  <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errores.general }}
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-6 border-t border-white/10">
                <Link :href="route('clientes')" class="px-6 py-3 bg-slate-700/60 text-white rounded-xl hover:bg-slate-700 transition-all">Cancelar</Link>
                <button 
                  :disabled="form.processing" 
                  class="px-6 py-3 bg-gradient-to-r from-amber-500 to-pink-600 text-white rounded-xl hover:from-amber-600 hover:to-pink-700 transition-all disabled:opacity-50"
                >
                  {{ form.processing ? 'Actualizando...' : 'Actualizar Cliente' }}
                </button>
              </div>
            </form>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
