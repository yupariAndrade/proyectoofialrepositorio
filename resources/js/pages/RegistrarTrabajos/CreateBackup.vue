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
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium mb-2 text-white">Responsable Principal *</label>
                      <select 
                        v-model="form.idResponsable"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                        required
                      >
                        <option value="">Seleccionar responsable</option>
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
                        <p class="text-white/50 text-sm">Selecciona un responsable</p>
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
                      <select 
                        v-model="form.idEstadoPago"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                      >
                        <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                          {{ estado.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between space-x-4 pt-6">
                  <div class="flex space-x-4">
                    <Link :href="route('registrar-trabajos')" class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">Cancelar</Link>
                  </div>
                  <button type="submit" :disabled="isSubmitting" class="bg-cyan-600 hover:bg-cyan-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded">
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
import { ref, computed, onMounted } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
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
  clientes: Array,
  servicios: Array,
  usuarios: Array, // Lista de usuarios para asignar responsables
  estadosTrabajo: Array,
  estadosPago: Array,
  clientePreSeleccionado: Object, // Nuevo prop para cliente pre-seleccionado
})

// Estado del formulario
const form = useForm({
  cliente: props.clientePreSeleccionado?.id || '',
  servicios: [
    {
      idServicio: '',
      cantidad: 1,
      descuento: 0.00,
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
  idResponsable: '', // Campo para asignación de responsable
  fechaEntrega: '',
  estadoTrabajo: 1, // Pendiente por defecto
  aCuenta: 0,
  idEstadoPago: 2, // Cambiar a 2 (Pendiente) que es más lógico para un nuevo trabajo
})

// Variables reactivas
const isSubmitting = ref(false)
const fechaRegistro = new Date().toISOString().split('T')[0]

// Computed properties
const clienteSeleccionado = computed(() => {
  return props.clientes.find(c => c.id === form.cliente)
})

const totalCalculado = computed(() => {
  let total = 0
  form.servicios.forEach(servicio => {
    total += calcularSubtotal(servicio)
  })
  return total
})

const usuarioSeleccionado = computed(() => {
  return props.usuarios?.find(u => u.id === form.idResponsable)
})

const saldoCalculado = computed(() => {
  return Math.max(0, totalCalculado.value - form.aCuenta)
})

const estadoPagoNombre = computed(() => {
  if (saldoCalculado.value === 0) return 'Completado'
  if (form.aCuenta === 0) return 'Cancelado'
  return 'Parcial'
})

const puedeGuardar = computed(() => {
  const tieneServicios = form.servicios.some(servicio => 
    servicio.idServicio && servicio.cantidad > 0
  )
  return form.cliente && tieneServicios && form.idResponsable && form.fechaEntrega
})

// Métodos
const onClienteChange = () => {
  // Resetear campos relacionados
  form.servicio = ''
  // Resetear los detalles del primer servicio si existe
  if (form.servicios && form.servicios.length > 0 && form.servicios[0].detalles) {
    form.servicios[0].detalles.tamano = ''
    form.servicios[0].detalles.color = ''
    form.servicios[0].detalles.modelo = ''
    form.servicios[0].detalles.tipoDocumento = ''
    form.servicios[0].detalles.tipoEvento = ''
    form.servicios[0].detalles.descripcion = ''
  }
  // NO resetear cantidad - mantener el valor del usuario
  form.aCuenta = 0
}

const onServicioChange = () => {
  // Resetear campos relacionados
  form.detalles.tamano = ''
  form.detalles.color = ''
  form.detalles.modelo = ''
  form.detalles.tipoDocumento = ''
  form.detalles.tipoEvento = ''
  form.detalles.descripcion = ''
  // NO resetear cantidad - mantener el valor del usuario
  form.aCuenta = 0
}

const calcularTotal = () => {
  // El total se calcula automáticamente
}

const calcularSaldo = () => {
  // El saldo se calcula automáticamente
}

const agregarOtroServicio = () => {
  form.servicios.push({
    idServicio: '',
    cantidad: 1,
    descuento: 0.00,
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
  
  const precioUnitario = servicioInfo.precioReferencial
  const descuentoPorUnidad = servicio.descuento || 0
  
  // Calcular precio final por unidad: precio original - descuento por unidad
  const precioFinalPorUnidad = precioUnitario - descuentoPorUnidad
  
  return Math.max(0, precioFinalPorUnidad) // Asegurar que no sea negativo
}

const calcularSubtotal = (servicio) => {
  const servicioInfo = obtenerServicioInfo(servicio.idServicio)
  if (!servicioInfo || !servicio.cantidad) return 0
  
  const precioFinalPorUnidad = calcularPrecioFinalPorUnidad(servicio)
  const cantidad = servicio.cantidad
  
  // Calcular subtotal: precio final por unidad × cantidad
  const subtotal = precioFinalPorUnidad * cantidad
  
  return Math.max(0, subtotal) // Asegurar que no sea negativo
}

const validarDescuento = (servicio) => {
  const servicioInfo = obtenerServicioInfo(servicio.idServicio)
  if (servicioInfo && servicio.descuento > servicioInfo.precioReferencial) {
    servicio.descuento = servicioInfo.precioReferencial
  }
}

const submitForm = () => {
  // Validación del lado del cliente
  if (!form.cliente) {
    alert('Por favor selecciona un cliente')
    return
  }
  
  // Validar que al menos un servicio esté seleccionado
  const serviciosValidos = form.servicios.filter(servicio => 
    servicio.idServicio && servicio.cantidad > 0
  )
  
  if (serviciosValidos.length === 0) {
    alert('Por favor selecciona al menos un servicio con cantidad válida')
    return
  }
  
  if (!form.idResponsable) {
    alert('Por favor selecciona un responsable')
    return
  }
  
  if (!form.fechaEntrega) {
    alert('Por favor selecciona una fecha de entrega')
    return
  }
  
  if (form.aCuenta < 0) {
    alert('El monto a cuenta no puede ser negativo')
    return
  }
  
  isSubmitting.value = true
  
  form.post(route('registrar-trabajos.store'), {
    onSuccess: () => {
      isSubmitting.value = false
      console.log('Trabajo registrado exitosamente')
    },
    onError: (errors) => {
      isSubmitting.value = false
      console.error('Errores del servidor:', errors)
    }
  })
}
</script>
