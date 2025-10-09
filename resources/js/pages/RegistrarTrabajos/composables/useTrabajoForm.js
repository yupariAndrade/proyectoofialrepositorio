// ✅ VERSIÓN BACKEND - Cálculos desde el servidor
import { ref, computed, watch, nextTick } from 'vue'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

export function useTrabajoForm(props) {
  // Formulario principal
  const form = useForm({
    cliente: '',
    servicios: [
      {
        id: Date.now() + Math.random(), // ID único para reactividad
        idServicio: '',
        cantidad: 1,
        descuento: 0,
        detalles: {
          tamano: '',
          color: '',
          modelo: '',
          descripcion: ''
        }
      }
    ],
    fechaEntrega: '',
    idResponsable: null,
    aCuenta: 0,
    idEstadoPago: null,
  })

  // ✅ Estados reactivos para totales (vienen del backend)
  const totalCalculado = ref(0)
  const saldoCalculado = ref(0)
  const isLoading = ref(false)

  // ✅ Computed properties simples
  const clienteSeleccionado = computed(() => {
    return props.clientes?.find(c => c.id == form.cliente)
  })

  const usuarioSeleccionado = computed(() => {
    return props.usuarios?.find(u => u.id == form.idResponsable)
  })

  const isFormValid = computed(() => {
    return true // El backend valida
  })

  // ✅ Función para calcular totales desde el backend
  const calcularTotales = async () => {
    // Convertir aCuenta a número, usar 0 si está vacío
    const aCuentaParaEnvio = form.aCuenta === '' || form.aCuenta === null || form.aCuenta === undefined 
      ? 0 
      : parseFloat(form.aCuenta) || 0
    
    console.log('🔄 Calculando totales desde backend...', {
      servicios: form.servicios,
      aCuenta: aCuentaParaEnvio,
      aCuentaOriginal: form.aCuenta,
      aCuentaType: typeof form.aCuenta
    })
    
    isLoading.value = true
    try {
      const response = await axios.post('/calcular-total', {
        servicios: form.servicios,
        aCuenta: aCuentaParaEnvio
      })
      
      console.log('✅ Respuesta del backend:', response.data)
      totalCalculado.value = response.data.total
      saldoCalculado.value = response.data.saldo
    } catch (error) {
      console.error('❌ Error calculando totales:', error)
      totalCalculado.value = 0
      saldoCalculado.value = 0
    } finally {
      isLoading.value = false
    }
  }

  // ✅ Watchers simplificados para evitar recálculos excesivos
  let recalcularTimeout = null
  
  watch(() => form.servicios, () => {
    console.log('🔄 Servicios cambiaron - programando recálculo...')
    // Cancelar recálculo anterior si existe
    if (recalcularTimeout) {
      clearTimeout(recalcularTimeout)
    }
    // Programar recálculo con delay
    recalcularTimeout = setTimeout(() => {
      calcularTotales()
    }, 300)
  }, { deep: true })
  
  watch(() => form.aCuenta, (newValue, oldValue) => {
    console.log('🔄 useTrabajoForm: A Cuenta cambió de:', oldValue, 'a:', newValue, '- programando recálculo...')
    console.log('🔄 useTrabajoForm: form.aCuenta actual:', form.aCuenta, 'tipo:', typeof form.aCuenta)
    // Cancelar recálculo anterior si existe
    if (recalcularTimeout) {
      clearTimeout(recalcularTimeout)
    }
    // Programar recálculo con delay
    recalcularTimeout = setTimeout(() => {
      console.log('🔄 useTrabajoForm: Ejecutando calcularTotales() por cambio en aCuenta')
      calcularTotales()
    }, 300)
  })
  
  // ✅ Calcular totales al inicio
  console.log('🚀 Inicializando composable - llamando calcularTotales()')
  calcularTotales()

  // ✅ Funciones para UI
  const obtenerServicioInfo = (idServicio) => {
    return props.servicios?.find(s => s.id == idServicio)
  }

  const onClienteChange = (clienteId) => {
    console.log('🔄 onClienteChange ejecutándose con clienteId:', clienteId)
    form.cliente = clienteId
    form.servicios.forEach(servicio => {
      servicio.detalles.tamano = ''
      servicio.detalles.color = ''
      servicio.detalles.modelo = ''
      servicio.detalles.descripcion = ''
    })
  }

  const onServicioChange = () => {
    form.servicios.forEach(servicio => {
      servicio.detalles.tamano = ''
      servicio.detalles.color = ''
      servicio.detalles.modelo = ''
      servicio.detalles.descripcion = ''
    })
  }

  const agregarOtroServicio = () => {
    console.log('🔄 agregarOtroServicio ejecutándose...')
    console.log('🔄 Servicios actuales:', form.servicios.length)
    console.log('🔄 Estado actual del form:', {
      cliente: form.cliente,
      servicios: form.servicios,
      fechaEntrega: form.fechaEntrega,
      aCuenta: form.aCuenta
    })
    
    const nuevoServicio = {
      id: Date.now() + Math.random(), // ID único para reactividad
      idServicio: '',
      cantidad: 1,
      descuento: 0,
      detalles: {
        tamano: '',
        color: '',
        modelo: '',
        descripcion: ''
      }
    }
    
    // Agregar directamente al array existente (más simple y directo)
    form.servicios.push(nuevoServicio)
    
    console.log('✅ Nuevo servicio agregado. Total servicios:', form.servicios.length)
    console.log('✅ Servicios actualizados:', form.servicios)
  }

  const eliminarServicio = (index) => {
    if (form.servicios.length > 1) {
      form.servicios.splice(index, 1)
      console.log('✅ Servicio eliminado. Total servicios:', form.servicios.length)
    }
  }

  const validarDescuento = (servicio) => {
    // No hacer nada - el backend valida
  }

  const submitForm = () => {
    // Filtrar solo los servicios que tienen idServicio válido
    const serviciosValidos = form.servicios.filter(servicio => 
      servicio.idServicio && servicio.idServicio !== ''
    )
    
    // Limpiar el campo 'id' temporal antes de enviar
    const serviciosLimpios = serviciosValidos.map(servicio => {
      const { id, ...servicioSinId } = servicio
      return servicioSinId
    })
    
    console.log('🔄 Enviando formulario con servicios válidos:', serviciosLimpios)
    console.log('🔄 aCuenta antes de enviar:', form.aCuenta, 'tipo:', typeof form.aCuenta)
    console.log('🔄 idResponsable antes de enviar:', form.idResponsable, 'tipo:', typeof form.idResponsable)
    
    // Crear una copia del form con solo los servicios válidos
    const formData = {
      ...form.data(),
      servicios: serviciosLimpios,  // Asegurar que servicios se envíe
      cliente: form.cliente,  // Asegurar que cliente se envíe
      aCuenta: form.aCuenta || 0,  // Asegurar que aCuenta se envíe
      idResponsable: form.idResponsable || null,  // Asegurar que idResponsable se envíe
      idEstadoPago: form.idEstadoPago,  // Asegurar que idEstadoPago se envíe
      fechaEntrega: form.fechaEntrega  // Asegurar que fechaEntrega se envíe
    }
    
    console.log('🔄 Datos finales a enviar:', formData)
    
    form.transform(() => formData).post(route('registrar-trabajos.store'), {
      onSuccess: () => {
        // Éxito - se maneja en el componente padre
      },
      onError: (errors) => {
        console.error('Errores del servidor:', errors)
      }
    })
  }

  return {
    form,
    clienteSeleccionado,
    usuarioSeleccionado,
    totalCalculado,
    saldoCalculado,
    isLoading,
    isFormValid,
    obtenerServicioInfo,
    onClienteChange,
    onServicioChange,
    agregarOtroServicio,
    eliminarServicio,
    validarDescuento,
    submitForm,
    calcularTotales
  }
}
