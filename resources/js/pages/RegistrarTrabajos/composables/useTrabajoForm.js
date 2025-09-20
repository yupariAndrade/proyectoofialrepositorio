import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

export function useTrabajoForm(props) {
  // Formulario principal
  const form = useForm({
    cliente: props.clientePreSeleccionado?.id || '',
    servicios: [
      {
        idServicio: '',
        cantidad: 1,
        descuento: 0.00,
        detalles: {
          tamano: '',
          color: '',
          modelo: '',
          tipoDocumento: '',
          tipoEvento: '',
          descripcion: ''
        }
      }
    ],
    idResponsable: '',
    fechaEntrega: '',
    estadoTrabajo: props.estadosTrabajo?.[0]?.id || '',
    aCuenta: 0,
    idEstadoPago: props.estadosPago?.[0]?.id || ''
  })

  // Computed properties
  const clienteSeleccionado = computed(() => {
    return props.clientes?.find(cliente => cliente.id == form.cliente)
  })

  const totalCalculado = computed(() => {
    return form.servicios.reduce((total, servicio) => {
      return total + calcularSubtotal(servicio)
    }, 0)
  })

  const saldoCalculado = computed(() => {
    return Math.max(0, totalCalculado.value - (form.aCuenta || 0))
  })

  const isFormValid = computed(() => {
    const tieneServicios = form.servicios.some(servicio => 
      servicio.idServicio && servicio.cantidad > 0
    )
    // Responsable es opcional, solo validamos cliente, servicios y fecha de entrega
    return form.cliente && tieneServicios && form.fechaEntrega
  })

  // Helper functions
  const obtenerServicioInfo = (idServicio) => {
    return props.servicios?.find(serv => serv.id == idServicio)
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

  // Methods
  const onClienteChange = (nuevoCliente) => {
    form.cliente = nuevoCliente
    // Resetear campos relacionados
    form.servicio = ''
    // Resetear los detalles del primer servicio si existe
    if (form.servicios && form.servicios.length > 0 && form.servicios[0].detalles) {
      form.servicios[0].detalles.tamano = ''
      form.servicios[0].detalles.color = ''
      form.servicios[0].detalles.modelo = ''
      form.servicios[0].detalles.tipoDocumento = ''
      form.servicios[0].detalles.tipoEvento = ''
      form.servicios[0].detalles.descripcion = ''
    }
    form.aCuenta = 0
  }

  const onServicioChange = () => {
    // Recalcular totales cuando cambie un servicio
    // Los computed properties se actualizarán automáticamente
  }

  const agregarOtroServicio = () => {
    console.log('agregarOtroServicio llamado')
    const nuevoServicio = {
      idServicio: '',
      cantidad: 1,
      descuento: 0.00,
      detalles: {
        tamano: '',
        color: '',
        modelo: '',
        tipoDocumento: '',
        tipoEvento: '',
        descripcion: ''
      }
    }
    console.log('Servicios antes:', form.servicios.length)
    form.servicios = [...form.servicios, nuevoServicio]
    console.log('Servicios después:', form.servicios.length)
  }

  const eliminarServicio = (index) => {
    if (form.servicios.length > 1) {
      form.servicios = form.servicios.filter((_, i) => i !== index)
    }
  }

  const validarDescuento = (servicio) => {
    const subtotalBruto = calcularSubtotalBruto(servicio)
    if (servicio.descuento >= subtotalBruto) {
      servicio.descuento = subtotalBruto - 0.01
    }
  }

  const submitForm = () => {
    if (isFormValid.value) {
      form.post(route('registrar-trabajos.store'), {
        onSuccess: () => {
          // Éxito - se maneja en el componente padre
        },
        onError: (errors) => {
          console.error('Errores del servidor:', errors)
        }
      })
    }
  }

  return {
    form,
    clienteSeleccionado,
    totalCalculado,
    saldoCalculado,
    isFormValid,
    obtenerServicioInfo,
    calcularSubtotalBruto,
    calcularSubtotal,
    onClienteChange,
    onServicioChange,
    agregarOtroServicio,
    eliminarServicio,
    validarDescuento,
    submitForm
  }
}