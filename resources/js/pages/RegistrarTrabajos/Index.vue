<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1 relative overflow-hidden">
      <!-- Background Effects -->
      <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-orange-500/20 to-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-cyan-500/20 to-blue-600/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-emerald-500/10 to-teal-600/10 rounded-full blur-3xl animate-pulse delay-500"></div>
      </div>

      <!-- Header Section with Glass Effect -->
      <div class="relative z-10 bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-xl border-b border-white/10 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Title Section -->
            <div class="flex items-center space-x-4">
              <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 via-pink-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-orange-500/25 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
                </div>
                <div class="absolute -inset-1 bg-gradient-to-r from-orange-400 to-purple-600 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-300"></div>
            </div>
            <div>
                <h1 class="text-4xl lg:text-5xl font-extrabold bg-gradient-to-r from-orange-400 via-pink-500 to-purple-600 bg-clip-text text-transparent mb-2">
                  📋 Trabajos Registrados
              </h1>
                <p class="text-slate-300 text-lg">✨ Gestión completa de trabajos y estados</p>
              </div>
            </div>

            <!-- Action Button -->
            <div class="flex flex-col sm:flex-row gap-4">
              <button @click="crearNuevoTrabajo" class="group relative bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:from-orange-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-2xl shadow-orange-500/25 flex items-center justify-center">
                <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-6 h-6 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
               </svg>
                <span class="relative z-10">+ Nuevo Trabajo</span>
             </button>
           </div>
        </div>
      </div>
    </div>

      <!-- Success Message with Glass Effect -->
      <div v-if="successMessage" class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 backdrop-blur-xl border border-green-400/30 rounded-2xl p-6 flex items-center shadow-2xl shadow-green-500/10">
          <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mr-4 shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
          </div>
          <div>
            <h3 class="text-green-400 font-bold text-lg">¡Éxito!</h3>
            <p class="text-green-300">{{ successMessage }}</p>
          </div>
        </div>
      </div>

      <!-- Filters Section with Glass Effect -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-white/10 p-8 mb-8 shadow-2xl">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
            <div class="flex items-center space-x-3 mb-4 lg:mb-0">
              <div class="w-10 h-10 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
              </div>
              <h2 class="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">🔍 Filtros de Búsqueda</h2>
    </div>

            <!-- View Toggles with Enhanced Design -->
            <div class="flex flex-col sm:flex-row gap-3">
            <button 
              @click="vistaActual = 'individual'" 
              :class="[
                  'group relative px-6 py-3 rounded-xl font-bold flex items-center justify-center transition-all duration-300 transform hover:scale-105',
                vistaActual === 'individual' 
                    ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white shadow-2xl shadow-orange-500/25' 
                    : 'bg-slate-700/50 text-slate-300 hover:bg-slate-600/50 backdrop-blur-sm border border-slate-600/50'
              ]"
            >
                <div v-if="vistaActual === 'individual'" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
                <span class="relative z-10">Vista Individual</span>
            </button>
            <button 
              @click="vistaActual = 'consolidada'" 
              :class="[
                  'group relative px-6 py-3 rounded-xl font-bold flex items-center justify-center transition-all duration-300 transform hover:scale-105',
                vistaActual === 'consolidada' 
                    ? 'bg-gradient-to-r from-orange-500 to-pink-500 text-white shadow-2xl shadow-orange-500/25' 
                    : 'bg-slate-700/50 text-slate-300 hover:bg-slate-600/50 backdrop-blur-sm border border-slate-600/50'
              ]"
            >
                <div v-if="vistaActual === 'consolidada'" class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
                <span class="relative z-10">Vista Consolidada</span>
            </button>
          </div>
        </div>

          <!-- Búsqueda Unificada -->
          <div class="max-w-2xl mx-auto">
            <div class="group">
              <label class="flex text-sm font-bold text-slate-300 mb-3 items-center justify-center">
                <div class="w-6 h-6 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center mr-2">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
                🔍 Búsqueda Unificada
              </label>
              <div class="relative">
                <input 
                  v-model="filtros.busquedaUnificada"
                  type="text" 
                  placeholder="Buscar por cliente, estado de trabajo, estado de pago..."
                  class="w-full bg-slate-700/50 backdrop-blur-sm border border-slate-600/50 rounded-xl px-4 py-3 pl-12 text-slate-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-300 hover:bg-slate-600/50"
                  @input="aplicarFiltros"
                />
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
                <div v-if="filtros.busquedaUnificada" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                  <button 
                    @click="limpiarBusqueda"
                    class="text-slate-400 hover:text-slate-200 transition-colors duration-200"
                    title="Limpiar búsqueda"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
          </div>
              <p class="text-xs text-slate-400 mt-2 text-center">
                💡 Busca por nombre de cliente, estado de trabajo o estado de pago
              </p>
          </div>
          </div>
        </div>
      </div>

      <!-- Table Section with Glass Effect -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700/50">
              <thead class="bg-gradient-to-r from-slate-700/50 to-slate-800/50 backdrop-blur-sm">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  👤 CLIENTE
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  📋 SERVICIO
                 </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  👨‍💼 RESPONSABLE
                 </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  🔢 CANTIDAD
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  💰 TOTAL
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  💳 A CUENTA
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  ⚖️ SALDO
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  📅 FECHA ENTREGA
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  🏷️ ESTADO TRABAJO
                </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  💳 ESTADO PAGO
                 </th>
                <th class="px-6 py-4 text-left text-sm font-bold text-slate-200 uppercase tracking-wider">
                  ⚡ ACCIONES
                 </th>
              </tr>
            </thead>
            <tbody class="bg-gradient-to-br from-slate-800/20 to-slate-900/20 divide-y divide-slate-600/20">
              <tr v-for="(trabajo, index) in trabajosFiltrados" :key="trabajo.id" 
                  :class="[
                    'group transition-all duration-300 transform hover:scale-[1.02] hover:shadow-2xl',
                    index % 2 === 0 
                      ? 'bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-cyan-500/10 hover:from-emerald-500/20 hover:via-teal-500/20 hover:to-cyan-500/20' 
                      : 'bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-rose-500/10 hover:from-purple-500/20 hover:via-pink-500/20 hover:to-rose-500/20'
                  ]">
                                 <!-- CLIENTE -->
                 <td class="px-6 py-4 whitespace-nowrap">
                   <div>
                    <div class="text-sm font-bold text-white group-hover:text-blue-100 transition-colors duration-300">
                      {{ trabajo.cliente?.nombre || 'Sin nombre' }} {{ trabajo.cliente?.apellido || '' }}
                     </div>
                    <div class="text-xs text-blue-300 mt-1">
                       Nr. Ref: 
                     </div>
                    <div v-if="trabajo.cliente?.telefono" class="text-xs text-blue-200 flex items-center mt-1">
                      <span class="mr-1">📞</span> {{ trabajo.cliente.telefono }}
                     </div>
                   </div>
                 </td>

                                 <!-- SERVICIO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div v-for="servicio in trabajo.servicios || []" :key="servicio.id" class="mb-1 last:mb-0">
                    <div class="text-sm font-bold text-white group-hover:text-emerald-100 transition-colors duration-300">
                      {{ servicio?.nombreServicio || 'Sin nombre' }}
                     </div>
                    <div class="text-xs text-emerald-300 mt-1">
                      <span class="font-medium">{{ servicio.cantidad || 1 }} × {{ formatPrecio(servicio.precio) }} Bs</span>
                       </div>
                    <div v-if="getDescuento(servicio) > 0" class="text-xs mt-1">
                      <span class="text-red-300 line-through block">
                           Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                         </span>
                      <span class="text-orange-300 block">
                           -{{ formatPrecio(getDescuento(servicio)) }} Bs desc.
                         </span>
                      <span class="text-green-300 font-bold">
                           Final: {{ formatPrecio(getSubtotalFinal(servicio)) }} Bs
                         </span>
                       </div>
                    <div v-else class="text-green-300 font-medium text-xs mt-1">
                         Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                     </div>
                   </div>
                 </td>

                                   <!-- RESPONSABLE -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="trabajo.responsable" class="flex items-center">
                      <!-- Círculo con inicial del responsable -->
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0 shadow-lg group-hover:shadow-cyan-500/50 transition-all duration-300">
                        <span class="text-white font-bold text-sm">
                        {{ getInitial(trabajo.responsable?.nombre || '') }}
                        </span>
                      </div>
                      <!-- Información del responsable -->
                      <div>
                      <div class="text-sm font-bold text-white group-hover:text-cyan-100 transition-colors duration-300">
                        {{ trabajo.responsable?.nombre || 'Sin nombre' }} {{ trabajo.responsable?.apellido || '' }}
                        </div>
                      <div class="text-xs text-cyan-300">
                        {{ trabajo.responsable?.rol || 'Sin rol' }}
                      </div>
                    </div>
                  </div>
                  <div v-else class="text-sm text-gray-300 italic">
                      No asignado
                    </div>
                  </td>

                <!-- CANTIDAD -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="text-sm font-medium text-white group-hover:text-orange-100 transition-colors duration-300">
                      {{ getServiciosConCantidad(trabajo) }}
                    </div>
                    <div class="text-xs text-orange-300">Servicios</div>
                  </div>
                </td>

                                 <!-- TOTAL -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="text-lg font-bold text-white group-hover:text-green-100 transition-colors duration-300">
                      {{ formatPrecio(trabajo.total || 0) }} Bs
                    </div>
                    <div class="text-xs text-green-300">Total</div>
                   </div>
                 </td>

                 <!-- A CUENTA -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="text-lg font-bold text-white group-hover:text-blue-100 transition-colors duration-300">
                      {{ formatPrecio(trabajo.aCuenta || 0) }} Bs
                    </div>
                    <div class="text-xs text-blue-300">A Cuenta</div>
                   </div>
                 </td>

                 <!-- SALDO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="text-lg font-bold text-white transition-colors duration-300" 
                         :class="getSaldoTrabajo(trabajo) > 0 ? 'group-hover:text-red-100' : 'group-hover:text-green-100'">
                      {{ formatPrecio(getSaldoTrabajo(trabajo)) }} Bs
                    </div>
                    <div class="text-xs transition-colors duration-300" 
                         :class="getSaldoTrabajo(trabajo) > 0 ? 'text-red-300' : 'text-green-300'">
                      Saldo
                    </div>
                   </div>
                 </td>

                <!-- FECHA ENTREGA -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="text-sm font-bold text-white group-hover:text-purple-100 transition-colors duration-300">
                      {{ formatDate(trabajo.fechaEntrega || '') }}
                    </div>
                    <div class="text-xs text-purple-300">Entrega</div>
                  </div>
                </td>

                <!-- ESTADO TRABAJO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                    <div class="relative inline-block">
                      <!-- Botón principal con color dinámico -->
                      <button 
                        @click="toggleEstadoDropdown(trabajo.id)"
                        class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full shadow-lg transition-all duration-300 transform hover:scale-105"
                        :class="getEstadoTrabajoButtonClass(trabajo.idEstado)"
                      >
                        <span>{{ getEstadoTrabajoNombre(trabajo.idEstado) }}</span>
                        <svg class="w-3 h-3 ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                      </button>
                      
                      <!-- Dropdown mejorado -->
                      <div 
                        v-show="estadoDropdownAbierto === trabajo.id"
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-48 bg-slate-800/95 backdrop-blur-sm border border-slate-600/50 rounded-xl shadow-2xl z-[9999] overflow-hidden"
                        @mouseleave="cerrarEstadoDropdown"
                      >
                        <div class="py-1">
                          <div 
                            v-for="estado in estadosTrabajo" 
                            :key="estado.id"
                            @click="cambiarEstadoTrabajo(trabajo, estado.id)"
                            class="px-4 py-3 text-sm cursor-pointer transition-all duration-200 hover:bg-slate-700/50 flex items-center justify-between group"
                            :class="estado.id === trabajo.idEstado ? 'bg-slate-700/30' : ''"
                          >
                            <span class="text-white font-medium">{{ estado.nombre }}</span>
                            <div 
                              class="w-2 h-2 rounded-full transition-all duration-200"
                              :class="getEstadoTrabajoColorClass(estado.id)"
                            ></div>
                          </div>
                        </div>
                        
                        <!-- Flecha decorativa -->
                        <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-slate-800/95 border-l border-t border-slate-600/50 rotate-45"></div>
                      </div>
                    </div>
                  </div>
                </td>

                                 <!-- ESTADO PAGO -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-center">
                   <span 
                      class="inline-flex px-3 py-1 text-xs font-bold rounded-full shadow-lg"
                      :class="getEstadoPagoClass(trabajo.estadoPago?.nombre || 'Sin estado')"
                    >
                      {{ trabajo.estadoPago?.nombre || 'Sin estado' }}
                   </span>
                  </div>
                 </td>

                                   <!-- ACCIONES -->
                 <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex gap-1 justify-center">
                     <!-- Botón Ver Detalle -->
                     <button 
                       @click="verDetalleTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-blue-500 to-cyan-600 text-white p-2 rounded-lg hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 transform hover:scale-110 shadow-lg shadow-blue-500/25"
                       title="Ver detalle"
                     >
                      <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                       </svg>
                     </button>
                     
                     <!-- Botón Editar -->
                     <button 
                       @click="editarTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-emerald-500 to-green-600 text-white p-2 rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all duration-300 transform hover:scale-110 shadow-lg shadow-emerald-500/25"
                       title="Editar trabajo"
                     >
                      <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                       </svg>
                     </button>
                     
                    <!-- Botón Pagos en Cuotas -->
                     <button 
                      @click="abrirModalPagos(trabajo)"
                      class="group relative bg-gradient-to-r from-green-500 to-emerald-600 text-white p-2 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-110 shadow-lg shadow-green-500/25"
                      title="Pagos en cuotas"
                      v-if="getSaldoTrabajo(trabajo) > 0"
                    >
                      <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                       </svg>
                     </button>
                     
                     <!-- Botón Eliminar -->
                     <button 
                       @click="eliminarTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-red-500 to-pink-600 text-white p-2 rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-110 shadow-lg shadow-red-500/25"
                       title="Eliminar trabajo"
                     >
                      <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                       </svg>
                     </button>
                   </div>
                 </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State with Enhanced Design -->
        <div v-if="trabajosFiltrados.length === 0" class="text-center py-16">
          <div class="relative mx-auto w-24 h-24 mb-6">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-purple-600/20 rounded-full blur-xl"></div>
            <div class="relative w-24 h-24 bg-gradient-to-r from-orange-400 to-purple-500 rounded-full flex items-center justify-center shadow-2xl">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            </div>
          </div>
          <h3 class="text-2xl font-bold bg-gradient-to-r from-orange-400 to-purple-500 bg-clip-text text-transparent mb-3">📋 No se encontraron trabajos</h3>
          <p class="text-slate-300 text-lg mb-6 max-w-md mx-auto">
            {{ filtros.busquedaUnificada ? 
               '🔍 Intenta ajustar la búsqueda para encontrar más resultados.' : 
               '✨ Comienza creando tu primer trabajo y gestiona todos tus proyectos desde aquí.' }}
          </p>
          <button @click="crearNuevoTrabajo" class="group relative bg-gradient-to-r from-orange-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:from-orange-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-2xl shadow-orange-500/25 flex items-center mx-auto">
            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <svg class="w-6 h-6 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="relative z-10">+ Crear Primer Trabajo</span>
          </button>
      </div>
    </div>
    </div>

    <!-- Modal Agregar Cuota -->
    <ModalAgregarCuota 
      :mostrar="mostrarModalCuota"
      :trabajo="trabajoSeleccionado"
      :estados-pago="estadosPago"
      @cerrar="cerrarModalCuota"
      @cuota-procesada="onCuotaProcesada"
    />

    <!-- Modal Cancelar Trabajo -->
    <ModalCancelarTrabajo 
      v-if="mostrarModalCancelacion"
      :trabajo="trabajoParaCancelar"
      @cerrar="cerrarModalCancelacion"
      @cancelado="onTrabajoCancelado"
    />
    
    <!-- Debug: Mostrar estado del modal -->
    <div v-if="mostrarModalCancelacion" class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg z-[9999]">
      Modal de cancelación activo: {{ mostrarModalCancelacion }}
    </div>
  </div>
  </AppShell>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import ModalAgregarCuota from './components/ModalAgregarCuota.vue'
