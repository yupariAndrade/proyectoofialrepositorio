<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({
  trabajo: { type: Object, required: true },
  clientes: { type: Array, required: true },
  servicios: { type: Array, required: true },
  usuarios: { type: Array, required: true },
  estados: { type: Array, required: true }
})

const form = useForm({
  idCliente: String(props.trabajo.idCliente || ''),
  idServicio: String(props.trabajo.idServicio || ''),
  idUsuario: String(props.trabajo.idUsuario || ''),
  fechaRegistro: props.trabajo.fechaRegistro || '',
  fechaEntrega: props.trabajo.fechaEntrega || '',
  idEstado: String(props.trabajo.idEstado || '')
})

const submit = () => { form.put(route('trabajos.update', props.trabajo.id)) }
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
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">✏️ Editar Trabajo</h1>
              <p class="text-slate-300">Modifica la información del trabajo</p>
            </div>
            <Link :href="route('trabajos')" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
              <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
              Volver
            </Link>
          </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-8">
          <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8">
              <form @submit.prevent="submit" class="space-y-8">
                <!-- Cliente y Servicio -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="idCliente" class="block text-sm font-medium text-slate-300 mb-2">Cliente *</label>
                    <select v-model="form.idCliente" id="idCliente" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white">
                      <option value="">Seleccionar cliente</option>
                      <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">{{ cliente.nombre }} {{ cliente.apellido || '' }}</option>
                    </select>
                    <div v-if="form.errors.idCliente" class="text-red-400 text-sm mt-1">{{ form.errors.idCliente }}</div>
                  </div>

                  <div>
                    <label for="idServicio" class="block text-sm font-medium text-slate-300 mb-2">Servicio *</label>
                    <select v-model="form.idServicio" id="idServicio" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white">
                      <option value="">Seleccionar servicio</option>
                      <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">{{ servicio.nombreServicio }}</option>
                    </select>
                    <div v-if="form.errors.idServicio" class="text-red-400 text-sm mt-1">{{ form.errors.idServicio }}</div>
                  </div>
                </div>

                <!-- Usuario y Estado -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="idUsuario" class="block text-sm font-medium text-slate-300 mb-2">Usuario Asignado *</label>
                    <select v-model="form.idUsuario" id="idUsuario" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white">
                      <option value="">Seleccionar usuario</option>
                      <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">{{ usuario.nombre }}</option>
                    </select>
                    <div v-if="form.errors.idUsuario" class="text-red-400 text-sm mt-1">{{ form.errors.idUsuario }}</div>
                  </div>

                  <div>
                    <label for="idEstado" class="block text-sm font-medium text-slate-300 mb-2">Estado *</label>
                    <select v-model="form.idEstado" id="idEstado" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white">
                      <option value="">Seleccionar estado</option>
                      <option v-for="estado in estados" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
                    </select>
                    <div v-if="form.errors.idEstado" class="text-red-400 text-sm mt-1">{{ form.errors.idEstado }}</div>
                  </div>
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="fechaRegistro" class="block text-sm font-medium text-slate-300 mb-2">Fecha de Registro *</label>
                    <input v-model="form.fechaRegistro" type="date" id="fechaRegistro" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white" />
                    <div v-if="form.errors.fechaRegistro" class="text-red-400 text-sm mt-1">{{ form.errors.fechaRegistro }}</div>
                  </div>
                  <div>
                    <label for="fechaEntrega" class="block text-sm font-medium text-slate-300 mb-2">Fecha de Entrega (Opcional)</label>
                    <input v-model="form.fechaEntrega" type="date" id="fechaEntrega" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white" />
                    <div v-if="form.errors.fechaEntrega" class="text-red-400 text-sm mt-1">{{ form.errors.fechaEntrega }}</div>
                  </div>
                </div>

                <!-- Acciones -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-white/10">
                  <Link :href="route('trabajos')" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">Cancelar</Link>
                  <button type="submit" :disabled="form.processing" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    {{ form.processing ? 'Actualizando...' : 'Actualizar Trabajo' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
