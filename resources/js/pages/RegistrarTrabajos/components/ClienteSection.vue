<template>
  <!-- Sección Cliente -->
  <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
    <h3 class="text-lg font-semibold mb-4 text-white">Información del Cliente</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-white mb-2">Cliente *</label>
        <select 
          :value="modelValue.cliente" 
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
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    required: true
  },
  clientes: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['update:modelValue', 'cliente-change'])

const clienteSeleccionado = computed(() => {
  return props.clientes.find(cliente => cliente.id == props.modelValue.cliente)
})

const onClienteChange = (event) => {
  const nuevoCliente = event.target.value
  emit('update:modelValue', { ...props.modelValue, cliente: nuevoCliente })
  emit('cliente-change', nuevoCliente)
}
</script>
