<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              👁️ Detalles del Trabajo
            </h2>
            <p class="text-slate-300">Información completa del trabajo</p>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          <!-- Header con título y botones de acción -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Detalles del Trabajo</h2>
            <div class="flex justify-end space-x-4 pt-6">
              <Link 
                :href="route('registrar-trabajos.edit', trabajo.slug)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow"
              >
                Editar Trabajo
              </Link>
              <button 
                @click="confirmarEliminacion"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow"
              >
                Eliminar Trabajo
              </button>
              <Link 
                :href="route('registrar-trabajos')"
                class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded shadow"
              >
                Volver a la Lista
              </Link>
            </div>
          </div>

          <!-- Información del Cliente -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Información del Cliente
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Nombre Completo</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Número de Referencia</label>
                  <p class="text-lg font-medium text-white">
                    {{ trabajo.cliente?.telefono }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Información de los Servicios -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Servicios del Trabajo ({{ trabajo.detallesTrabajo?.length || 0 }})
              </h3>
              
              <!-- Lista de servicios -->
              <div v-if="trabajo.detallesTrabajo && trabajo.detallesTrabajo.length > 0" class="space-y-4">
                <div v-for="(detalle, index) in trabajo.detallesTrabajo" :key="index" 
                     class="bg-[#0a192f]/50 rounded-lg p-4 border border-white/10">
                  <div class="flex justify-between items-start mb-3">
                    <h4 class="text-md font-semibold text-cyan-300">Servicio {{ index + 1 }}</h4>
                    <span class="text-sm text-gray-400">ID: {{ detalle.id }}</span>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Servicio</label>
                      <p class="text-lg font-medium text-white">
                        {{ detalle.servicio?.nombreServicio }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Precio Unitario</label>
                      <div class="text-lg font-medium text-white">
                        <div v-if="detalle.descuento > 0" class="space-y-1">
                          <div class="text-red-400 line-through">
                            {{ detalle.servicio?.precioReferencial }} Bs
                          </div>
                          <div class="text-green-400">
                            {{ (detalle.servicio?.precioReferencial - detalle.descuento).toFixed(2) }} Bs
                          </div>
                          <div class="text-xs text-orange-400">
                            Descuento: -{{ detalle.descuento }} Bs
                          </div>
                        </div>
                        <div v-else>
                          {{ detalle.servicio?.precioReferencial }} Bs
                        </div>
                      </div>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Cantidad</label>
                      <p class="text-lg font-medium text-white">
                        {{ detalle.cantidad }} unidades
                      </p>
                    </div>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Subtotal</label>
                      <p class="text-xl font-bold text-green-400">
                        {{ calcularSubtotal(detalle) }} Bs
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Descuento</label>
                      <p class="text-lg text-orange-400">
                        {{ detalle.descuento || 0 }} Bs
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-8">
                <p class="text-gray-400">No hay servicios registrados para este trabajo</p>
              </div>
            </div>
          </div>

          <!-- Detalles Específicos de los Servicios -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Detalles Específicos de los Servicios
              </h3>
              
              <div v-if="trabajo.detallesTrabajo && trabajo.detallesTrabajo.length > 0" class="space-y-6">
                <div v-for="(detalle, index) in trabajo.detallesTrabajo" :key="index" 
                     class="bg-[#0a192f]/50 rounded-lg p-4 border border-white/10">
                  <div class="flex justify-between items-center mb-4">
                    <h4 class="text-md font-semibold text-purple-300">
                      {{ detalle.servicio?.nombreServicio }}
                    </h4>
                    <span class="text-sm text-gray-400">Servicio {{ index + 1 }}</span>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Tamaño</label>
                      <p class="text-white">
                        {{ detalle.tamano || 'No especificado' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Color</label>
                      <p class="text-white">
                        {{ detalle.color || 'No especificado' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Modelo</label>
                      <p class="text-white">
                        {{ detalle.modelo || 'No especificado' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Documento</label>
                      <p class="text-white">
                        {{ detalle.tipoDocumento || 'No especificado' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Tipo de Evento</label>
                      <p class="text-white">
                        {{ detalle.tipoEvento || 'No especificado' }}
                      </p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-400 mb-1">Cantidad</label>
                      <p class="text-white font-semibold">
                        {{ detalle.cantidad }} unidades
                      </p>
                    </div>
                  </div>
                  
                  <div v-if="detalle.descripcion" class="mt-4">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Descripción</label>
                    <p class="text-white bg-[#0a192f]/30 p-3 rounded-lg">
                      {{ detalle.descripcion }}
                    </p>
                  </div>
                </div>
              </div>
              
              <div v-else class="text-center py-8">
                <p class="text-gray-400">No hay detalles específicos registrados</p>
              </div>
            </div>
          </div>

          <!-- Información del Trabajo -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Información del Trabajo
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Fecha de Registro</label>
                  <p class="text-lg font-medium text-white">
                    {{ formatDate(trabajo.fechaRegistro) }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Fecha de Entrega</label>
                  <p class="text-lg font-medium text-white">
                    {{ formatDate(trabajo.fechaEntrega) }}
                  </p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Estado del Trabajo</label>
                  <span :class="getEstadoTrabajoClass(trabajo.estado?.nombre)">
                    {{ trabajo.estado?.nombre }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Información del Responsable -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Información del Responsable
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Responsable Asignado</label>
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center">
                      <span class="text-white font-bold text-sm">{{ trabajo.responsable?.nombre?.charAt(0) || 'R' }}</span>
                    </div>
                    <div>
                      <p class="font-medium text-white">
                        {{ trabajo.responsable?.nombre }} {{ trabajo.responsable?.apellidoPaterno }} {{ trabajo.responsable?.apellidoMaterno }}
                      </p>
                      <p class="text-sm text-cyan-400">{{ trabajo.responsable?.rol?.nombre }}</p>
                    </div>
                  </div>
                </div>
                <!--<div>
                  <label class="block text-sm font-medium text-gray-400 mb-1">Estado del Responsable</label>
                  <span :class="trabajo.responsable?.estado ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ trabajo.responsable?.estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>-->
              </div>
            </div>
          </div>

          <!-- Información del Pago -->
          <div class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow mb-6 overflow-hidden border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                💰 Estado de Pagos
              </h3>
              
              <!-- Resumen de costos por servicio -->
              <div v-if="trabajo.detallesTrabajo && trabajo.detallesTrabajo.length > 0" class="mb-6">
                <h4 class="text-md font-semibold text-gray-300 mb-3">Desglose por Servicio</h4>
                <div class="space-y-2">
                  <div v-for="(detalle, index) in trabajo.detallesTrabajo" :key="index" 
                       class="bg-[#0a192f]/30 p-3 rounded-lg">
                    <div class="flex justify-between items-center">
                      <div>
                        <span class="text-white font-medium">{{ detalle.servicio?.nombreServicio }}</span>
                        <span class="text-gray-400 text-sm ml-2">({{ detalle.cantidad }} unidades)</span>
                      </div>
                      <span class="text-green-400 font-semibold">{{ calcularSubtotal(detalle).toFixed(2) }} Bs</span>
                    </div>
                    <div v-if="detalle.descuento > 0" class="mt-2 text-xs text-orange-400">
                      Precio original: {{ (detalle.servicio?.precioReferencial * detalle.cantidad).toFixed(2) }} Bs - 
                      Descuento: {{ (detalle.descuento * detalle.cantidad).toFixed(2) }} Bs
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Información actualizada del sistema de cuotas -->
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-[#0a192f]/50 p-4 rounded-lg border border-white/10">
                  <label class="block text-sm font-medium text-gray-400 mb-1">Total del Trabajo</label>
                  <p class="text-2xl font-bold text-white">
                    {{ trabajo.total || totalGeneralCalculado }} Bs
                  </p>
                </div>
                <div class="bg-[#0a192f]/50 p-4 rounded-lg border border-white/10">
                  <label class="block text-sm font-medium text-gray-400 mb-1">Ya Pagado</label>
                  <p class="text-2xl font-bold text-green-400">
                    {{ trabajo.aCuenta || 0 }} Bs
                  </p>
                </div>
                <div class="bg-[#0a192f]/50 p-4 rounded-lg border border-white/10">
                  <label class="block text-sm font-medium text-gray-400 mb-1">Saldo Pendiente</label>
                  <p class="text-2xl font-bold" :class="getSaldoClass(trabajo.saldo || saldoCalculado)">
                    {{ trabajo.saldo || saldoCalculado }} Bs
                  </p>
                </div>
                <div class="bg-[#0a192f]/50 p-4 rounded-lg border border-white/10">
                  <label class="block text-sm font-medium text-gray-400 mb-1">Estado del Pago</label>
                  <span :class="getEstadoPagoClass(trabajo.estadoPago?.nombre)" class="text-lg font-semibold">
                    {{ trabajo.estadoPago?.nombre || 'Sin pago' }}
                  </span>
                </div>
              </div>
              
              <!-- Botón para agregar cuota (si hay saldo pendiente) -->
              <div v-if="(trabajo.saldo || saldoCalculado) > 0" class="text-center">
                <button 
                  @click="mostrarFormularioCuota"
                  class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-3 rounded-lg font-medium hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 flex items-center mx-auto"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                  Agregar Cuota
                </button>
              </div>
            </div>
          </div>

          <!-- Historial de Cuotas -->
          <div v-if="historialPagos && historialPagos.length > 0" class="bg-[#0c1d3a]/70 backdrop-blur-sm rounded-lg shadow overflow-hidden mb-6 border border-white/10">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4 text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                📋 Historial de Cuotas
              </h3>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                  <thead class="bg-[#0a192f]/50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        #
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Fecha
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Monto de Cuota
                      </th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                        Saldo Después
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-[#0a192f]/30 divide-y divide-gray-700">
                    <tr v-for="(pago, index) in historialPagos" :key="pago.idPago" class="hover:bg-[#0a192f]/50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ index + 1 }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ formatDate(pago.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-400">
                        +{{ pago.aCuenta }} Bs
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        {{ pago.saldo }} Bs
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div v-if="mostrarModalEliminacion" class="fixed inset-0 bg-black bg-opacity-70 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-gray-900 text-gray-200">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-800">
            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-white mt-4">Confirmar Eliminación</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-400">
              ¿Estás seguro de que quieres eliminar este trabajo?
            </p>
            <p class="text-sm text-gray-300 mt-2">
              <span class="font-semibold text-white">{{ trabajo?.cliente?.nombre }} {{ trabajo?.cliente?.apellido }}</span> - 
              <span class="font-semibold text-white">{{ trabajo?.detallesTrabajo?.[0]?.servicio?.nombreServicio || 'Sin servicio' }}</span>
            </p>
            <p class="text-xs text-red-400 mt-2">
              ⚠️ Esta acción no se puede deshacer
            </p>
          </div>
          <div class="flex justify-center space-x-3 mt-4">
            <button 
              @click="cancelarEliminacion"
              class="px-4 py-2 bg-gray-700 text-gray-200 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
              Cancelar
            </button>
            <button 
              @click="eliminarTrabajo"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para Agregar Cuota -->
    <div v-if="mostrarCuota" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-[#1a1a2e] rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold text-white">💰 Agregar Cuota</h3>
          <button @click="cerrarModalCuota" class="text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="space-y-4">
          <!-- Información del trabajo -->
          <div class="bg-[#0c1d3a] p-4 rounded-lg">
            <h4 class="text-lg font-semibold text-white mb-3">📋 Información del Trabajo</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
              <div>
                <span class="text-gray-400">Cliente:</span>
                <p class="text-white font-medium">{{ trabajo.cliente?.nombre }}</p>
              </div>
              <div>
                <span class="text-gray-400">Total:</span>
                <p class="text-white font-medium">{{ trabajo.total || totalGeneralCalculado }} Bs</p>
              </div>
              <div>
                <span class="text-gray-400">Ya pagado:</span>
                <p class="text-white font-medium">{{ trabajo.aCuenta || 0 }} Bs</p>
              </div>
              <div>
                <span class="text-gray-400">Saldo pendiente:</span>
                <p class="text-white font-medium">{{ trabajo.saldo || saldoCalculado }} Bs</p>
              </div>
            </div>
          </div>
          
          <!-- Formulario de cuota -->
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-white mb-2">Monto de la cuota:</label>
              <input 
                v-model="montoCuota" 
                type="number" 
                :max="trabajo.saldo || saldoCalculado"
                step="0.01"
                placeholder="Ej: 50.00"
                class="w-full bg-[#0a192f] text-white border border-cyan-500 rounded-md px-3 py-2 focus:ring-cyan-400 focus:border-cyan-400"
              >
              <small class="text-gray-400">Máximo: {{ trabajo.saldo || saldoCalculado }} Bs</small>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-white mb-2">Estado de pago (opcional):</label>
              <select v-model="nuevoEstadoPago" class="w-full bg-[#0a192f] text-white border border-cyan-500 rounded-md px-3 py-2 focus:ring-cyan-400 focus:border-cyan-400">
                <option value="">Mantener estado actual</option>
                <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                  {{ estado.nombre }}
                </option>
              </select>
            </div>
          </div>
          
          <!-- Botones -->
          <div class="flex space-x-3 pt-4">
            <button @click="cerrarModalCuota" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors">
              Cancelar
            </button>
            <button 
              @click="procesarCuota" 
              :disabled="!montoCuota || parseFloat(montoCuota) <= 0 || parseFloat(montoCuota) > (trabajo.saldo || saldoCalculado)"
              class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-4 py-2 rounded-md hover:from-yellow-600 hover:to-orange-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              💰 Procesar Cuota
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppShell>
</template>


<script setup>
import { ref, computed } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  trabajo: Object,
  historialPagos: Array,
  estadosPago: Array,
})

// Acceder a los mensajes flash
const page = usePage()

// Estado para eliminación
const mostrarModalEliminacion = ref(false)

// Variables para el modal de cuota
const mostrarCuota = ref(false)
const montoCuota = ref('')
const nuevoEstadoPago = ref('')

// Debug: Verificar si el componente se está ejecutando
console.log('🚀 Show.vue se está ejecutando')
console.log('🚀 Props recibidos:', props)
console.log('🚀 Trabajo completo:', props.trabajo)
console.log('🚀 DetallesTrabajo:', props.trabajo?.detallesTrabajo)
console.log('🚀 DetallesTrabajo length:', props.trabajo?.detallesTrabajo?.length)
console.log('🚀 Primer detalle:', props.trabajo?.detallesTrabajo?.[0])

// Computed properties
const trabajo = computed(() => {
  console.log('🔍 Datos del trabajo recibidos:', props.trabajo)
  console.log('🔍 Detalles del trabajo:', props.trabajo?.detallesTrabajo)
  console.log('🔍 Detalles del trabajo length:', props.trabajo?.detallesTrabajo?.length)
  console.log('🔍 Servicios:', props.trabajo?.detallesTrabajo?.map(d => d.servicio))
  console.log('🔍 Pago:', props.trabajo?.detallesTrabajo?.[0]?.pago)
  console.log('🔍 Estado del pago:', props.trabajo?.estadoPago)
  console.log('🔍 Cliente:', props.trabajo?.cliente)
  console.log('🔍 Estado:', props.trabajo?.estado)
  return props.trabajo
})


// Calcular subtotal de un servicio
const calcularSubtotal = (detalle) => {
  if (!detalle || !detalle.servicio) return 0
  const precioUnitario = detalle.servicio.precioReferencial || 0
  const cantidad = detalle.cantidad || 1
  const descuento = detalle.descuento || 0
  return (precioUnitario - descuento) * cantidad
}

// Calcular total general de todos los servicios
const totalGeneralCalculado = computed(() => {
  if (!trabajo.value?.detallesTrabajo) return 0
  return trabajo.value.detallesTrabajo.reduce((total, detalle) => {
    return total + calcularSubtotal(detalle)
  }, 0)
})

// Calcular saldo
const saldoCalculado = computed(() => {
  const total = totalGeneralCalculado.value
  const aCuenta = trabajo.value?.detallesTrabajo?.[0]?.pago?.aCuenta || 0
  return Math.max(0, total - aCuenta)
})

// Historial de pagos
const historialPagos = computed(() => {
  return props.historialPagos || []
})

// Métodos
const formatDate = (date) => {
  if (!date) return 'No definida'
  return new Date(date).toLocaleDateString('es-ES')
}

const getEstadoTrabajoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full',
    'En Proceso': 'px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full',
    'Completado': 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full',
    'Entregado': 'px-3 py-1 text-sm font-medium bg-purple-100 text-purple-800 rounded-full',
    'Cancelado': 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full',
  }
  return classes[estado] || 'px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full'
}

const getEstadoPagoClass = (estado) => {
  const classes = {
    'Pendiente': 'px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full',
    'Parcial': 'px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-full',
    'Completado': 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full',
    'Cancelado': 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full',
  }
  return classes[estado] || 'px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full'
}

const getSaldoClass = (saldo) => {
  if (saldo === 0) return 'text-green-600'
  if (saldo > 0) return 'text-red-600'
  return 'text-gray-900'
}

// Métodos de eliminación
const confirmarEliminacion = () => {
  mostrarModalEliminacion.value = true
}

const cancelarEliminacion = () => {
  mostrarModalEliminacion.value = false
}

const eliminarTrabajo = () => {
  if (trabajo.value) {
    const form = useForm({
      _method: 'DELETE',
    })

    form.post(route('registrar-trabajos.destroy', trabajo.value.slug), {
      onSuccess: () => {
        mostrarModalEliminacion.value = false
        // Usar Inertia para redirigir en lugar de window.location.href
        router.visit(route('registrar-trabajos'))
      },
      onError: (errors) => {
        console.error('Error al eliminar el trabajo:', errors)
        // Mostrar error más específico
        let errorMessage = 'Error al eliminar el trabajo. Por favor, inténtalo de nuevo.'
        
        if (errors.error) {
          errorMessage = errors.error
        } else if (errors.message) {
          errorMessage = errors.message
        } else if (typeof errors === 'string') {
          errorMessage = errors
        }
        
        alert(errorMessage)
      },
    })
  }
}

// Funciones para el modal de cuota
const mostrarFormularioCuota = () => {
  montoCuota.value = ''
  nuevoEstadoPago.value = ''
  mostrarCuota.value = true
}

const cerrarModalCuota = () => {
  mostrarCuota.value = false
  montoCuota.value = ''
  nuevoEstadoPago.value = ''
}

const procesarCuota = async () => {
  if (!montoCuota.value) return
  
  try {
    const response = await fetch(`/trabajos/${props.trabajo.id}/cuota`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        monto: parseFloat(montoCuota.value),
        nuevoEstadoPago: nuevoEstadoPago.value || null
      })
    })
    
    if (response.ok) {
      const data = await response.json()
      if (data.success) {
        // Recargar la página para mostrar los datos actualizados
        window.location.reload()
      }
    } else {
      alert('❌ Error al procesar la cuota')
    }
  } catch (error) {
    console.error('Error al procesar cuota:', error)
    alert('❌ Error al procesar la cuota')
  }
}
</script>