import ModalCancelarTrabajo from './components/ModalCancelarTrabajo.vue'

// Obtener la página actual
const page = usePage()

// Computed para el mensaje de éxito
const successMessage = computed(() => {
  return (page.props as any).flash?.success || null
})

// Tipos de datos
interface Cliente {
  id: number
  nombre: string
  apellido: string
  email?: string
  telefono?: string
  direccion?: string
}

interface Servicio {
  id: number
  nombreServicio: string
  precio: number
  descuento?: number
  descripcion?: string
  cantidad?: number
  tamano?: string
  color?: string
  modelo?: string
}

interface EstadoTrabajo {
  id: number
  nombre: string
}

interface EstadoPago {
  id: number
  nombre: string
}

interface Responsable {
  id: number
  nombre: string
  apellido: string
  rol: string
}

interface Trabajo {
  id: number
  idCliente: number
  idResponsable?: number
  fechaRegistro: string
  fechaEntrega?: string
  idEstado: number
  idEstadoPago: number
  slug: string
  cliente: Cliente
  estado: EstadoTrabajo
  estadoTrabajo: EstadoTrabajo
  estadoPago: EstadoPago
  responsable?: Responsable
  servicios: Servicio[]
  totalTrabajo: number
  total: number
  cantidad: number
  aCuenta: number
  saldo: number
  pagado: number
}

