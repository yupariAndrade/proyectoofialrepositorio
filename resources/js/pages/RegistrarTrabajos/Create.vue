<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              📝 Registrar Nuevo Trabajo
            </h2>
            <p class="text-slate-300">Crear un nuevo trabajo para un cliente</p>
          </div>
        </div>
      </header>

      <!-- Success Message -->
      <div v-if="successMessage" class="px-8 py-4">
        <div class="max-w-7xl mx-auto">
          <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-green-400 font-medium">{{ successMessage }}</span>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          <div class="bg-[#0b1a2d] shadow-2xl sm:rounded-lg border border-white/10 overflow-hidden">
            <div class="p-6">
              <h2 class="text-2xl font-bold mb-6 text-white">Registrar Nuevo Trabajo</h2>
              
              <form @submit.prevent="submitForm" class="space-y-8">

                <!-- Sección de Cliente Existente (si aplica) -->
                <div v-if="clientePreSeleccionado" class="bg-[#0c1d3a]/70 p-6 rounded-lg border-l-4 border-cyan-500">
                  <h3 class="text-lg font-semibold mb-4 text-white">🆕 Agregando Servicio Adicional</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-white/90">
                    <div>
                      <span class="font-medium">Cliente:</span>
                      <p>{{ clientePreSeleccionado.nombre }} {{ clientePreSeleccionado.apellido }}</p>
                    </div>
                    <div>
                      <span class="font-medium">Teléfono:</span>
                      <p>{{ clientePreSeleccionado.telefono }}</p>
                    </div>
                    <div>
                      <span class="font-medium">Servicios Previos:</span>
                      <p>Ver historial completo</p>
                    </div>
                  </div>
                </div>

                <!-- Componentes de las secciones -->
                <ClienteSection 
                  v-model="originalForm" 
                  :clientes="clientes"
                  @cliente-change="onClienteChange"
                />

                <ServicioSection 
                  v-model="form" 
                  :servicios="servicios"
                  @servicio-change="onServicioChange"
                  @servicio-added="onServicioChange"
                  @servicio-removed="onServicioChange"
                  @agregarServicio="handleAgregarServicio"
                  @eliminarServicio="handleEliminarServicio"
                />

                <TrabajoSection 
                  v-model="originalForm" 
                  :usuarios="usuarios"
                  :fecha-registro="fechaRegistro"
                  @trabajo-change="onTrabajoChange"
                />

                <PagoSection 
                  v-model="originalForm"
                  :estados-pago="estadosPago"
                  :total-calculado="totalCalculado"
                  :saldo-calculado="saldoCalculado"
                  :is-loading="isLoading"
                  @pago-change="onPagoChange"
                />

                <!-- Botones de acción -->
                <div class="flex justify-between space-x-4 pt-6">
                  <div class="flex space-x-4">
                    <Link :href="route('registrar-trabajos')" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Cancelar</Link>
                  </div>
                  <button type="submit" :disabled="isSubmitting || !isFormValidReactivo" class="bg-cyan-600 hover:bg-cyan-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded">
                    {{ isSubmitting ? 'Registrando...' : 'Registrar Trabajo' }}
                  </button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </AppShell>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import ClienteSection from './components/ClienteSection.vue'
import ServicioSection from './components/ServicioSection.vue'
import TrabajoSection from './components/TrabajoSection.vue'
import PagoSection from './components/PagoSection.vue'
import { useTrabajoForm } from './composables/useTrabajoForm.js'

const props = defineProps({
  clientes: Array,
  servicios: Array,
  usuarios: Array,
  estadosPago: Array,
  clientePreSeleccionado: Object,
})

// Estado local
const successMessage = ref('')
const isSubmitting = ref(false)

// ✅ Usar el composable con cálculos para UX (validaciones en backend)
const {
  form: originalForm,
  clienteSeleccionado,
  totalCalculado,
  saldoCalculado,
  isLoading,
  isFormValid,
  obtenerServicioInfo,
  onClienteChange,
  onServicioChange,
  agregarOtroServicio,
  eliminarServicio,
  validarDescuento,
  submitForm: originalSubmitForm
} = useTrabajoForm(props)

// Crear una versión reactiva del form para la UI que se sincronice con originalForm
const form = ref(originalForm)

// Watcher para sincronizar cambios del form de Inertia con la versión reactiva
watch(() => originalForm.servicios, (newServicios) => {
  console.log('🔄 Sincronizando servicios en Create.vue:', newServicios)
  form.value = { ...originalForm }
}, { deep: true })

// Watcher para sincronizar cambios de idResponsable
watch(() => originalForm.idResponsable, (newIdResponsable) => {
  console.log('🔄 Sincronizando idResponsable en Create.vue:', newIdResponsable)
  form.value = { ...originalForm }
})

// ✅ ARQUITECTURA MVC CORRECTA
// ✅ CÁLCULOS EN EL BACKEND - El servidor maneja toda la lógica
// ✅ FRONTEND SOLO PRESENTACIÓN - Sin lógica de negocio

// ✅ Funciones simplificadas - solo para UI
const submitForm = () => {
  console.log('submitForm ejecutándose...', originalForm)
  isSubmitting.value = true
  // Usar directamente el form de Inertia
  originalSubmitForm()
  isSubmitting.value = false
}

// ✅ Funciones faltantes que el template necesita
const handleAgregarServicio = () => {
  console.log('🔄 Create.vue: handleAgregarServicio ejecutándose...')
  console.log('🔄 Servicios actuales en originalForm:', originalForm.servicios.length)
  
  // Usar la función del composable
  agregarOtroServicio()
  
  // Sincronizar con la versión reactiva
  form.value = { ...originalForm }
  
  console.log('✅ Create.vue: Servicio agregado. Total servicios:', originalForm.servicios.length)
}

const handleEliminarServicio = (index) => {
  console.log('🔄 Create.vue: handleEliminarServicio ejecutándose para índice:', index)
  
  // Usar la función del composable
  eliminarServicio(index)
  
  // Sincronizar con la versión reactiva
  form.value = { ...originalForm }
  
  console.log('✅ Create.vue: Servicio eliminado. Total servicios:', originalForm.servicios.length)
}

const onTrabajoChange = (trabajoData) => {
  // Función para cambios en la sección de trabajo
  console.log('🔄 Create.vue: Trabajo cambió:', trabajoData)
  console.log('🔄 Create.vue: idResponsable recibido:', trabajoData?.idResponsable)
  if (trabajoData) {
    Object.assign(originalForm, trabajoData)
    console.log('🔄 Create.vue: originalForm.idResponsable actualizado a:', originalForm.idResponsable)
  }
}

const onPagoChange = (pagoData) => {
  // Función para cambios en la sección de pago
  console.log('🔄 Create.vue: Pago cambió:', pagoData)
  console.log('🔄 Create.vue: originalForm.aCuenta antes:', originalForm.aCuenta)
  
  // Actualizar directamente el form de Inertia
  if (pagoData.aCuenta !== undefined) {
    console.log('🔄 Actualizando originalForm.aCuenta de:', originalForm.aCuenta, 'a:', pagoData.aCuenta)
    originalForm.aCuenta = pagoData.aCuenta
    console.log('✅ Create.vue: originalForm.aCuenta después:', originalForm.aCuenta)
  }
  if (pagoData.idEstadoPago !== undefined) {
    originalForm.idEstadoPago = pagoData.idEstadoPago
  }
}

// Computed para validación del formulario
const isFormValidReactivo = computed(() => {
  return isFormValid.value
})

// Fecha de registro (hoy)
const fechaRegistro = new Date().toISOString().split('T')[0]
</script>
