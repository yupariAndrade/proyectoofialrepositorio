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
            </div>
          </div>
          
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
                        v-model="form.cliente" 
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
                        💡 El descuento se aplica por unidad. Ej: Si una tarjeta cuesta 4.00 Bs. y quieres cobrar 3.20 Bs., ingresa 0.80 Bs. de descuento
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
                  
                  <div v-for="(servicio, index) in form.servicios" :key="index" class="mb-6 p-4 bg-[#0a192f]/50 rounded-lg border border-white/10">
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Tipo de Servicio *</label>
                        <select 
                          v-model="servicio.idServicio"
                          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                          required
                        >
                          <option value="">Seleccionar servicio</option>
                          <option v-for="serv in servicios" :key="serv.id" :value="serv.id">
                            {{ serv.nombreServicio }} - {{ serv.precioReferencial }} Bs
                          </option>
                        </select>
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
                          :max="obtenerServicioInfo(servicio.idServicio)?.precioReferencial || 0"
                          step="0.01"
                          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                          placeholder="0.00"
                          @input="validarDescuento(servicio)"
                        />
                        <p v-if="servicio.descuento >= (obtenerServicioInfo(servicio.idServicio)?.precioReferencial || 0)" class="text-red-400 text-xs mt-1">
                          El descuento no puede ser mayor o igual al precio unitario
                        </p>
                      </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Precio Unitario</label>
                        <input 
                          type="text" 
                          :value="obtenerServicioInfo(servicio.idServicio)?.precioReferencial || ''" 
                          disabled
                          class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Precio Final por Unidad</label>
                        <input 
                          type="text" 
                          :value="calcularPrecioFinalPorUnidad(servicio).toFixed(2) + ' Bs'" 
                          disabled
                          class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                        />
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-white mb-2">Subtotal</label>
                        <input 
                          type="text" 
                          :value="calcularSubtotal(servicio).toFixed(2) + ' Bs'" 
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
                      <div>
                        <label class="block text-sm font-medium mb-2">Tipo de Documento</label>
                        <input v-model="servicio.detalles.tipoDocumento" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                      </div>
                      <div>
                        <label class="block text-sm font-medium mb-2">Tipo de Evento</label>
                        <input v-model="servicio.detalles.tipoEvento" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
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
                        v-model="form.idResponsable"
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
                      <input v-model="form.fechaEntrega" type="date" :min="fechaRegistro" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" required />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Estado del Trabajo</label>
                      <select v-model="form.estadoTrabajo" class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400">
                        <option v-for="estado in estadosTrabajo" :key="estado.id" :value="estado.id">{{ estado.nombre }}</option>
                      </select>
                    </div>
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
                        v-model="form.aCuenta"
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
                      <select v-model="form.idEstadoPago" class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400">
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
const form = useForm({
  cliente: '',
  servicios: [
    {
      idServicio: '',
      cantidad: 1,
      descuento: 0,
      detalles: {
        tamano: '',
        color: '',
        modelo: '',
        tipoDocumento: '',
        tipoEvento: '',
        descripcion: '',
      }
    }
  ],
  fechaEntrega: '',
  estadoTrabajo: 1,
  idResponsable: '',
  aCuenta: 0,
  idEstadoPago: 2,
})

// Variables reactivas
const isSubmitting = ref(false)
const fechaRegistro = props.trabajo?.fechaRegistro || new Date().toISOString().split('T')[0]
const showSuccessMessage = ref(false)
const showErrorMessage = ref(false)
const messageText = ref('')

// Debug: Log de datos recibidos
onMounted(() => {
  console.log('Datos del trabajo recibidos:', props.trabajo)
  console.log('Detalle del trabajo:', props.trabajo?.detallesTrabajo)
  console.log('Pago del trabajo:', props.trabajo?.detallesTrabajo?.pago)
  console.log('Estado del pago:', props.trabajo?.estadoPago)
})

// Watcher para actualizar el formulario cuando los datos lleguen
watch(() => props.trabajo, async (newTrabajo) => {
  if (newTrabajo) {
    console.log('🔄 Watcher ejecutado - Actualizando formulario con datos:', newTrabajo)
    
    // Actualizar el formulario
    form.cliente = newTrabajo.idCliente || ''
    form.idResponsable = newTrabajo.idResponsable || ''
    form.fechaEntrega = newTrabajo.fechaEntrega || ''
    form.estadoTrabajo = newTrabajo.idEstado || 1
    form.aCuenta = newTrabajo.detallesTrabajo?.[0]?.pago?.aCuenta || 0
    form.idEstadoPago = newTrabajo.idEstadoPago || 2

    // Actualizar servicios - Cargar TODOS los servicios del trabajo
    if (newTrabajo.detallesTrabajo && newTrabajo.detallesTrabajo.length > 0) {
      form.servicios = newTrabajo.detallesTrabajo.map(detalle => ({
        idServicio: detalle.idServicio || '',
        cantidad: detalle.cantidad || 1,
        descuento: detalle.descuento || 0, // Usar el descuento real del detalle
        detalles: {
          tamano: detalle.tamano || '',
          color: detalle.color || '',
          modelo: detalle.modelo || '',
          tipoDocumento: detalle.tipoDocumento || '',
          tipoEvento: detalle.tipoEvento || '',
          descripcion: detalle.descripcion || '',
        }
      }))
    }
    
    console.log('🔄 Datos del trabajo recibidos:', newTrabajo)
    console.log('🔄 Formulario actualizado:', form)
    
    // Esperar a que Vue procese los cambios
    await nextTick()
    
    console.log('✅ Formulario actualizado después de nextTick:', form)
  }
}, { immediate: true, deep: true })