// Props del componente
interface Props {
  trabajos: Trabajo[]
  clientes: Cliente[]
  estadosTrabajo: EstadoTrabajo[]
  estadosPago: EstadoPago[]
}

const props = defineProps<Props>()

// Estado reactivo
const vistaActual = ref<'consolidada' | 'individual'>('individual')
const filtros = ref({
  busquedaUnificada: ''
})

// Estado del modal de agregar cuota
const mostrarModalCuota = ref(false)
const trabajoSeleccionado = ref<any>(null)

// ✅ Estado para controlar modal de cancelación
const mostrarModalCancelacion = ref(false)
const trabajoParaCancelar = ref<any>(null)

// Debug: Watcher para mostrarModalCancelacion
watch(mostrarModalCancelacion, (newValue) => {
  console.log('🔍 mostrarModalCancelacion cambió a:', newValue);
  if (newValue) {
    console.log('🔍 trabajoParaCancelar:', trabajoParaCancelar.value);
    console.log('🔍 DOM - Elemento modal existe:', document.querySelector('.fixed.inset-0.bg-black'));
  }
});

// Debug: Log de estados de trabajo al cargar
onMounted(() => {
  console.log('🔍 Estados de trabajo disponibles:', props.estadosTrabajo);
  console.log('🔍 Buscando estado "Cancelado" (id: 5):', props.estadosTrabajo.find(e => e.id === 5));
  console.log('🔍 Trabajos cargados:', props.trabajos.length);
  
  // Debug: Verificar responsables
  console.log('🔍 Verificando responsables en Index.vue:')
  props.trabajos.forEach((trabajo, index) => {
    if (index < 5) { // Solo los primeros 5
      console.log(`Trabajo ${trabajo.id}:`, {
        idResponsable: trabajo.idResponsable,
        responsable: trabajo.responsable,
        responsable_nombre: trabajo.responsable?.nombre,
        responsable_apellido: trabajo.responsable?.apellido,
        responsable_rol: trabajo.responsable?.rol
      })
    }
  })
});

