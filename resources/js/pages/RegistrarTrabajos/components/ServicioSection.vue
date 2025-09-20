<template>
  <!-- Sección Servicios -->
  <div class="bg-[#0c1d3a]/70 p-6 rounded-lg border border-white/10">
    <div class="flex justify-between items-center mb-4">
      <div>
        <h3 class="text-lg font-semibold text-white">Servicios</h3>
        <p class="text-sm text-cyan-300 mt-1">
          💡 El descuento se aplica al subtotal del servicio. Ej: 200 tarjetas × 5 Bs = 1,000 Bs, si quieres cobrar 800 Bs, ingresa 200 Bs de descuento
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
    
    <div v-for="(servicio, index) in modelValue.servicios" :key="`servicio-${index}-${servicio.idServicio}`" class="mb-6 p-4 bg-[#0a192f]/50 rounded-lg border border-white/10">
      <div class="flex justify-between items-center mb-4">
        <h4 class="text-md font-medium text-white">Servicio {{ index + 1 }}</h4>
        <button 
          v-if="modelValue.servicios.length >= 2"
          type="button"
          @click="eliminarServicio(index)"
          class="text-red-400 hover:text-red-300 text-sm"
        >
          🗑️ Eliminar
        </button>
      </div>
      
      <!-- Fila única: Servicio + Precio + Cantidad + Descuento + Subtotal -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-white mb-2">Tipo de Servicio *</label>
          <select 
            :value="servicio.idServicio"
            @change="updateServicio(index, 'idServicio', $event.target.value)"
            class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
            required
          >
            <option value="">Seleccionar servicio</option>
            <option v-for="serv in servicios" :key="serv.id" :value="serv.id">
              {{ serv.nombreServicio }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-white mb-2">Precio Referencial</label>
          <input 
            type="text" 
            :value="obtenerServicioInfo(servicio.idServicio)?.precioReferencial ? (obtenerServicioInfo(servicio.idServicio).precioReferencial + ' Bs') : ''" 
            disabled
            class="w-full bg-[#0a192f]/50 text-white border border-white/20 rounded-md shadow-md"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-white mb-2">Cantidad *</label>
          <input 
            :value="servicio.cantidad"
            @input="updateServicio(index, 'cantidad', parseInt($event.target.value) || '')"
            @blur="validarCantidad(index)"
            type="number" 
            min="1"
            class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
            required
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-white mb-2">Descuento (Bs.)</label>
          <input 
            :value="servicio.descuento"
            @input="updateServicio(index, 'descuento', parseFloat($event.target.value) || '')"
            @blur="validarDescuento(index)"
            type="number" 
            min="0"
            :max="calcularSubtotalBruto(servicio)"
            step="0.01"
            class="w-full bg-[#0a192f]/80 text-white border border-cyan-500 rounded-md shadow-md focus:ring-cyan-400 focus:border-cyan-400"
            placeholder="0.00"
          />
          <p v-if="servicio.descuento >= calcularSubtotalBruto(servicio)" class="text-red-400 text-xs mt-1">
            El descuento no puede ser mayor o igual al subtotal bruto
          </p>
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
          <input 
            :value="servicio.detalles.tamano"
            @input="updateDetalle(index, 'tamano', $event.target.value)"
            type="text" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Color</label>
          <input 
            :value="servicio.detalles.color"
            @input="updateDetalle(index, 'color', $event.target.value)"
            type="text" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Modelo</label>
          <input 
            :value="servicio.detalles.modelo"
            @input="updateDetalle(index, 'modelo', $event.target.value)"
            type="text" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Tipo de Documento</label>
          <input 
            :value="servicio.detalles.tipoDocumento"
            @input="updateDetalle(index, 'tipoDocumento', $event.target.value)"
            type="text" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">Tipo de Evento</label>
          <input 
            :value="servicio.detalles.tipoEvento"
            @input="updateDetalle(index, 'tipoEvento', $event.target.value)"
            type="text" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400" 
          />
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-2">Descripción</label>
          <textarea 
            :value="servicio.detalles.descripcion"
            @input="updateDetalle(index, 'descripcion', $event.target.value)"
            rows="3" 
            class="w-full bg-[#0a192f]/50 rounded-md border border-white/20 shadow-md focus:ring-cyan-400 focus:border-cyan-400"
          ></textarea>
        </div>
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
  servicios: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['update:modelValue', 'servicio-change', 'servicio-added', 'servicio-removed', 'agregarServicio', 'eliminarServicio'])

const obtenerServicioInfo = (idServicio) => {
  return props.servicios.find(serv => serv.id == idServicio)
}

const calcularSubtotalBruto = (servicio) => {
  const precioOriginal = obtenerServicioInfo(servicio.idServicio)?.precioReferencial || 0
  const cantidad = parseInt(servicio.cantidad) || 0
  return precioOriginal * cantidad
}

const calcularSubtotal = (servicio) => {
  const subtotalBruto = calcularSubtotalBruto(servicio)
  const descuento = parseFloat(servicio.descuento) || 0
  return Math.max(0, subtotalBruto - descuento)
}

const onServicioChange = () => {
  // Los cambios se manejan automáticamente con v-model
  emit('servicio-change', props.modelValue.servicios)
}

const updateServicio = (index, campo, valor) => {
  const nuevosServicios = [...props.modelValue.servicios]
  nuevosServicios[index][campo] = valor
  emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
}

const updateDetalle = (index, campo, valor) => {
  const nuevosServicios = [...props.modelValue.servicios]
  nuevosServicios[index].detalles[campo] = valor
  emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
}

const agregarOtroServicio = () => {
  console.log('ServicioSection: emitir agregarServicio')
  emit('agregarServicio')
}

const eliminarServicio = (index) => {
  emit('eliminarServicio', index)
}

const validarCantidad = (index) => {
  const cantidad = props.modelValue.servicios[index].cantidad
  if (!cantidad || cantidad < 1) {
    const nuevosServicios = [...props.modelValue.servicios]
    nuevosServicios[index].cantidad = 1
    emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
  }
}

const validarDescuento = (index) => {
  const descuento = props.modelValue.servicios[index].descuento
  const subtotalBruto = calcularSubtotalBruto(props.modelValue.servicios[index])
  
  if (descuento === '' || descuento === null || descuento === undefined) {
    // Si está vacío, asignar 0
    const nuevosServicios = [...props.modelValue.servicios]
    nuevosServicios[index].descuento = 0
    emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
  } else if (descuento < 0) {
    // Si es negativo, asignar 0
    const nuevosServicios = [...props.modelValue.servicios]
    nuevosServicios[index].descuento = 0
    emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
  } else if (descuento >= subtotalBruto && subtotalBruto > 0) {
    // Si es mayor o igual al subtotal bruto, asignar subtotal - 0.01
    const nuevosServicios = [...props.modelValue.servicios]
    nuevosServicios[index].descuento = Math.max(0, subtotalBruto - 0.01)
    emit('update:modelValue', { ...props.modelValue, servicios: nuevosServicios })
  }
}
</script>
