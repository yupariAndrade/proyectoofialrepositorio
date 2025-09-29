<template>
  <!-- Sección Asignación de Responsables -->
  <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
    <h3 class="text-lg font-semibold mb-4 text-white">👥 Asignación de Responsables</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-2 text-white">Responsable Principal</label>
        <select 
          :value="modelValue.idResponsable"
          @change="onResponsableChange"
          class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
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
        <input 
          type="date" 
          :value="fechaRegistro" 
          disabled 
          class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md" 
        />
      </div>
      <div>
        <label class="block text-sm font-medium mb-2">Fecha de Entrega *</label>
        <input 
          :value="modelValue.fechaEntrega"
          @change="onFechaEntregaChange"
          type="date" 
          :min="fechaRegistro" 
          class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          required 
        />
      </div>
      <!-- ✅ ARQUITECTURA MVC - Estado del trabajo se maneja desde la lista, no desde el formulario -->
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
  usuarios: {
    type: Array,
    required: true
  },
  // ✅ ARQUITECTURA MVC - Prop eliminada: Estados de trabajo ya no se necesitan en el formulario
  fechaRegistro: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['update:modelValue', 'trabajo-change'])

const usuarioSeleccionado = computed(() => {
  return props.usuarios.find(usuario => usuario.id == props.modelValue.idResponsable)
})

const onResponsableChange = (event) => {
  const idResponsable = event.target.value
  emit('update:modelValue', { ...props.modelValue, idResponsable })
  emit('trabajo-change', { ...props.modelValue, idResponsable })
}

const onFechaEntregaChange = (event) => {
  const fechaEntrega = event.target.value
  emit('update:modelValue', { ...props.modelValue, fechaEntrega })
  emit('trabajo-change', { ...props.modelValue, fechaEntrega })
}

// ✅ ARQUITECTURA MVC - Función eliminada: Estado del trabajo se maneja desde la lista
</script>