// ✅ Estado para controlar dropdown de estado
const estadoDropdownAbierto = ref<number | null>(null)

// ✅ ARQUITECTURA MVC - Los trabajos ya vienen filtrados del backend
const trabajosFiltrados = computed(() => {
  return props.trabajos
})

// Métodos
const getTotalCantidadServicios = (trabajo: any): number => {
  if (!trabajo.servicios || !Array.isArray(trabajo.servicios)) {
    return 0
  }
  return trabajo.servicios.reduce((total: number, servicio: any) => {
    return total + (servicio.cantidad || 0)
  }, 0)
}

const getServiciosConCantidad = (trabajo: any): string => {
  if (!trabajo.servicios || !Array.isArray(trabajo.servicios)) {
    return 'Sin servicios'
  }
  
  return trabajo.servicios
    .filter((servicio: any) => servicio.nombreServicio && servicio.cantidad)
    .map((servicio: any) => {
      const nombre = servicio.nombreServicio || 'Sin nombre'
      const cantidad = servicio.cantidad || 0
      return `${nombre} ${cantidad}`
    })
    .join(', ')
}

const aplicarFiltros = () => {
  // ✅ ARQUITECTURA MVC - Enviar búsqueda al backend
  router.get('/registrar-trabajos', {
    busqueda: filtros.value.busquedaUnificada
  })
  console.log('Búsqueda enviada al backend:', filtros.value.busquedaUnificada)
}

