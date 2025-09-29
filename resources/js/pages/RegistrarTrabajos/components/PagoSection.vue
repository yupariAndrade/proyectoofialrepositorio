<template>
  <!-- Sección Pago -->
  <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
    <h3 class="text-lg font-semibold mb-4 text-white">💰 Información del Pago</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-white mb-2">Total</label>
        <input 
          type="text" 
          :value="isLoading ? 'Calculando...' : (totalCalculado + ' Bs')" 
          disabled
          class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-white mb-2">A Cuenta *</label>
        <input 
          type="number" 
          :value="aCuentaDisplay"
          @input="handleACuentaInput"
          @blur="validarACuenta"
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
          :value="isLoading ? 'Calculando...' : (props.saldoCalculado + ' Bs')" 
          disabled
          class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-white mb-2">Estado del Pago</label>
        <select 
          :value="modelValue.idEstadoPago"
          @change="onEstadoPagoChange"
          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
        >
          <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
            {{ estado.nombre }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true
  },
  estadosPago: {
    type: Array,
    required: true
  },
  totalCalculado: {
    type: Number,
    default: 0
  },
  saldoCalculado: {
    type: Number,
    default: 0
  },
  isLoading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'pago-change'])

// Variable local para el input
const aCuentaLocal = ref(props.modelValue.aCuenta || 0)

// Computed para mostrar el valor
const aCuentaDisplay = computed(() => {
  return aCuentaLocal.value
})

// Watcher para sincronizar con el prop
watch(() => props.modelValue.aCuenta, (newValue) => {
  console.log('🔄 PagoSection: modelValue.aCuenta cambió a:', newValue)
  aCuentaLocal.value = newValue || 0
  console.log('🔄 PagoSection: aCuentaLocal actualizado a:', aCuentaLocal.value)
})

// ✅ Usar el saldo calculado que viene del componente padre
// const saldoCalculado = computed(() => {
//   return Math.max(0, props.totalCalculado - (props.modelValue.aCuenta || 0))
// })

const handleACuentaInput = (event) => {
  const valor = event.target.value
  console.log('🔄 handleACuentaInput llamado con:', valor, 'tipo:', typeof valor)
  
  // Actualizar la variable local
  aCuentaLocal.value = valor
  
  // Convertir a número para el backend
  let aCuenta = 0
  if (valor !== '' && valor !== null && valor !== undefined) {
    aCuenta = parseFloat(valor) || 0
  }
  
  console.log('✅ Emitiendo nuevo valor aCuenta:', aCuenta)
  const newData = { ...props.modelValue, aCuenta }
  console.log('✅ Datos completos a emitir:', newData)
  emit('update:modelValue', newData)
  emit('pago-change', newData)
}

const updateACuenta = (valor) => {
  console.log('🔄 updateACuenta llamado con:', valor, 'tipo:', typeof valor)
  
  // El v-model ya maneja la actualización, solo emitir el evento
  emit('pago-change', { ...props.modelValue })
}

const validarACuenta = () => {
  let aCuenta = props.modelValue.aCuenta
  
  // Asegurar que siempre sea un número
  if (aCuenta === '' || aCuenta === null || aCuenta === undefined) {
    aCuenta = 0
  } else {
    aCuenta = parseFloat(aCuenta) || 0
  }
  
  // Validar límites
  if (aCuenta < 0) {
    aCuenta = 0
  } else if (aCuenta > props.totalCalculado && props.totalCalculado > 0) {
    aCuenta = props.totalCalculado
  }
  
  // Actualizar si cambió
  if (aCuenta !== props.modelValue.aCuenta) {
    console.log('🔄 Validando aCuenta, actualizando a:', aCuenta)
    emit('update:modelValue', { ...props.modelValue, aCuenta })
    emit('pago-change', { ...props.modelValue, aCuenta })
  }
}

const onEstadoPagoChange = (event) => {
  const idEstadoPago = event.target.value
  emit('update:modelValue', { ...props.modelValue, idEstadoPago })
  emit('pago-change', { ...props.modelValue, idEstadoPago })
}
</script>
