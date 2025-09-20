<template>
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
          :value="modelValue.aCuenta"
          @input="updateACuenta($event.target.value)"
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
          :value="saldoCalculado + ' Bs'" 
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
import { computed } from 'vue'

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
  }
})

const emit = defineEmits(['update:modelValue', 'pago-change'])

const saldoCalculado = computed(() => {
  return Math.max(0, props.totalCalculado - (props.modelValue.aCuenta || 0))
})

const updateACuenta = (valor) => {
  const aCuenta = parseFloat(valor) || ''
  emit('update:modelValue', { ...props.modelValue, aCuenta })
  emit('pago-change', { ...props.modelValue, aCuenta })
}

const validarACuenta = () => {
  const aCuenta = props.modelValue.aCuenta
  if (aCuenta === '' || aCuenta === null || aCuenta === undefined || aCuenta < 0) {
    emit('update:modelValue', { ...props.modelValue, aCuenta: 0 })
    emit('pago-change', { ...props.modelValue, aCuenta: 0 })
  } else if (aCuenta > props.totalCalculado) {
    emit('update:modelValue', { ...props.modelValue, aCuenta: props.totalCalculado })
    emit('pago-change', { ...props.modelValue, aCuenta: props.totalCalculado })
  }
}

const onEstadoPagoChange = (event) => {
  const idEstadoPago = event.target.value
  emit('update:modelValue', { ...props.modelValue, idEstadoPago })
  emit('pago-change', { ...props.modelValue, idEstadoPago })
}
</script>