const limpiarBusqueda = () => {
  filtros.value.busquedaUnificada = ''
  // ✅ ARQUITECTURA MVC - Limpiar búsqueda en el backend
  router.get('/registrar-trabajos')
  console.log('Búsqueda limpiada')
}

const verDetalleTrabajo = (trabajo: Trabajo) => {
  // Navegar al detalle del trabajo usando slug
  window.location.href = `/registrar-trabajos/${trabajo.slug}`
}

const crearNuevoTrabajo = () => {
  // Navegar al formulario de creación
  window.location.href = '/registrar-trabajos/create'
}

const editarTrabajo = (trabajo: Trabajo) => {
  // Navegar al formulario de edición usando el slug
  window.location.href = `/registrar-trabajos/${trabajo.slug}/edit`
}

const eliminarTrabajo = (trabajo: Trabajo) => {
  // Confirmar antes de eliminar
  if (confirm(`¿Estás seguro de que quieres eliminar el trabajo de ${trabajo.cliente.nombre}?`)) {
    // Aquí puedes implementar la lógica de eliminación
    console.log('Eliminando trabajo:', trabajo.id)
    // Por ahora solo muestra un mensaje
    alert('Función de eliminación en desarrollo')
  }
}

const abrirModalPagos = (trabajo: Trabajo) => {
  console.log('abrirModalPagos llamado con:', trabajo)
  // Abrir modal para pagos en cuotas
  trabajoSeleccionado.value = trabajo
  mostrarModalCuota.value = true
  console.log('Modal de cuotas debería estar abierto:', mostrarModalCuota.value)
}

