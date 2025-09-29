<template>
  <!-- Modal de cancelación de trabajo -->
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-gradient-to-br from-slate-800/95 to-slate-900/95 backdrop-blur-xl border border-slate-600/50 rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
      <!-- Header del Modal -->
      <div class="bg-gradient-to-r from-red-500/20 to-pink-500/20 border-b border-red-400/30 p-6 rounded-t-2xl">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white">Cancelar Trabajo</h3>
              <p class="text-sm text-slate-300">Cliente: {{ trabajo?.cliente?.nombre }} {{ trabajo?.cliente?.apellido }}</p>
            </div>
          </div>
          <button 
            @click="$emit('cerrar')"
            class="text-slate-400 hover:text-white transition-colors duration-200 p-2 hover:bg-slate-700/50 rounded-lg"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Contenido del Modal -->
      <div class="p-6 space-y-6">
        <!-- Información del trabajo -->
        <div class="bg-slate-700/30 rounded-xl p-4 border border-slate-600/30">
          <h4 class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Información del Trabajo
          </h4>
          <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
              <span class="text-slate-400">Total:</span>
              <span class="text-white font-medium ml-2">{{ trabajo?.total || 0 }} Bs</span>
            </div>
            <div>
              <span class="text-slate-400">A Cuenta:</span>
              <span class="text-white font-medium ml-2">{{ trabajo?.aCuenta || 0 }} Bs</span>
            </div>
            <div>
              <span class="text-slate-400">Saldo:</span>
              <span class="text-white font-medium ml-2">{{ trabajo?.saldo || 0 }} Bs</span>
            </div>
            <div>
              <span class="text-slate-400">Estado:</span>
              <span class="text-white font-medium ml-2">{{ trabajo?.estado?.nombre || 'Sin estado' }}</span>
            </div>
          </div>
        </div>

        <!-- Formulario de cancelación -->
        <form @submit.prevent="cancelarTrabajo" class="space-y-4">
          <!-- Campo Observaciones -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">
              <span class="text-gray-400">(Opcional)</span> Observaciones
            </label>
            <textarea 
              v-model="form.observaciones"
              class="w-full bg-slate-700/50 border border-slate-600/50 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-red-500/50 focus:border-red-400 transition-all duration-300 resize-none"
              placeholder="Motivo de la cancelación..."
              rows="4"
              maxlength="200"
            ></textarea>
            <div class="flex justify-between items-center mt-1">
              <span class="text-xs text-slate-400">Máximo 200 caracteres</span>
              <span class="text-xs text-slate-400">{{ form.observaciones.length }}/200</span>
            </div>
          </div>

          <!-- Campo Monto Devuelto -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">
              <span class="text-gray-400">(Opcional)</span> Monto Devuelto
            </label>
            <div class="relative">
              <input 
                v-model.number="form.montoDevuelto"
                type="number"
                step="0.01"
                min="0"
                max="999999.99"
                class="w-full bg-slate-700/50 border border-slate-600/50 rounded-xl px-4 py-3 pl-12 text-white placeholder-slate-400 focus:ring-2 focus:ring-red-500/50 focus:border-red-400 transition-all duration-300"
                placeholder="0.00"
              />
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <span class="text-slate-400 font-medium">Bs</span>
              </div>
            </div>
            <p class="text-xs text-slate-400 mt-1">
              💡 Monto que se devolverá al cliente
            </p>
          </div>

          <!-- Botones de acción -->
          <div class="flex gap-3 pt-4">
            <button 
              type="button"
              @click="$emit('cerrar')"
              class="flex-1 bg-slate-600/50 hover:bg-slate-600 text-white px-4 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              :disabled="loading || !form.observaciones.trim() || form.montoDevuelto < 0"
              class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 disabled:from-slate-600 disabled:to-slate-700 disabled:cursor-not-allowed text-white px-4 py-3 rounded-xl font-medium transition-all duration-300 hover:scale-105 shadow-lg shadow-red-500/25"
            >
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Cancelando...
              </span>
              <span v-else class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Confirmar Cancelación
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

// Props
const props = defineProps({
  trabajo: {
    type: Object,
    required: true
  }
})

// Emits
const emit = defineEmits(['cerrar', 'cancelado'])

// Debug: Log cuando el modal se monta
onMounted(() => {
  console.log('🚨 ModalCancelarTrabajo montado con trabajo:', props.trabajo)
})

// Estado del componente
const loading = ref(false)

// Formulario
const form = reactive({
  observaciones: '',
  montoDevuelto: 0
})

// Debug: Log cuando el modal se monta
onMounted(() => {
  console.log('🚨 ModalCancelarTrabajo montado con trabajo:', props.trabajo);
});

// Función para cancelar trabajo
const cancelarTrabajo = async () => {
  // Validación básica (campos opcionales)
  if (form.montoDevuelto < 0) {
    return
  }

  loading.value = true

  try {
    const response = await axios.patch(`/api/trabajos/${props.trabajo.id}/cancelar`, {
      observaciones: form.observaciones.trim(),
      montoDevuelto: form.montoDevuelto
    }, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })

    if (response.data.success) {
      // Emitir evento de cancelación exitosa
      emit('cancelado', response.data.trabajo)
      
      // Cerrar modal
      emit('cerrar')
      
      console.log('✅ Trabajo cancelado exitosamente:', response.data.message)
    } else {
      console.error('❌ Error al cancelar trabajo:', response.data.message)
    }

  } catch (error) {
    console.error('❌ Error al cancelar trabajo:', error)
    
    if (error.response?.data?.message) {
      console.error('Mensaje del servidor:', error.response.data.message)
    }
  } finally {
    loading.value = false
  }
}
</script>