// Watcher para detectar cambios en los servicios y forzar reactividad
watch(() => form.servicios, (newServicios) => {
  console.log('🔄 Servicios cambiaron:', newServicios)
  // Forzar recálculo del total
  nextTick(() => {
    console.log('🔄 Total recalculado después de cambio:', totalCalculado.value)
  })
}, { deep: true })

// Computed properties
const trabajoOriginal = computed(() => {
  return props.trabajo
})

const clienteSeleccionado = computed(() => {
  return props.clientes.find(c => c.id === form.cliente)
})

const usuarioSeleccionado = computed(() => {
  return props.usuarios.find(u => u.id === form.idResponsable)
})

const totalCalculado = computed(() => {
  const total = form.servicios.reduce((total, servicio) => {
    const subtotal = calcularSubtotal(servicio)
    return total + subtotal
  }, 0)
  console.log('💵 Total calculado:', {
    servicios: form.servicios.length,
    total: total
  })
  return total
})

const saldoCalculado = computed(() => {
  return Math.max(0, totalCalculado.value - form.aCuenta)
})

// Métodos
const onClienteChange = () => {
  // Resetear campos relacionados
  form.servicios.forEach(servicio => {
    servicio.detalles.tamano = ''
    servicio.detalles.color = ''
    servicio.detalles.modelo = ''
    servicio.detalles.tipoDocumento = ''
    servicio.detalles.tipoEvento = ''
    servicio.detalles.descripcion = ''
  })
}

const agregarOtroServicio = () => {
  form.servicios.push({
    idServicio: '',
    cantidad: 1,
    descuento: 0,
    detalles: {
      tamano: '',
      color: '',
      modelo: '',
      tipoDocumento: '',
      tipoEvento: '',
      descripcion: '',
    }
  })
}

const eliminarServicio = (index) => {
  if (form.servicios.length > 1) {
    form.servicios.splice(index, 1)
  }
}

const obtenerServicioInfo = (idServicio) => {
  return props.servicios.find(s => s.id === idServicio)
}

const calcularPrecioFinalPorUnidad = (servicio) => {
  const servicioInfo = obtenerServicioInfo(servicio.idServicio)
  if (!servicioInfo) return 0
  const precioFinal = servicioInfo.precioReferencial - (servicio.descuento || 0)
  console.log('💰 Precio final por unidad:', {
    servicio: servicioInfo.nombreServicio,
    precioOriginal: servicioInfo.precioReferencial,
    descuento: servicio.descuento || 0,
    precioFinal: precioFinal
  })
  return precioFinal
}

const calcularSubtotal = (servicio) => {
  const precioFinal = calcularPrecioFinalPorUnidad(servicio)
  const cantidad = servicio.cantidad || 1
  const subtotal = precioFinal * cantidad
  console.log('🧮 Subtotal calculado:', {
    servicio: obtenerServicioInfo(servicio.idServicio)?.nombreServicio,
    precioFinal: precioFinal,
    cantidad: cantidad,
    subtotal: subtotal
  })
  return subtotal
}

const validarDescuento = (servicio) => {
  const servicioInfo = obtenerServicioInfo(servicio.idServicio)
  if (servicioInfo && servicio.descuento >= servicioInfo.precioReferencial) {
    servicio.descuento = servicioInfo.precioReferencial - 0.01
  }
  console.log('🔍 Descuento validado:', {
    servicio: servicioInfo?.nombreServicio,
    descuento: servicio.descuento,
    precioOriginal: servicioInfo?.precioReferencial
  })
}

const submitForm = () => {
  // Validación del lado del cliente
  if (!form.cliente) {
    alert('Por favor selecciona un cliente')
    return
  }
  
  if (!form.servicios.some(s => s.idServicio)) {
    alert('Por favor selecciona al menos un servicio')
    return
  }
  
  if (!form.fechaEntrega) {
    alert('Por favor selecciona una fecha de entrega')
    return
  }
  
  // El responsable es opcional, no se valida
  
  // Validar campo aCuenta
  if (form.aCuenta < 0) {
    alert('El monto a cuenta no puede ser negativo')
    return
  }
  
  isSubmitting.value = true
  
  // Debug: verificar los datos del trabajo
  console.log('🔍 Props del trabajo:', props.trabajo)
  console.log('🔍 Slug del trabajo:', props.trabajo.slug)
  
  // Usar fetch directamente para evitar problemas con axios
  const slug = props.trabajo.slug
  const url = 'http://127.0.0.1:8000/registrar-trabajos/' + slug
  console.log('🔗 Slug extraído:', slug)
  console.log('🔗 URL construida:', url)
  
  console.log('🚀 Enviando petición PUT a:', url)
  console.log('📦 Datos del formulario:', form.data())
  
  // Usar form.put de Inertia como última alternativa
  form.put(`/registrar-trabajos/${slug}`, {
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