const cerrarModalCuota = () => {
  mostrarModalCuota.value = false
  trabajoSeleccionado.value = null
}

// ✅ Funciones para modal de cancelación
const abrirModalCancelacion = (trabajo: any) => {
  trabajoParaCancelar.value = trabajo
  mostrarModalCancelacion.value = true
}

const cerrarModalCancelacion = () => {
  mostrarModalCancelacion.value = false
  trabajoParaCancelar.value = null
}

const onTrabajoCancelado = (trabajoActualizado: any) => {
  console.log('🔄 onTrabajoCancelado llamado con:', trabajoActualizado)
  
  // Actualizar el trabajo en la lista local
  const index = props.trabajos.findIndex(t => t.id === trabajoActualizado.id)
  console.log('🔍 Índice encontrado:', index)
  
  if (index !== -1) {
    console.log('📝 Trabajo antes de actualizar:', props.trabajos[index])
    props.trabajos[index] = trabajoActualizado
    console.log('📝 Trabajo después de actualizar:', props.trabajos[index])
  } else {
    console.error('❌ No se encontró el trabajo en la lista con ID:', trabajoActualizado.id)
  }
  
  // Cerrar modal
  cerrarModalCancelacion()
  
  console.log('✅ Trabajo cancelado y actualizado en la lista')
}

const onCuotaProcesada = (trabajo: any) => {
  console.log('Cuota procesada para:', trabajo)
  // Recargar la página para actualizar los datos
  window.location.reload()
}


// Función helper para obtener solo la inicial del nombre
const getInitial = (nombre: string): string => {
  return nombre ? nombre.charAt(0).toUpperCase() : ''
}

// ✅ ARQUITECTURA MVC - Los cálculos vienen del backend
// Los trabajos ya vienen con los totales calculados desde el servidor
const getTotalTrabajo = (trabajo: any): number => {
  // El backend ya calcula y envía el total
  return Number(trabajo.totalTrabajo) || 0
}

