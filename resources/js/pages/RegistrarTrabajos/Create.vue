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

                <!-- Sección Servicio -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">Servicio</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Tipo de Servicio *</label>
                      <select 
                        v-model="form.servicio" 
                        @change="onServicioChange"
                        class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
                        required
                      >
                        <option value="">Seleccionar servicio</option>
                        <option v-for="servicio in servicios" :key="servicio.id" :value="servicio.id">
                          {{ servicio.nombreServicio }} - {{ servicio.precioReferencial }} Bs
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-white mb-2">Precio Unitario</label>
                      <input 
                        type="text" 
                        :value="servicioSeleccionado?.precioReferencial || ''" 
                        disabled
                        class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
                      />
                    </div>
                  </div>
                </div>

                <!-- Sección Detalles del Trabajo -->
                <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
                  <h3 class="text-lg font-semibold mb-4 text-white">Detalles del Trabajo</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-white/90">
                    <div>
                      <label class="block text-sm font-medium mb-2">Tamaño</label>
                      <input v-model="form.detalles.tamano" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Color</label>
                      <input v-model="form.detalles.color" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Modelo</label>
                      <input v-model="form.detalles.modelo" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Cantidad *</label>
                      <input v-model="form.detalles.cantidad" type="number" min="1" @input="calcularTotal" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" required />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Tipo de Documento</label>
                      <input v-model="form.detalles.tipoDocumento" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium mb-2">Tipo de Evento</label>
                      <input v-model="form.detalles.tipoEvento" type="text" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" />
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium mb-2">Descripción</label>
                      <textarea v-model="form.detalles.descripcion" rows="3" class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400"></textarea>
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
                        required
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

                <!-- Botones de acción -->
                <div class="flex justify-between space-x-4 pt-6">
                  <div class="flex space-x-4">
                    <!-- Botón para agregar otro servicio -->
                    <button 
                      v-if="form.cliente"
                      @click="agregarOtroServicio"
                      type="button"
                      :disabled="isSubmitting || !puedeGuardar"
                      class="bg-green-500 hover:bg-green-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded"
                    >
                      ➕ Agregar Otro Servicio
                    </button>
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
import { Link, useForm } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  clientes: Array,
  servicios: Array,
  estadosTrabajo: Array,
  estadosPago: Array,
  clientePreSeleccionado: Object, // Nuevo prop para cliente pre-seleccionado
})

// Estado del formulario
const form = useForm({
  cliente: props.clientePreSeleccionado?.id || '',
  servicio: '',
  detalles: {
    tamano: '',
    color: '',
    modelo: '',
    cantidad: 1,
    tipoDocumento: '',
    tipoEvento: '',
    descripcion: '',
  },
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

const puedeGuardar = computed(() => {
  return form.cliente && form.servicio && form.detalles.cantidad > 0 && form.fechaEntrega
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
  
  if (form.aCuenta < 0) {
    alert('El monto a cuenta no puede ser negativo')
    return
  }
  
  isSubmitting.value = true
  
  // Guardar el trabajo actual y luego redirigir para agregar otro
  form.post(route('registrar-trabajos.store'), {
    onSuccess: () => {
      isSubmitting.value = false
      console.log('Trabajo registrado exitosamente, redirigiendo para agregar otro...')
      
      // Redirigir al formulario de crear con el cliente pre-seleccionado
      window.location.href = route('registrar-trabajos.create', { cliente_id: form.cliente })
    },
    onError: (errors) => {
      isSubmitting.value = false
      console.error('Errores del servidor:', errors)
    }
  })
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
