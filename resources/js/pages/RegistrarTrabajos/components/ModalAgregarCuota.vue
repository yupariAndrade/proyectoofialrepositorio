<template>
  <!-- Modal Agregar Cuota -->
  <div v-if="mostrar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="cerrarModal">
    <div class="bg-[#0c1d3a] rounded-lg border border-white/10 w-full max-w-md mx-4">
      <!-- Header del Modal -->
      <div class="flex items-center justify-between p-6 border-b border-white/10">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white">Agregar Cuota</h3>
        </div>
        <button @click="cerrarModal" class="text-gray-400 hover:text-white transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Contenido del Modal -->
      <div class="p-6 space-y-6">
        <!-- Información del Trabajo -->
        <div class="bg-[#0a192f]/50 p-4 rounded-lg border border-white/10">
          <h4 class="text-sm font-medium text-white mb-3">Información del Trabajo</h4>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="text-gray-400">Cliente:</span>
              <span class="text-white ml-2">{{ trabajo?.cliente?.nombre }}</span>
            </div>
            <div>
              <span class="text-gray-400">Total:</span>
              <span class="text-white ml-2">{{ getTotalTrabajo(trabajo) }} Bs</span>
            </div>
            <div>
              <span class="text-gray-400">Ya pagado:</span>
              <span class="text-white ml-2">{{ formatPrecio(trabajo?.aCuenta || 0) }} Bs</span>
            </div>
            <div>
              <span class="text-gray-400">Saldo pendiente:</span>
              <span class="text-white ml-2">{{ formatPrecio(trabajo?.saldo || 0) }} Bs</span>
            </div>
          </div>
        </div>

        <!-- Monto de la Cuota -->
        <div>
          <label class="block text-sm font-medium text-white mb-2">Monto de la cuota</label>
          <div class="relative">
            <input
              v-model="montoCuota"
              type="number"
              step="0.01"
              min="0.01"
              :max="trabajo?.saldo || 0"
              placeholder="Ej: 50.00"
              class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md px-4 py-3 pr-12 focus:ring-cyan-400 focus:border-cyan-400"
            />
            <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
              <button
                @click="incrementarMonto"
                class="text-cyan-400 hover:text-cyan-300 text-xs"
              >
                ▲
              </button>
              <button
                @click="decrementarMonto"
                class="text-cyan-400 hover:text-cyan-300 text-xs"
              >
                ▼
              </button>
            </div>
          </div>
          <p class="text-xs text-gray-400 mt-1">Máximo: {{ formatPrecio(trabajo?.saldo || 0) }} Bs</p>
        </div>

        <!-- Estado de Pago (Opcional) -->
        <div>
          <label class="block text-sm font-medium text-white mb-2">
            Estado de pago 
            <span v-if="!puedeCambiarEstado" class="text-xs text-gray-400">(solo cuando se complete el saldo)</span>
            <span v-else class="text-xs text-gray-400">(opcional)</span>
          </label>
          <select
            v-model="estadoPagoSeleccionado"
            :disabled="!puedeCambiarEstado"
            class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md px-4 py-3 focus:ring-cyan-400 focus:border-cyan-400 disabled:bg-gray-700 disabled:text-gray-400 disabled:cursor-not-allowed"
          >
            <option value="mantener">Mantener estado actual</option>
            <option v-if="puedeCambiarEstado" v-for="estado in estadosPagoDisponibles" :key="estado.id" :value="estado.id">
              {{ estado.nombreEstado }}
            </option>
          </select>
          <p v-if="!puedeCambiarEstado" class="text-xs text-yellow-400 mt-1">
            💡 El estado solo se puede cambiar cuando el saldo se complete (0 Bs)
          </p>
          <p v-else class="text-xs text-green-400 mt-1">
            ✅ Saldo completado - Puedes cambiar el estado de pago
          </p>
        </div>
      </div>

      <!-- Footer del Modal -->
      <div class="flex justify-end space-x-3 p-6 border-t border-white/10">
        <button
          @click="cerrarModal"
          class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
        >
          Cancelar
        </button>
        <button
          @click="procesarCuota"
          :disabled="procesando"
          class="flex items-center space-x-2 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-500 text-white px-4 py-2 rounded-md transition-colors"
        >
          <svg v-if="!procesando" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
          </svg>
          <svg v-else class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span>{{ procesando ? 'Procesando...' : 'Procesar Cuota' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'

// Props
interface Props {
  mostrar: boolean
  trabajo: any
  estadosPago: any[]
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  cerrar: []
  cuotaProcesada: [trabajo: any]
}>()

// Estado reactivo del modal
const montoCuota = ref('')
const estadoPagoSeleccionado = ref('mantener')
const procesando = ref(false)

// Computed para determinar si se puede cambiar el estado de pago
const puedeCambiarEstado = computed(() => {
  if (!props.trabajo) return false
  
  const saldoActual = Number(props.trabajo.saldo || 0)
  const montoIngresado = Number(montoCuota.value) || 0
  const nuevoSaldo = saldoActual - montoIngresado
  
  // Solo se puede cambiar el estado si el nuevo saldo será 0 (completamente pagado)
  return nuevoSaldo <= 0
})

// Computed para obtener estados de pago disponibles
const estadosPagoDisponibles = computed(() => {
  if (!props.estadosPago) return []
  
  // Filtrar para mostrar solo "Pago completado" cuando se puede cambiar el estado
  return props.estadosPago.filter(estado => 
    estado.nombreEstado?.toLowerCase().includes('completado') || 
    estado.nombreEstado?.toLowerCase().includes('pagado')
  )
})

// Función para calcular el total del trabajo
const getTotalTrabajo = (trabajo: any): number => {
  if (!trabajo?.servicios || trabajo.servicios.length === 0) return 0
  
  return trabajo.servicios.reduce((total: number, servicio: any) => {
    const precioOriginal = Number(servicio.precio) || 0
    const cantidad = Number(servicio.cantidad) || 0
    const descuento = Number(servicio.descuento) || 0
    const subtotalBruto = precioOriginal * cantidad
    const subtotalFinal = Math.max(0, subtotalBruto - descuento)
    return total + subtotalFinal
  }, 0)
}

// ✅ ARQUITECTURA MVC - Función helper para formatear precios (consistente con Index.vue)
const formatPrecio = (precio: any): string => {
  const numPrecio = Number(precio) || 0
  
  // Solo mostrar decimales si hay centavos
  if (numPrecio % 1 === 0) {
    return numPrecio.toString() // Sin decimales para números enteros
  } else {
    return numPrecio.toFixed(2) // Con decimales para números con centavos
  }
}

// Funciones para incrementar/decrementar monto
const incrementarMonto = () => {
  const actual = Number(montoCuota.value) || 0
  const maximo = Number(props.trabajo?.saldo || 0)
  montoCuota.value = Math.min(actual + 10, maximo).toString()
}

const decrementarMonto = () => {
  const actual = Number(montoCuota.value) || 0
  montoCuota.value = Math.max(actual - 10, 0).toString()
}

// Función para cerrar el modal
const cerrarModal = () => {
  emit('cerrar')
  // Resetear valores
  montoCuota.value = ''
  estadoPagoSeleccionado.value = 'mantener'
  procesando.value = false
}

// ✅ ARQUITECTURA MVC - Función para procesar la cuota
const procesarCuota = async () => {
  // Validaciones básicas
  if (!props.trabajo || !montoCuota.value) {
    alert('Por favor ingrese un monto válido')
    return
  }
  
  const montoNumero = parseFloat(montoCuota.value)
  const saldoPendiente = parseFloat(props.trabajo.saldo || 0)
  
  // Validaciones de negocio
  if (isNaN(montoNumero) || montoNumero <= 0) {
    alert('El monto de la cuota debe ser mayor a 0')
    return
  }
  
  if (montoNumero > saldoPendiente) {
    alert(`Error: El monto de la cuota (${montoNumero} Bs) no puede ser mayor al saldo pendiente (${saldoPendiente} Bs)`)
    return
  }
  
  procesando.value = true
  
  try {
    // ✅ ARQUITECTURA MVC - Enviar pago al servidor usando la nueva ruta API
    const response = await fetch('/api/cuotas', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        idTrabajo: props.trabajo.id,
        monto: montoNumero
      })
    })
    
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        // ✅ ARQUITECTURA MVC - Mostrar mensaje de éxito
        const mensajeExito = data.flash?.success || data.message || 'Cuota registrada exitosamente'
        alert('✅ ' + mensajeExito)
        
        // ✅ ARQUITECTURA MVC - Emitir evento con datos actualizados del backend
        emit('cuotaProcesada', data.trabajo)
        cerrarModal()
      } else {
        alert('❌ Error: ' + data.message)
      }
    } else {
      const errorData = await response.json()
      alert('❌ Error al procesar el pago: ' + (errorData.message || 'Error desconocido'))
    }
  } catch (error: any) {
    console.error('Error:', error)
    alert('Error al procesar el pago: ' + (error?.message || 'Error desconocido'))
  } finally {
    procesando.value = false
  }
}

// Resetear valores cuando el modal se abre
watch(() => props.mostrar, (nuevoValor) => {
  if (nuevoValor) {
    montoCuota.value = ''
    estadoPagoSeleccionado.value = 'mantener'
    procesando.value = false
  }
})
</script>
