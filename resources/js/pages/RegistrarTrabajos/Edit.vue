<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              ✏️ Editar Trabajo
            </h2>
            <p class="text-slate-300">Modificar trabajo existente</p>
          </div>
          <!-- Botón Volver -->
          <button 
            @click="volverAIndex"
            class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-md font-medium transition-colors duration-200 flex items-center"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver
          </button>
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
          
          <!-- Notificaciones -->
          <div v-if="showSuccessMessage" class="mb-6 p-4 bg-green-900/80 border border-green-400/50 text-green-200 rounded-xl backdrop-blur-sm animate-fade-in">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ messageText }}</span>
            </div>          </div>
          
          <div v-if="showErrorMessage" class="mb-6 p-4 bg-red-900/80 border border-red-400/50 text-red-200 rounded-xl backdrop-blur-sm animate-fade-in">
            <div class="flex items-center gap-3">
              <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">{{ messageText }}</span>
            </div>
          </div>
          <div class="bg-[#0b1a2d] shadow-2xl sm:rounded-lg border border-white/10 overflow-hidden">
            <div class="p-6">
              <h2 class="text-2xl font-bold mb-6 text-white">Editar Trabajo</h2>
              
              <form @submit.prevent="submitForm" class="space-y-8">

                <!-- Sección de Historial del Trabajo -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border-l-4 border-blue-500">
                  <h3 class="text-lg font-semibold mb-4 text-white">📋 Historial del Trabajo Actual</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-white/90">
                    <div>
                      <span class="font-medium">Cliente:</span>
                      <p>{{ trabajoOriginal.cliente?.nombre }} {{ trabajoOriginal.cliente?.apellido }}</p>
                    </div>
                                         <div>
                       <span class="font-medium">Servicios:</span>
                       <p>{{ trabajoOriginal.detallesTrabajo?.length || 0 }} servicio(s)</p>
                     </div>
                    <div>
                      <span class="font-medium">Total Original:</span>
                      <p>{{ trabajoOriginal.totalTrabajo || 0 }} Bs</p>
                    </div>
                  </div>
                </div>

                <!-- Sección Cliente -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">Información del Cliente</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Cliente *</label>
                      <select 
                        v-model="originalForm.cliente" 
                        @change="onClienteChange"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                        required
                      >
                        <option value="">Seleccionar cliente</option>
                        <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                          {{ cliente.nombre }} {{ cliente.apellido }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Número de Referencia</label>
                      <input 
                        type="text" 
                        :value="clienteSeleccionado?.telefono || ''" 
                        disabled
                        class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                      />
                    </div>
                  </div>
                </div>

                <!-- Sección Servicios -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <div class="flex justify-between items-center mb-4">
                    <div>
                      <h3 class="text-lg font-semibold text-white">Servicios</h3>
                      <p class="text-sm text-cyan-300 mt-1">
                        💡 El descuento se aplica al subtotal del servicio. Ej: 200 tarjetas × 5 Bs = 1,000 Bs, si quieres cobrar 800 Bs, ingresa 200 Bs de descuento
                      </p>
                    </div>
                    <button 
                      type="button"
                      @click="agregarOtroServicio"
                      class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                      ➕ Agregar Otro Servicio
                    </button>
                  </div>
                  
                  <div v-for="(servicio, index) in form.servicios" :key="`servicio-${index}-${form.servicios.length}`" class="mb-6 p-4 bg-[#0a192f]/50 rounded-lg border border-white/10">
                    <div class="flex justify-between items-center mb-4">
                      <h4 class="text-md font-medium text-white">Servicio {{ index + 1 }}</h4>
                      <button 
                        v-if="form.servicios.length >= 2"
                        type="button"
                        @click="eliminarServicio(index)"
                        class="text-red-400 hover:text-red-300 text-sm"
                      >
                        🗑️ Eliminar
                      </button>
                    </div>
                    
                    <!-- Fila única: Servicio + Precio + Cantidad + Descuento + Subtotal -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Tipo de Servicio *</label>
                        <select 
                          v-model="servicio.idServicio"
                          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                          required
                        >
                          <option value="">Seleccionar servicio</option>
                          <option v-for="serv in servicios" :key="serv.id" :value="serv.id">
                            {{ serv.nombreServicio }}
                          </option>
                        </select>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Precio Referencial</label>
                        <input 
                          type="text" 
                          :value="obtenerServicioInfo(servicio.idServicio)?.precioReferencial ? (obtenerServicioInfo(servicio.idServicio).precioReferencial + ' Bs') : ''" 
                          disabled
                          class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Cantidad *</label>
                        <input 
                          v-model="servicio.cantidad"
                          type="number" 
                          min="1"
                          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                          required
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Descuento (Bs.)</label>
                        <input 
                          v-model="servicio.descuento"
                          type="number" 
                          min="0"
                          :max="calcularSubtotalBruto(servicio)"
                          step="0.01"
                          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                          placeholder="0.00"
                          @input="validarDescuento(servicio)"
                        />
                        <p v-if="servicio.descuento >= calcularSubtotalBruto(servicio)" class="text-red-400 text-xs mt-1">
                          El descuento no puede ser mayor o igual al subtotal bruto
                        </p>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Subtotal</label>
                        <input 
                          type="text" 
                          :value="getSubtotalDisplay(servicio)" 
                          disabled
                          class="w-full bg-[#0a192f]/80 text-white border border-white/20 rounded-md shadow-md"
                        />
                      </div>
                    </div>
                    
                    <!-- Detalles específicos del servicio -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/90">
                      <div>
                        <label class="block text-sm font-medium mb-2">Tamaño</label>
                        <input v-model="servicio.detalles.tamano" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                      </div>
                      <div>
                        <label class="block text-sm font-medium mb-2">Color</label>
                        <input v-model="servicio.detalles.color" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                      </div>
                      <div>
                        <label class="block text-sm font-medium mb-2">Modelo</label>
                        <input v-model="servicio.detalles.modelo" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                      </div>
                      <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Descripción</label>
                        <textarea v-model="servicio.detalles.descripcion" rows="3" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Sección Asignación de Responsables -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">👥 Asignación de Responsables</h3>
                  <p class="text-sm text-gray-400 mb-4">💡 <strong>Opcional:</strong> Puedes asignar, cambiar o quitar el responsable del trabajo.</p>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium mb-2 text-white">
                        Responsable Principal 
                        <span class="text-gray-400 text-xs">(Opcional)</span>
                      </label>
                      <select 
                        v-model="originalForm.idResponsable"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                      >
                        <option value="">Sin responsable asignado</option>
                        <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">
                          {{ usuario.nombre }} {{ usuario.apellidoPaterno }} {{ usuario.apellidoMaterno }} - {{ usuario.rol?.nombre }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2 text-white">Información del Responsable</label>
                      <div v-if="usuarioSeleccionado" class="p-3 bg-[#0a192f]/50 border border-white/20 rounded-lg">
                        <div class="flex items-center space-x-3">
                          <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ usuarioSeleccionado.nombre.charAt(0) }}</span>
                          </div>
                          <div>
                            <p class="font-medium text-white">
                              {{ usuarioSeleccionado.nombre }} {{ usuarioSeleccionado.apellidoPaterno }} {{ usuarioSeleccionado.apellidoMaterno }}
                            </p>
                            <p class="text-sm text-cyan-400">{{ usuarioSeleccionado.rol?.nombre }}</p>
                          </div>
                        </div>
                      </div>
                      <div v-else class="p-3 bg-[#0a192f]/30 border border-white/10 rounded-lg text-center">
                        <p class="text-white/50 text-sm">No hay responsable asignado</p>
                        <p class="text-gray-500 text-xs mt-1">Se puede asignar o cambiar</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Sección Información del Trabajo -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">Información del Trabajo</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-white/90">
                    <div>
                      <label class="block text-sm font-medium mb-2">Fecha de Registro</label>
                      <input type="date" :value="fechaRegistro" disabled class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Fecha de Entrega *</label>
                      <input v-model="originalForm.fechaEntrega" type="date" :min="fechaRegistro" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" required />
                    </div>
                    <!-- ✅ ARQUITECTURA MVC - Estado del trabajo se maneja desde la lista, no desde el formulario -->
                  </div>
                </div>

                <!-- Sección Pago -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">💰 Información del Pago</h3>
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Total</label>
                      <input 
                        type="text" 
                        :value="totalCalculado + ' Bs'" 
                        disabled
                        class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">A Cuenta *</label>
                      <input 
                        type="number" 
                        v-model="originalForm.aCuenta"
                        min="0"
                        step="0.01"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                        placeholder="0.00"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Saldo</label>
                      <input 
                        type="text" 
                        :value="saldoCalculado + ' Bs'" 
                        disabled
                        class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Estado del Pago</label>
                      <select v-model="originalForm.idEstadoPago" class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400">
                        <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 pt-6">
                  <button 
                    type="button"
                    @click="volverAIndex"
                    class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded"
                  >
                    Cancelar
                  </button>
                  
                  <button 
                    type="submit" 
                    :disabled="isSubmitting"
                    class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded"
                  >
                    {{ isSubmitting ? 'Actualizando...' : 'Actualizar Trabajo' }}
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
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { Link, useForm, router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Obtener la página actual
const page = usePage()

// Computed para el mensaje de éxito
const successMessage = computed(() => {
  return page.props.flash?.success || null
})

// Props
const props = defineProps({
  trabajo: Object,
  clientes: Array,
  servicios: Array,
  usuarios: Array,
  estadosTrabajo: Array,
  estadosPago: Array,
})

// Estado del formulario - Inicializar con datos reales
const originalForm = useForm({
  cliente: '',
  servicios: [
    {
      id: Date.now() + Math.random(), // ID único para reactividad
      idServicio: '',
      cantidad: 1,
      descuento: 0,
      detalles: {
        tamano: '',
        color: '',
        modelo: '',
        descripcion: '',
      }
    }
  ],
  fechaEntrega: '',
  idResponsable: '',
  aCuenta: 0,
  idEstadoPago: 2,
})

// Crear una versión reactiva del form para la UI que se sincronice con originalForm
const form = ref(originalForm)

// Watcher para sincronizar cambios del form de Inertia con la versión reactiva
watch(() => originalForm.servicios, (newServicios) => {
  console.log('🔄 Sincronizando servicios en Edit.vue:', newServicios)
  form.value = { ...originalForm }
}, { deep: true })

// Variables reactivas
const isSubmitting = ref(false)
const fechaRegistro = computed(() => {
  const fecha = props.trabajo?.fechaRegistro
  console.log('🔄 Edit.vue: fechaRegistro recibida:', fecha)
  console.log('🔄 Edit.vue: tipo de fechaRegistro:', typeof fecha)
  
  if (!fecha) {
    console.log('⚠️ Edit.vue: No hay fechaRegistro, usando fecha actual')
    return new Date().toISOString().split('T')[0]
  }
  
  // Si la fecha viene en formato YYYY-MM-DD, usarla directamente
  if (typeof fecha === 'string' && fecha.match(/^\d{4}-\d{2}-\d{2}$/)) {
    console.log('✅ Edit.vue: fechaRegistro en formato correcto:', fecha)
    return fecha
  }
  
  // Si viene en otro formato, convertirla
  const fechaConvertida = new Date(fecha).toISOString().split('T')[0]
  console.log('🔄 Edit.vue: fechaRegistro convertida:', fechaConvertida)
  return fechaConvertida
})
const showSuccessMessage = ref(false)
const showErrorMessage = ref(false)
const messageText = ref('')

// ✅ ARQUITECTURA MVC - Los totales se calculan en el backend
const totalCalculado = ref(0)
const saldoCalculado = ref(0)

// Debug: Log de datos recibidos
// ✅ ARQUITECTURA MVC - Función para cargar datos del trabajo
const cargarDatosTrabajo = async (trabajoData) => {
  if (!trabajoData) return
  
  console.log('🔄 Cargando datos del trabajo:', trabajoData)
  
  // Actualizar el formulario
  console.log('🔄 Edit.vue: Cargando datos del cliente:', trabajoData.idCliente)
  console.log('🔄 Edit.vue: fechaEntrega recibida:', trabajoData.fechaEntrega)
  console.log('🔄 Edit.vue: tipo de fechaEntrega:', typeof trabajoData.fechaEntrega)
  
  originalForm.cliente = trabajoData.idCliente || ''
  originalForm.idResponsable = trabajoData.idResponsable || ''
  
  // Manejar fechaEntrega con conversión de formato si es necesario
  if (trabajoData.fechaEntrega) {
    if (typeof trabajoData.fechaEntrega === 'string' && trabajoData.fechaEntrega.match(/^\d{4}-\d{2}-\d{2}$/)) {
      originalForm.fechaEntrega = trabajoData.fechaEntrega
      console.log('✅ Edit.vue: fechaEntrega en formato correcto:', trabajoData.fechaEntrega)
    } else {
      originalForm.fechaEntrega = new Date(trabajoData.fechaEntrega).toISOString().split('T')[0]
      console.log('🔄 Edit.vue: fechaEntrega convertida:', originalForm.fechaEntrega)
    }
  } else {
    originalForm.fechaEntrega = ''
    console.log('⚠️ Edit.vue: No hay fechaEntrega')
  }
  
  console.log('✅ Edit.vue: originalForm.cliente actualizado a:', originalForm.cliente)
  console.log('✅ Edit.vue: originalForm.fechaEntrega actualizado a:', originalForm.fechaEntrega)
    // ✅ ARQUITECTURA MVC - Estado se maneja desde la lista, no desde el formulario
  originalForm.aCuenta = trabajoData.aCuenta || 0
  originalForm.idEstadoPago = trabajoData.idEstadoPago || 2
  
  // ✅ ARQUITECTURA MVC - Usar datos calculados del backend
  totalCalculado.value = trabajoData.total || 0
  saldoCalculado.value = trabajoData.saldo || 0

  // ✅ ARQUITECTURA MVC - Cargar servicios desde detallesTrabajo del backend
  if (trabajoData.detallesTrabajo && trabajoData.detallesTrabajo.length > 0) {
    originalForm.servicios = trabajoData.detallesTrabajo.map((detalle, index) => ({
      id: Date.now() + Math.random() + index, // ID único para reactividad
      idServicio: detalle.idServicio || '',
      cantidad: detalle.cantidad || 1,
      descuento: detalle.descuento || 0,
      detalles: {
        tamano: detalle.tamano || '',
        color: detalle.color || '',
        modelo: detalle.modelo || '',
        descripcion: detalle.descripcion || '',
      }
    }))
    
    console.log('✅ Servicios cargados desde backend:', originalForm.servicios)
  } else {
    console.log('❌ No hay detallesTrabajo en el trabajo:', trabajoData)
  }
  
  console.log('✅ Formulario actualizado:', originalForm)
}

onMounted(() => {
  console.log('🚀 COMPONENTE MONTADO - Datos iniciales:')
  console.log('🚀 Props trabajo completo:', props.trabajo)
  
  // Cargar datos iniciales si ya están disponibles
  if (props.trabajo) {
    cargarDatosTrabajo(props.trabajo)
  }
})

// Watcher para actualizar el formulario cuando los datos lleguen
watch(() => props.trabajo, async (newTrabajo) => {
  console.log('🔄 Watcher ejecutado - Props trabajo:', props.trabajo)
  console.log('🔄 Watcher ejecutado - NewTrabajo:', newTrabajo)
  
  if (newTrabajo) {
    console.log('🔄 Watcher ejecutado - Actualizando formulario con datos:', newTrabajo)
    
    // ✅ ARQUITECTURA MVC - Usar función centralizada para cargar datos
    await cargarDatosTrabajo(newTrabajo)
    
    // Esperar a que Vue procese los cambios
    await nextTick()
    
    console.log('✅ Formulario actualizado después de nextTick:', form)
    
    // ✅ Calcular totales desde el backend
    calcularTotalesDesdeBackend()
  }
}, { immediate: true, deep: true })

// Watcher para detectar cambios en los servicios y recalcular totales
watch(() => originalForm.servicios, (newServicios) => {
  console.log('🔄 Servicios cambiaron:', newServicios)
  // Recalcular totales desde el backend
  setTimeout(() => {
    calcularTotalesDesdeBackend()
  }, 100)
}, { deep: true })

// Computed properties
const trabajoOriginal = computed(() => {
  return props.trabajo
})

const clienteSeleccionado = computed(() => {
  if (!props.clientes || !originalForm.cliente) return null
  return props.clientes.find(c => c.id === originalForm.cliente) || null
})

const usuarioSeleccionado = computed(() => {
  if (!props.usuarios || !originalForm.idResponsable) return null
  return props.usuarios.find(u => u.id === originalForm.idResponsable) || null
})

// ✅ ARQUITECTURA MVC - Función para calcular totales desde el backend
const calcularTotalesDesdeBackend = async () => {
  try {
    const response = await axios.post('/api/trabajos/calcular-totales', {
      servicios: originalForm.servicios,
      aCuenta: originalForm.aCuenta || 0
    })
    
    totalCalculado.value = response.data.total
    saldoCalculado.value = response.data.saldo
    
    console.log('✅ Totales calculados desde backend:', {
      total: response.data.total,
      saldo: response.data.saldo
    })
  } catch (error) {
    console.error('❌ Error calculando totales desde backend:', error)
    // Fallback: calcular localmente si el backend falla
    let total = 0
    originalForm.servicios.forEach(servicio => {
      if (servicio.idServicio) {
        const subtotalBruto = calcularSubtotalBruto(servicio)
        const descuento = parseFloat(servicio.descuento) || 0
        total += subtotalBruto - descuento
      }
    })
    totalCalculado.value = total
    saldoCalculado.value = Math.max(0, total - (originalForm.aCuenta || 0))
  }
}

// Watcher para recalcular cuando cambie aCuenta

watch(() => originalForm.aCuenta, (newACuenta) => {
  // ✅ ARQUITECTURA MVC - Recalcular saldo cuando cambie aCuenta
  saldoCalculado.value = Math.max(0, totalCalculado.value - (newACuenta || 0))
  console.log('🔄 Saldo recalculado:', {
    total: totalCalculado.value,
    aCuenta: newACuenta,
    saldo: saldoCalculado.value
  })
})

// Métodos
const onClienteChange = () => {
  // Resetear campos relacionados
  originalForm.servicios.forEach(servicio => {
    servicio.detalles.tamano = ''
    servicio.detalles.color = ''
    servicio.detalles.modelo = ''
    servicio.detalles.descripcion = ''
  })
}

const agregarOtroServicio = () => {
  console.log('🔄 agregarOtroServicio ejecutándose en Edit.vue...')
  console.log('🔄 Servicios actuales:', originalForm.servicios.length)
  console.log('🔄 Servicios actuales (datos):', originalForm.servicios)
  
  const nuevoServicio = {
    id: Date.now() + Math.random(), // ID único para reactividad
    idServicio: '',
    cantidad: 1,
    descuento: 0,
    detalles: {
      tamano: '',
      color: '',
      modelo: '',
      descripcion: '',
    }
  }
  
  // Crear nuevo array de servicios
  const nuevosServicios = [...originalForm.servicios, nuevoServicio]
  
  // Actualizar directamente el array de servicios
  originalForm.servicios = nuevosServicios
  
  console.log('✅ Nuevo servicio agregado en Edit.vue. Total servicios:', originalForm.servicios.length)
  console.log('✅ Servicios actualizados:', originalForm.servicios)
  
  // Forzar actualización de la versión reactiva con nextTick
  nextTick(() => {
    form.value = { ...originalForm }
    console.log('✅ Form reactivo actualizado en Edit.vue')
  })
}

const eliminarServicio = (index) => {
  if (originalForm.servicios.length > 1) {
    const nuevosServicios = originalForm.servicios.filter((_, i) => i !== index)
    originalForm.servicios = nuevosServicios
    console.log('✅ Servicio eliminado en Edit.vue. Total servicios:', originalForm.servicios.length)
    
    // Forzar actualización de la versión reactiva
    form.value = { ...originalForm }
  }
}

const obtenerServicioInfo = (idServicio) => {
  return props.servicios.find(s => s.id === idServicio)
}

// ✅ ARQUITECTURA MVC - Los cálculos vienen del backend
const subtotalesCache = ref({})

const calcularSubtotalBruto = (servicio) => {
  const servicioInfo = obtenerServicioInfo(servicio.idServicio)
  if (!servicioInfo) return 0
  const precioOriginal = parseFloat(servicioInfo.precioReferencial)
  const cantidad = parseInt(servicio.cantidad) || 0
  return precioOriginal * cantidad
}

const getSubtotalDisplay = (servicio) => {
  const subtotalBruto = calcularSubtotalBruto(servicio)
  const descuento = parseFloat(servicio.descuento) || 0
  const subtotal = subtotalBruto - descuento
  return subtotal.toFixed(2) + ' Bs'
}

const validarDescuento = (servicio) => {
  const subtotalBruto = calcularSubtotalBruto(servicio)
  const descuento = parseFloat(servicio.descuento) || 0
  
  // Validar que el descuento no sea mayor al subtotal bruto
  if (descuento > subtotalBruto) {
    servicio.descuento = subtotalBruto
  }
  
  console.log('🔍 Descuento validado:', {
    servicio: obtenerServicioInfo(servicio.idServicio)?.nombreServicio,
    descuento: servicio.descuento,
    subtotalBruto: subtotalBruto
  })
}

const submitForm = () => {
  // Validación del lado del cliente
  if (!originalForm.cliente) {
    alert('Por favor selecciona un cliente')
    return
  }
  
  if (!originalForm.servicios.some(s => s.idServicio)) {
    alert('Por favor selecciona al menos un servicio')
    return
  }
  
  if (!originalForm.fechaEntrega) {
    alert('Por favor selecciona una fecha de entrega')
    return
  }
  
  // El responsable es opcional, no se valida
  
  // Validar campo aCuenta
  if (originalForm.aCuenta < 0) {
    alert('El monto a cuenta no puede ser negativo')
    return
  }
  
  isSubmitting.value = true
  
  // Limpiar el campo 'id' temporal antes de enviar
  const serviciosLimpios = originalForm.servicios.map(servicio => {
    const { id, ...servicioSinId } = servicio
    return servicioSinId
  })
  
  // Crear una copia del form con servicios limpios
  const formData = {
    ...originalForm.data(),
    servicios: serviciosLimpios
  }
  
  // Debug: verificar los datos del trabajo
  console.log('🔍 Props del trabajo:', props.trabajo)
  console.log('🔍 Slug del trabajo:', props.trabajo.slug)
  console.log('📦 Servicios limpios:', serviciosLimpios)
  
  // Usar fetch directamente para evitar problemas con axios
  const slug = props.trabajo.slug
  const url = 'http://127.0.0.1:8000/registrar-trabajos/' + slug
  console.log('🔗 Slug extraído:', slug)
  console.log('🔗 URL construida:', url)
  
  console.log('🚀 Enviando petición PUT a:', url)
  console.log('📦 Datos del formulario:', formData)
  
  // Usar form.put de Inertia con datos limpios
  originalForm.transform(() => formData).put(`/registrar-trabajos/${slug}`, {
    onSuccess: () => {
      isSubmitting.value = false
      console.log('Trabajo actualizado exitosamente')
      // Mostrar mensaje de éxito
      showMessage('¡Trabajo actualizado correctamente! Los datos han sido editados y guardados exitosamente.')
      // Redirigir inmediatamente al index
      location.replace('/registrar-trabajos')
    },
    onError: (errors) => {
      isSubmitting.value = false
      console.error('Errores del servidor:', errors)
      showMessage('Error al actualizar el trabajo. Por favor, inténtalo de nuevo.', true)
    }
  })
  
}

const showMessage = (message, isError = false) => {
  messageText.value = message
  if (isError) {
    showErrorMessage.value = true
    setTimeout(() => {
      showErrorMessage.value = false
    }, 5000)
  } else {
    showSuccessMessage.value = true
    setTimeout(() => {
      showSuccessMessage.value = false
      // Redirigir después de mostrar el mensaje
      setTimeout(() => {
        window.location.href = route('registrar-trabajos')
      }, 2000)
    }, 3000)
  }
}

const volverAIndex = () => {
  window.location.href = route('registrar-trabajos')
}
</script>
<style scoped>
/* Animación para las notificaciones */
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

/* Estilos adicionales si son necesarios */
</style>