const getSaldoTrabajo = (trabajo: any): number => {
  // El backend ya calcula y envía el saldo
  const saldo = Number(trabajo.saldo) || 0
  
  // Debug: verificar datos del trabajo
  console.log('🔍 Datos del trabajo:', {
    id: trabajo.id,
    total: trabajo.total,
    aCuenta: trabajo.aCuenta,
    saldo: trabajo.saldo,
    saldoCalculado: saldo
  })
  
  return saldo
}

// ✅ Funciones helper para presentación (sin lógica de negocio)
const formatPrecio = (precio: any): string => {
  const numPrecio = Number(precio) || 0
  
  // Solo mostrar decimales si hay centavos
  if (numPrecio % 1 === 0) {
    return numPrecio.toString() // Sin decimales para números enteros
  } else {
    return numPrecio.toFixed(2) // Con decimales para números con centavos
  }
}

const getDescuento = (servicio: any): number => {
  return Number(servicio.descuento) || 0
}

const getSubtotalBruto = (servicio: any): number => {
  const precioOriginal = Number(servicio.precio) || 0
  const cantidad = Number(servicio.cantidad) || 0
  return precioOriginal * cantidad
}

const getSubtotalFinal = (servicio: any): number => {
  const subtotalBruto = getSubtotalBruto(servicio)
  const descuento = getDescuento(servicio)
  return subtotalBruto - descuento
}

// Función para formatear fechas
const formatDate = (dateString: string): string => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Función para obtener clases CSS de estado de trabajo
const getEstadoTrabajoClass = (estado: string): string => {
  const estadoLower = estado.toLowerCase()
  if (estadoLower.includes('pendiente') || estadoLower.includes('en proceso')) {
    return 'bg-yellow-100 text-yellow-800'
  } else if (estadoLower.includes('completado') || estadoLower.includes('finalizado')) {
    return 'bg-green-100 text-green-800'
  } else if (estadoLower.includes('cancelado') || estadoLower.includes('rechazado')) {
    return 'bg-red-100 text-red-800'
  } else {
    return 'bg-gray-100 text-gray-800'
  }
}

// Función para obtener clases CSS de estado de pago
const getEstadoPagoClass = (estado: string): string => {
  const estadoLower = estado.toLowerCase()
  if (estadoLower.includes('pendiente') || estadoLower.includes('por pagar')) {
    return 'bg-yellow-100 text-yellow-800'
  } else if (estadoLower.includes('pagado') || estadoLower.includes('completado')) {
    return 'bg-green-100 text-green-800'
  } else if (estadoLower.includes('parcial') || estadoLower.includes('a cuenta')) {
    return 'bg-blue-100 text-blue-800'
  } else if (estadoLower.includes('vencido') || estadoLower.includes('atrasado')) {
    return 'bg-red-100 text-red-800'
  } else {
    return 'bg-gray-100 text-gray-800'
  }
}

