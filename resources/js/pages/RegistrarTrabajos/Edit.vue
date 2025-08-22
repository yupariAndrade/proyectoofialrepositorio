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
            <p class="text-slate-300">Modificar información del trabajo</p>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
              <h2 class="text-2xl font-bold mb-6 text-gray-900">Editar Trabajo</h2>
              
              <form @submit.prevent="submitForm" class="space-y-8">
                <!-- Sección de Historial del Trabajo -->
                <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-400">
                  <h3 class="text-lg font-semibold mb-4 text-blue-700">📋 Historial del Trabajo Actual</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                      <span class="font-medium text-blue-700">Servicio Original:</span>
                      <p class="text-blue-600">{{ trabajoOriginal.servicio?.nombreServicio }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-blue-700">Cantidad Original:</span>
                      <p class="text-blue-600">{{ trabajoOriginal.detalleTrabajo?.cantidad }}</p>
                    </div>
                    <div>
                      <span class="font-medium text-blue-700">Total Original:</span>
                      <p class="text-blue-600">{{ trabajoOriginal.pagos?.[0]?.total || 0 }} Bs</p>
                    </div>
                  </div>
                </div>

                <!-- Sección Cliente -->
                <div class="bg-gray-50 p-6 rounded-lg">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Información del Cliente</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cliente *
                      </label>
                      <select 
                        v-model="form.cliente" 
                        @change="onClienteChange"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required
                      >
                        <option value="">Seleccionar cliente</option>
                        <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                          {{ cliente.nombre }} {{ cliente.apellido }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Referencia
                      </label>
                      <input 
                        type="text" 
                        :value="clienteSeleccionado?.telefono || ''" 
                        disabled
                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                      />
                    </div>
                  </div>
                </div>

                <!-- Sección Servicio -->
                <div class="bg-gray-50 p-6 rounded-lg">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Servicio</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Servicio *
                      </label>
                      <select 
                        v-model="form.servicio" 
                        @change="onServicioChange"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required
                      >
                        <option value="">Seleccionar servicio</option>
                        <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">
                          {{ servicio.nombreServicio }} - {{ servicio.precioReferencial }} Bs
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Precio Unitario
                      </label>
                      <input 
                        type="text" 
                        :value="servicioSeleccionado?.precioReferencial || ''" 
                        disabled
                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                      />
                    </div>
                  </div>
                </div>

                <!-- Sección Detalles del Trabajo -->
                <div class="bg-gray-50 p-6 rounded-lg">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Detalles del Trabajo</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tamaño
                      </label>
                      <input 
                        v-model="form.detalles.tamano" 
                        type="text" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Color
                      </label>
                      <input 
                        v-model="form.detalles.color" 
                        type="text" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Modelo
                      </label>
                      <input 
                        v-model="form.detalles.modelo" 
                        type="text" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cantidad *
                      </label>
                      <input 
                        v-model="form.detalles.cantidad" 
                        type="number" 
                        min="1"
                        @input="calcularTotal"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Documento
                      </label>
                      <input 
                        v-model="form.detalles.tipoDocumento" 
                        type="text" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Evento
                      </label>
                      <input 
                        v-model="form.detalles.tipoEvento" 
                        type="text" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      />
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                      </label>
                      <textarea 
                        v-model="form.detalles.descripcion" 
                        rows="3"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      ></textarea>
                    </div>
                  </div>
                </div>

                <!-- Sección Información del Trabajo -->
                <div class="bg-gray-50 p-6 rounded-lg">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Información del Trabajo</h3>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Registro
                      </label>
                      <input 
                        type="date" 
                        :value="fechaRegistro" 
                        disabled
                        class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Entrega *
                      </label>
                      <input 
                        v-model="form.fechaEntrega" 
                        type="date" 
                        :min="fechaRegistro"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado del Trabajo
                      </label>
                      <select 
                        v-model="form.estadoTrabajo" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      >
                        <option v-for="estado in estadosTrabajo" :key="estado.id" :value="estado.id">
                          {{ estado.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Sección Pago -->
                <div class="bg-gray-50 p-6 rounded-lg">
                  <h3 class="text-lg font-semibold mb-4 text-gray-700">Información del Pago</h3>
                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Total</label>
                      <input 
                        type="text" 
                        :value="totalCalculado + ' Bs'" 
                        disabled
                        class="w-full border-gray-300 rounded-md bg-gray-100"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">A Cuenta *</label>
                      <input 
                        type="number" 
                        v-model="form.aCuenta"
                        min="0"
                        step="0.01"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Saldo</label>
                      <input 
                        type="text" 
                        :value="saldoCalculado + ' Bs'" 
                        disabled
                        class="w-full border-gray-300 rounded-md bg-gray-100"
                      />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Pago</label>
                      <select 
                        v-model="form.idEstadoPago"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      >
                        <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                          {{ estado.nombre }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Sección de Comentarios sobre Cambios (Solo si hay cambios significativos) -->
                <div v-if="hayCambiosSignificativos" class="bg-yellow-50 p-6 rounded-lg border-l-4 border-yellow-400">
                  <h3 class="text-lg font-semibold mb-4 text-yellow-700">📝 Comentarios sobre Cambios</h3>
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-yellow-700 mb-2">
                        ¿Por qué se realizan estos cambios? <span class="text-yellow-600">(Opcional pero recomendado)</span>
                      </label>
                      <textarea 
                        v-model="form.comentarioCambios" 
                        rows="3"
                        placeholder="Ej: Cliente cambió de invitaciones a certificaciones, aumentó cantidad de 10 a 20..."
                        class="w-full border-yellow-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                      ></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="block text-sm font-medium text-yellow-700 mb-2">
                          Cambio de Servicio
                        </label>
                        <div class="flex items-center space-x-2">
                          <span class="text-sm text-yellow-600">
                            {{ trabajoOriginal.servicio?.nombreServicio }} → {{ servicioSeleccionado?.nombreServicio }}
                          </span>
                          <span v-if="form.servicio !== trabajoOriginal.idServicio" class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                            CAMBIADO
                          </span>
                        </div>
                      </div>
                      <div>
                        <label class="block text-sm font-medium text-yellow-700 mb-2">
                          Cambio de Cantidad
                        </label>
                        <div class="flex items-center space-x-2">
                          <span class="text-sm text-yellow-600">
                            {{ trabajoOriginal.detalleTrabajo?.cantidad }} → {{ form.detalles.cantidad }}
                          </span>
                          <span v-if="form.detalles.cantidad !== trabajoOriginal.detalleTrabajo?.cantidad" class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                            CAMBIADO
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Indicador de Cambios Menores (Siempre visible si hay cambios) -->
                <div v-if="hayCambiosMenores && !hayCambiosSignificativos" class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                  <div class="flex items-center">
                    <span class="text-blue-600 mr-2">ℹ️</span>
                    <span class="text-sm text-blue-700">
                      Se detectaron cambios menores. Los comentarios son opcionales para estos tipos de modificaciones.
                    </span>
                  </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end space-x-4 pt-6">
                  <!-- Botón para agregar otro servicio -->
                  <Link 
                    :href="route('registrar-trabajos.create', { cliente_id: trabajo.idCliente })"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                  >
                    ➕ Agregar Otro Servicio
                  </Link>
                  
                  <Link 
                    :href="route('registrar-trabajos.show', trabajo.id)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                  >
                    Cancelar
                  </Link>
                  
                  <button 
                    type="submit" 
                    :disabled="isSubmitting"
                    class="bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded"
                  >
                    {{ isSubmitting ? 'Actualizando...' : 'Actualizar Trabajo' }}
                  </button>
                </div>

                <!-- Información de Cambios Detectados -->
                <div v-if="hayCualquierCambio" class="mt-4 p-4 bg-gray-50 rounded-lg">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                      <span class="text-gray-600">📊</span>
                      <span class="text-sm text-gray-700">
                        <span v-if="hayCambiosSignificativos" class="font-medium text-yellow-700">
                          Cambios significativos detectados
                        </span>
                        <span v-else-if="hayCambiosMenores" class="font-medium text-blue-700">
                          Cambios menores detectados
                        </span>
                      </span>
                    </div>
                    <div class="text-xs text-gray-500">
                      {{ hayCambiosSignificativos ? 'Recomendado: Agregar comentarios' : 'Comentarios opcionales' }}
                    </div>
                  </div>
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
import { Link, useForm } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  trabajo: Object,
  clientes: Array,
  servicios: Array,
  estadosTrabajo: Array,
  estadosPago: Array,
})

// Estado del formulario - Inicializar con datos reales
const form = useForm({
  cliente: '',
  servicio: '',
  detalles: {
    descripcion: '',
    tamano: '',
    color: '',
    modelo: '',
    cantidad: 1,
    tipoDocumento: '',
    tipoEvento: '',
  },
  fechaEntrega: '',
  estadoTrabajo: 1,
  aCuenta: 0,
  idEstadoPago: 2,
  comentarioCambios: '',
})

// Variables reactivas
const isSubmitting = ref(false)
const fechaRegistro = props.trabajo?.fechaRegistro || new Date().toISOString().split('T')[0]

// Debug: Log de datos recibidos
onMounted(() => {
  console.log('Datos del trabajo recibidos:', props.trabajo)
  console.log('Detalle del trabajo:', props.trabajo?.detalleTrabajo)
  console.log('Pagos del trabajo:', props.trabajo?.pagos)
  console.log('Estado del pago:', props.trabajo?.pagos?.[0]?.estadoPago)
  console.log('Campo descripcion:', props.trabajo?.detalleTrabajo?.descripcion)
  console.log('Campo tamano:', props.trabajo?.detalleTrabajo?.tamano)
  console.log('Campo color:', props.trabajo?.detalleTrabajo?.color)
  console.log('Campo modelo:', props.trabajo?.detalleTrabajo?.modelo)
  console.log('Campo cantidad:', props.trabajo?.detalleTrabajo?.cantidad)
  console.log('Campo tipoDocumento:', props.trabajo?.detalleTrabajo?.tipoDocumento)
  console.log('Campo tipoEvento:', props.trabajo?.detalleTrabajo?.tipoEvento)
})

// Watcher para actualizar el formulario cuando los datos lleguen
watch(() => props.trabajo, async (newTrabajo) => {
  if (newTrabajo) {
    console.log('🔄 Watcher ejecutado - Actualizando formulario con datos:', newTrabajo)
    console.log('📋 Detalle del trabajo:', newTrabajo.detalleTrabajo)
    console.log('💰 Pagos:', newTrabajo.pagos)
    
    // Actualizar el formulario
    form.cliente = newTrabajo.idCliente || ''
    form.servicio = newTrabajo.idServicio || ''
    form.detalles = {
      descripcion: newTrabajo.detalleTrabajo?.descripcion || '',
      tamano: newTrabajo.detalleTrabajo?.tamano || '',
      color: newTrabajo.detalleTrabajo?.color || '',
      modelo: newTrabajo.detalleTrabajo?.modelo || '',
      cantidad: newTrabajo.detalleTrabajo?.cantidad || 1,
      tipoDocumento: newTrabajo.detalleTrabajo?.tipoDocumento || '',
      tipoEvento: newTrabajo.detalleTrabajo?.tipoEvento || '',
    }
    form.fechaEntrega = newTrabajo.fechaEntrega || ''
    form.estadoTrabajo = newTrabajo.idEstado || 1
    form.aCuenta = newTrabajo.pagos?.[0]?.aCuenta || 0
    form.idEstadoPago = newTrabajo.pagos?.[0]?.idEstadoPago || 2
    
    // Esperar a que Vue procese los cambios
    await nextTick()
    
    console.log('✅ Formulario actualizado después de nextTick:', {
      cliente: form.cliente,
      servicio: form.servicio,
      detalles: form.detalles,
      fechaEntrega: form.fechaEntrega,
      estadoTrabajo: form.estadoTrabajo,
      aCuenta: form.aCuenta,
      idEstadoPago: form.idEstadoPago
    })
    
    // Log específico de los detalles
    console.log('📋 Detalles del formulario después de actualizar:', {
      descripcion: form.detalles.descripcion,
      tamano: form.detalles.tamano,
      color: form.detalles.color,
      modelo: form.detalles.modelo,
      cantidad: form.detalles.cantidad,
      tipoDocumento: form.detalles.tipoDocumento,
      tipoEvento: form.detalles.tipoEvento
    })
  }
}, { immediate: true, deep: true })

// Computed properties
const trabajoOriginal = computed(() => {
  return props.trabajo
})

const clienteSeleccionado = computed(() => {
  return props.clientes.find(c => c.id === form.cliente)
})

const servicioSeleccionado = computed(() => {
  return props.servicios.find(s => s.id === form.servicio)
})

const totalCalculado = computed(() => {
  if (!servicioSeleccionado.value || !form.detalles.cantidad) return 0
  return servicioSeleccionado.value.precioReferencial * form.detalles.cantidad
})

const saldoCalculado = computed(() => {
  return Math.max(0, totalCalculado.value - form.aCuenta)
})

const estadoPagoNombre = computed(() => {
  if (saldoCalculado.value === 0) return 'Completado'
  if (form.aCuenta === 0) return 'Cancelado'
  return 'Parcial'
})

// Detectar tipos de cambios
const hayCambiosSignificativos = computed(() => {
  return form.servicio !== props.trabajo?.idServicio ||
         form.detalles.cantidad !== props.trabajo?.detalleTrabajo?.cantidad ||
         form.cliente !== props.trabajo?.idCliente
})

const hayCambiosMenores = computed(() => {
  return form.detalles.tamano !== props.trabajo?.detalleTrabajo?.tamano ||
         form.detalles.color !== props.trabajo?.detalleTrabajo?.color ||
         form.detalles.modelo !== props.trabajo?.detalleTrabajo?.modelo ||
         form.detalles.tipoDocumento !== props.trabajo?.detalleTrabajo?.tipoDocumento ||
         form.detalles.tipoEvento !== props.trabajo?.detalleTrabajo?.tipoEvento ||
         form.detalles.descripcion !== props.trabajo?.detalleTrabajo?.descripcion ||
         form.fechaEntrega !== props.trabajo?.fechaEntrega ||
         form.estadoTrabajo !== props.trabajo?.idEstado
})

const hayCualquierCambio = computed(() => {
  return hayCambiosSignificativos.value || hayCambiosMenores.value
})

// Métodos
const onClienteChange = () => {
  // Resetear campos relacionados
  form.servicio = ''
  form.detalles.tamano = ''
  form.detalles.color = ''
  form.detalles.modelo = ''
  form.detalles.tipoDocumento = ''
  form.detalles.tipoEvento = ''
  form.detalles.descripcion = ''
  // NO resetear cantidad - mantener el valor del usuario
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
}

const calcularTotal = () => {
  // El total se calcula automáticamente
}

const calcularSaldo = () => {
  // El saldo se calcula automáticamente
}

const submitForm = () => {
  // Validación del lado del cliente
  if (!form.cliente) {
    alert('Por favor selecciona un cliente')
    return
  }
  
  if (!form.servicio) {
    alert('Por favor selecciona un servicio')
    return
  }
  
  if (!form.detalles.cantidad || form.detalles.cantidad < 1) {
    alert('Por favor ingresa una cantidad válida (mínimo 1)')
    return
  }
  
  if (!form.fechaEntrega) {
    alert('Por favor selecciona una fecha de entrega')
    return
  }
  
  // Validar campo aCuenta
  if (form.aCuenta < 0) {
    alert('El monto a cuenta no puede ser negativo')
    return
  }
  
  // Validación inteligente de comentarios
  if (hayCambiosSignificativos.value && !form.comentarioCambios.trim()) {
    const confirmar = confirm(
      'Se detectaron cambios significativos (servicio, cantidad o cliente). ' +
      '¿Deseas continuar sin explicar los cambios? ' +
      'Es recomendable documentar estos cambios para el historial.'
    )
    if (!confirmar) {
      return
    }
  }
  
  isSubmitting.value = true
  
  form.put(route('registrar-trabajos.update', props.trabajo.slug), {
    onSuccess: () => {
      isSubmitting.value = false
      console.log('Trabajo actualizado exitosamente')
    },
    onError: (errors) => {
      isSubmitting.value = false
      console.error('Errores del servidor:', errors)
    }
  })
}
</script>
