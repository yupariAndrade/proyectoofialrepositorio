<script setup>
import { ref } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const $page = usePage()

// Ya no necesitamos recibir usuarios como prop
const form = useForm({ 
  nombre: '', 
  apellido: '', 
  ci: '', 
  telefono: '', 
  correoElectronico: '' 
  // idUsuario se asignará automáticamente en el backend
})

const showSuccessMessage = ref(false)
const successMessage = ref('')

const submit = () => {
  form.post(route('clientes.store'), { 
    preserveScroll: true, 
    onSuccess: () => {
      showSuccessMessage.value = true
      successMessage.value = 'Cliente registrado exitosamente'
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
      <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black relative overflow-hidden">
        <!-- Efectos de fondo animados -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-900/10 via-gray-800/10 to-black/20 animate-pulse"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-900/15 via-transparent to-transparent"></div>
        <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-gray-800/15 via-transparent to-transparent"></div>
        <!-- Header -->
        <header class="relative bg-gradient-to-r from-black/90 via-gray-900/90 to-black/90 backdrop-blur-xl border-b border-red-600/30 px-8 py-6 shadow-2xl">
          <!-- Efecto cristal -->
          <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 via-gray-600/10 to-black/10 backdrop-blur-sm"></div>
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
          <div class="relative z-10 flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-red-500 via-white to-gray-300 bg-clip-text text-transparent mb-2"> + Registrar Nuevo Cliente</h1>
              <p class="text-gray-300">Ingrese los datos del Cliente</p>
            </div>
            <Link :href="route('clientes')" class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-3 rounded-xl font-medium border border-gray-600/50 hover:from-gray-800 hover:to-black transition-all backdrop-blur-sm"> ⬅️ Volver</Link>
          </div>
        </header>

        <!-- Form -->
        <main class="relative z-10 p-8">
          <!-- Notificaciones Flash -->
          <div v-if="$page.props.flash?.success" class="mb-6 relative p-4 bg-gradient-to-r from-green-900/90 to-emerald-900/90 border border-green-400/30 text-white rounded-xl backdrop-blur-xl shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-xl"></div>
            <div class="relative z-10 flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              </div>
              <span class="font-semibold text-lg">{{ $page.props.flash.success }}</span>
            </div>
          </div>
          
          <div v-if="$page.props.flash?.error" class="mb-6 relative p-4 bg-gradient-to-r from-red-900/90 to-rose-900/90 border border-red-400/30 text-white rounded-xl backdrop-blur-xl shadow-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-xl"></div>
            <div class="relative z-10 flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-rose-400 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
              </div>
              <span class="font-semibold text-lg">{{ $page.props.flash.error }}</span>
            </div>
          </div>

          <!-- Mensaje de Éxito Temporal -->
          <div v-if="showSuccessMessage" class="mb-6 relative p-4 bg-gradient-to-r from-green-900/90 to-emerald-900/90 border border-green-400/30 text-white rounded-xl backdrop-blur-xl shadow-2xl animate-pulse">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-xl"></div>
            <div class="relative z-10 flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
              </div>
              <span class="font-semibold text-lg">{{ successMessage }}</span>
            </div>
          </div>

          <div class="max-w-4xl mx-auto relative bg-gradient-to-br from-black/60 via-gray-900/60 to-black/60 backdrop-blur-xl rounded-2xl border border-red-500/30 p-8 shadow-2xl">
            <!-- Efecto cristal -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 via-gray-600/10 to-black/10 rounded-2xl"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent rounded-2xl"></div>
            <form @submit.prevent="submit" class="relative z-10 space-y-8">
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Nombre <span class="text-red-400">*</span>
                  </label>
                  <input 
                    v-model="form.nombre" 
                    type="text" 
                    required 
                    maxlength="50"
                    placeholder="Ingrese el nombre del cliente... "
                    :class="[
                      'w-full px-4 py-3 bg-black/40 border rounded-xl text-white focus:ring-2 focus:ring-red-500 focus:border-transparent backdrop-blur-sm placeholder:text-gray-400',
                      form.errors.nombre ? 'border-red-500' : 'border-gray-600'
                    ]"
                  />
                  <div v-if="form.errors.nombre" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.nombre }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Apellido <span class="text-red-400">*</span>
                  </label>
                  <input 
                    v-model="form.apellido" 
                    type="text" 
                    required
                    maxlength="50"
                    placeholder="Ingrese el apellido del cliente... "
                    :class="[
                      'w-full px-4 py-3 bg-black/40 border rounded-xl text-white focus:ring-2 focus:ring-red-500 focus:border-transparent backdrop-blur-sm placeholder:text-gray-400',
                      form.errors.apellido ? 'border-red-500' : 'border-gray-600'
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
                  <label class="block text-sm text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v11a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    Cédula de Identidad <span class="text-red-400">*</span>
                  </label>
                  <input 
                    v-model="form.ci" 
                    type="text" 
                    required
                    maxlength="20"
                    placeholder="Ingrese CI..."
                    :class="[
                      'w-full px-4 py-3 bg-black/40 border rounded-xl text-white focus:ring-2 focus:ring-red-500 focus:border-transparent backdrop-blur-sm placeholder:text-gray-400',
                      form.errors.ci ? 'border-red-500' : 'border-gray-600'
                    ]"
                  />
                  <div v-if="form.errors.ci" class="text-red-400 text-sm mt-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ form.errors.ci }}
                  </div>
                </div>
                <div>
                  <label class="block text-sm text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Teléfono <span class="text-red-400">*</span>
                  </label>
                  <input 
                    v-model="form.telefono" 
                    type="tel" 
                    required
                    maxlength="20"
                    placeholder="Ingrese nr. Telefono..."
                    :class="[
                      'w-full px-4 py-3 bg-black/40 border rounded-xl text-white focus:ring-2 focus:ring-red-500 focus:border-transparent backdrop-blur-sm placeholder:text-gray-400',
                      form.errors.telefono ? 'border-red-500' : 'border-gray-600'
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
                  <label class="block text-sm text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Correo Electrónico <span class="text-red-400">*</span>
                  </label>
                  <input 
                    v-model="form.correoElectronico" 
                    type="email" 
                    required
                    maxlength="150"
                    placeholder="Ingrese su em@il..."
                    :class="[
                      'w-full px-4 py-3 bg-black/40 border rounded-xl text-white focus:ring-2 focus:ring-red-500 focus:border-transparent backdrop-blur-sm placeholder:text-gray-400',
                      form.errors.correoElectronico ? 'border-red-500' : 'border-gray-600'
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

              <div class="flex justify-end space-x-3 pt-6 border-t border-red-500/30">
                <Link :href="route('clientes')" class="px-6 py-3 bg-gradient-to-r from-gray-700 to-gray-800 text-white rounded-xl hover:from-gray-800 hover:to-black transition-all backdrop-blur-sm border border-gray-600/50">✖️ Cancelar</Link>
                <button 
                  :disabled="form.processing" 
                  class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all disabled:opacity-50 backdrop-blur-sm border border-red-500/50 shadow-lg flex items-center gap-2"
                >
                  <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  {{ form.processing ? 'Creando...' : ' 💾 Guardar' }}
                </button>
              </div>
            </form>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
