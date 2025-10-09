<template>
  <div v-if="mostrar" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-2xl max-w-md w-full mx-4">
      <!-- Recibo -->
      <div class="text-center border-2 border-gray-300 p-6 bg-white">
        <!-- Logo -->
        <div class="mb-4">
          <img 
            src="/img/logo.png" 
            alt="FOTO STUDIO EU" 
            class="h-16 mx-auto"
            @error="logoError = true"
          >
          <h1 v-if="logoError" class="text-2xl font-bold text-gray-800">FOTO STUDIO EU</h1>
        </div>
        
        <!-- Información del cliente -->
        <div class="text-left mb-4">
          <p class="text-sm"><strong>Cliente:</strong> {{ trabajo?.cliente?.nombre }} {{ trabajo?.cliente?.apellido }}</p>
          <p class="text-sm"><strong>Fecha:</strong> {{ formatDate(trabajo?.fechaRegistro) }}</p>
          <p class="text-sm"><strong>Entrega:</strong> {{ formatDate(trabajo?.fechaEntrega) }}</p>
        </div>
        
        <!-- Servicios -->
        <div class="text-left mb-4">
          <p class="text-sm font-semibold mb-2">Servicios:</p>
          <div v-for="servicio in trabajo?.servicios" :key="servicio.id" class="text-sm">
            <p>• {{ servicio.nombreServicio }} x{{ servicio.cantidad }} - {{ formatPrecio(servicio.subtotal) }} Bs</p>
          </div>
        </div>
        
        <!-- Totales -->
        <div class="border-t pt-3 text-left">
          <p class="text-sm"><strong>Total:</strong> {{ formatPrecio(trabajo?.total) }} Bs</p>
          <p class="text-sm"><strong>A Cuenta:</strong> {{ formatPrecio(trabajo?.aCuenta) }} Bs</p>
          <p class="text-sm"><strong>Saldo:</strong> {{ formatPrecio(trabajo?.saldo) }} Bs</p>
          <p class="text-sm"><strong>Estado:</strong> {{ trabajo?.estadoPago?.nombre }}</p>
        </div>
        
        <!-- Mensaje de agradecimiento -->
        <div class="mt-4 text-center">
          <p class="text-sm text-gray-600">¡Gracias por su preferencia!</p>
        </div>
      </div>
      
      <!-- Botones -->
      <div class="flex justify-center space-x-4 mt-6">
        <button 
          @click="imprimirRecibo"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center space-x-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
          </svg>
          <span>Imprimir</span>
        </button>
        <button 
          @click="cerrarRecibo"
          class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg"
        >
          Cerrar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

interface Props {
  mostrar: boolean
  trabajo: any
}

const props = defineProps<Props>()

const emit = defineEmits<{
  cerrar: []
  imprimir: [trabajo: any]
}>()

const logoError = ref(false)

// Función para formatear fechas
const formatDate = (dateString: string) => {
  if (!dateString) return 'No definida'
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit'
  })
}

// Función para formatear precios
const formatPrecio = (precio: any): string => {
  const numPrecio = Number(precio) || 0
  if (numPrecio % 1 === 0) {
    return numPrecio.toString()
  } else {
    return numPrecio.toFixed(2)
  }
}

// Función para imprimir
const imprimirRecibo = () => {
  emit('imprimir', props.trabajo)
}

// Función para cerrar
const cerrarRecibo = () => {
  emit('cerrar')
}

// Auto-cerrar después de 3 segundos
onMounted(() => {
  if (props.mostrar) {
    setTimeout(() => {
      cerrarRecibo()
    }, 3000)
  }
})
</script>