// ✅ ARQUITECTURA MVC - Función para cambiar estado del trabajo
const cambiarEstadoTrabajo = async (trabajo: any, nuevoEstadoId?: number) => {
  try {
    const estadoId = nuevoEstadoId || trabajo.idEstado;
    
    console.log('🔍 cambiarEstadoTrabajo llamado:', {
      trabajoId: trabajo.id,
      estadoId: estadoId,
      nuevoEstadoId: nuevoEstadoId,
      estadoActual: trabajo.idEstado
    });
    
    // Debug: Verificar token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('🔍 Token CSRF:', csrfToken);
    
    // Si el estado es "Cancelado" (id: 5), abrir modal de cancelación
    if (estadoId === 5) {
      console.log('🚨 Abriendo modal de cancelación para trabajo:', trabajo.id);
      console.log('🚨 Estado actual de mostrarModalCancelacion:', mostrarModalCancelacion.value);
      
      trabajoParaCancelar.value = trabajo;
      mostrarModalCancelacion.value = true;
      
      console.log('🚨 Después de asignar - mostrarModalCancelacion:', mostrarModalCancelacion.value);
      console.log('🚨 trabajoParaCancelar:', trabajoParaCancelar.value);
      
      cerrarEstadoDropdown();
      return;
    }
    
    // Debug: Verificar si el estado es "Cancelado" por nombre
    const estadoNombre = props.estadosTrabajo.find(e => e.id === estadoId)?.nombre?.toLowerCase();
    console.log('🔍 Estado nombre:', estadoNombre);
    
    if (estadoNombre && estadoNombre.includes('cancelado')) {
      console.log('🚨 Detectado estado "Cancelado" por nombre, abriendo modal');
      trabajoParaCancelar.value = trabajo;
      mostrarModalCancelacion.value = true;
      cerrarEstadoDropdown();
      return;
    }
    
    const response = await axios.patch(`/api/trabajos/${trabajo.id}/estado`, {
      idEstado: estadoId
    }, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    // ✅ ARQUITECTURA MVC - Actualizar la lista local con los datos completos del backend
    if (response.data.success && response.data.trabajo) {
      const index = props.trabajos.findIndex(t => t.id === trabajo.id);
      if (index !== -1) {
        // Actualizar el trabajo en la lista con todos los datos del backend
        props.trabajos[index] = response.data.trabajo;
        console.log('✅ Trabajo actualizado en la lista:', response.data.trabajo);
      }
    } else {
      // Fallback: solo actualizar el estado si no hay datos completos
      trabajo.idEstado = estadoId;
    }
    
    // Cerrar dropdown
    estadoDropdownAbierto.value = null;
    
    // Mostrar mensaje de éxito
    console.log('✅ Estado actualizado:', response.data);
    
  } catch (error) {
    console.error('❌ Error al cambiar estado:', error);
  }
};

// ✅ Funciones para el diseño mejorado del selector de estado
const toggleEstadoDropdown = (trabajoId: number) => {
  // Cerrar otros dropdowns abiertos
  if (estadoDropdownAbierto.value !== trabajoId) {
    estadoDropdownAbierto.value = trabajoId;
  } else {
    estadoDropdownAbierto.value = null;
  }
};

const cerrarEstadoDropdown = () => {
  // Pequeño delay para permitir clicks en las opciones
  setTimeout(() => {
    estadoDropdownAbierto.value = null;
  }, 150);
};

const getEstadoTrabajoNombre = (estadoId: number): string => {
  const estado = props.estadosTrabajo.find(e => e.id === estadoId);
  console.log('🔍 getEstadoTrabajoNombre:', { estadoId, estado, estadosTrabajo: props.estadosTrabajo });
  return estado?.nombre || 'Sin estado';
};

const getEstadoTrabajoButtonClass = (estadoId: number): string => {
  const estadoLower = getEstadoTrabajoNombre(estadoId).toLowerCase();
  
  if (estadoLower.includes('pendiente')) {
    return 'bg-yellow-500/90 text-white hover:bg-yellow-400/90 shadow-yellow-500/25';
  } else if (estadoLower.includes('en proceso')) {
    return 'bg-blue-500/90 text-white hover:bg-blue-400/90 shadow-blue-500/25';
  } else if (estadoLower.includes('completado')) {
    return 'bg-green-500/90 text-white hover:bg-green-400/90 shadow-green-500/25';
  } else if (estadoLower.includes('cancelado')) {
    return 'bg-red-500/90 text-white hover:bg-red-400/90 shadow-red-500/25';
  } else {
    return 'bg-gray-500/90 text-white hover:bg-gray-400/90 shadow-gray-500/25';
  }
};

const getEstadoTrabajoColorClass = (estadoId: number): string => {
  const estadoLower = getEstadoTrabajoNombre(estadoId).toLowerCase();
  
  if (estadoLower.includes('pendiente')) {
    return 'bg-yellow-400';
  } else if (estadoLower.includes('en proceso')) {
    return 'bg-blue-400';
  } else if (estadoLower.includes('completado')) {
    return 'bg-green-400';
  } else if (estadoLower.includes('cancelado')) {
    return 'bg-red-400';
  } else {
    return 'bg-gray-400';
  }
};

// Funciones helper para formateo y estilos

// Lifecycle
onMounted(() => {
  console.log('🚀 Index.vue montado, trabajos recibidos:', props.trabajos)
})
</script>

<style scoped>
/* Estilos adicionales si son necesarios */
</style>