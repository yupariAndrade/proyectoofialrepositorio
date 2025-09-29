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

          <!-- Filter Dropdowns with Enhanced Design -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="group">
              <label class="flex text-sm font-bold text-slate-300 mb-3 items-center">
                <div class="w-6 h-6 bg-gradient-to-r from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center mr-2">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                Cliente
              </label>
              <select 
                v-model="filtros.clienteId" 
                class="w-full bg-slate-700/50 backdrop-blur-sm border border-slate-600/50 rounded-xl px-4 py-3 text-slate-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400 transition-all duration-300 hover:bg-slate-600/50"
              >
                <option value="">👥 Todos los clientes</option>
                <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                  {{ cliente.nombre }} {{ cliente.apellido }}
                </option>
              </select>
            </div>

            <div class="group">
              <label class="flex text-sm font-bold text-slate-300 mb-3 items-center">
                <div class="w-6 h-6 bg-gradient-to-r from-emerald-400 to-green-500 rounded-lg flex items-center justify-center mr-2">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                Estado del Trabajo
              </label>
              <select 
                v-model="filtros.estadoId" 
                class="w-full bg-slate-700/50 backdrop-blur-sm border border-slate-600/50 rounded-xl px-4 py-3 text-slate-200 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-400 transition-all duration-300 hover:bg-slate-600/50"
              >
                <option value="">📋 Todos los estados</option>
                <option v-for="estado in estadosTrabajo" :key="estado.id" :value="estado.id">
                  {{ estado.nombre }}
                </option>
              </select>
            </div>

            <div class="group">
              <label class="flex text-sm font-bold text-slate-300 mb-3 items-center">
                <div class="w-6 h-6 bg-gradient-to-r from-amber-400 to-orange-500 rounded-lg flex items-center justify-center mr-2">
                  <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                  </svg>
                </div>
                Estado de Pago
              </label>
              <select 
                v-model="filtros.estadoPagoId" 
                class="w-full bg-slate-700/50 backdrop-blur-sm border border-slate-600/50 rounded-xl px-4 py-3 text-slate-200 focus:ring-2 focus:ring-amber-500/50 focus:border-amber-400 transition-all duration-300 hover:bg-slate-600/50"
              >
                <option value="">💰 Todos los estados de pago</option>
                <option v-for="estado in estadosPago" :key="estado.id" :value="estado.id">
                  {{ estado.nombre }}
                </option>
              </select>
            </div>
          </div>

          <!-- Filter Button with Enhanced Design -->
          <div class="mt-8 flex justify-center">
            <button 
              @click="aplicarFiltros" 
              class="group relative bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:from-cyan-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-2xl shadow-cyan-500/25 flex items-center"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              <span class="relative z-10">🔍 Aplicar Filtros</span>
            </button>
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
            <tbody class="bg-slate-800/30 divide-y divide-slate-700/30">
              <tr v-for="trabajo in trabajosFiltrados" :key="trabajo.id" class="hover:bg-slate-700/30 transition-all duration-300 group">
                <!-- CLIENTE -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div class="text-sm font-medium text-gray-200">
                      {{ trabajo.cliente.nombre }} {{ trabajo.cliente.apellido }}
                    </div>
                    <div class="text-sm text-gray-400">
                      Nr. Ref: 
                    </div>
                    <div v-if="trabajo.cliente.telefono" class="text-sm text-gray-400">
                      📞 {{ trabajo.cliente.telefono }}
                    </div>
                  </div>
                </td>

                <!-- SERVICIO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-for="servicio in trabajo.servicios" :key="servicio.id" class="mb-1 last:mb-0">
                    <div class="text-sm font-medium text-gray-200">
                      {{ servicio.nombreServicio }}
                    </div>
                    <div class="text-sm text-gray-400">
                      <div class="mb-1">
                        <span class="text-gray-300">{{ servicio.cantidad || 1 }} × {{ formatPrecio(servicio.precio) }} Bs</span>
                      </div>
                      <div v-if="getDescuento(servicio) > 0" class="text-xs">
                        <span class="text-red-400 line-through">
                          Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                        </span>
                        <span class="text-orange-400 block">
                          -{{ formatPrecio(getDescuento(servicio)) }} Bs desc.
                        </span>
                        <span class="text-green-400 font-medium">
                          Final: {{ formatPrecio(getSubtotalFinal(servicio)) }} Bs
                        </span>
                      </div>
                      <div v-else class="text-green-400">
                        Subtotal: {{ formatPrecio(getSubtotalBruto(servicio)) }} Bs
                      </div>
                    </div>
                  </div>
                </td>

                <!-- RESPONSABLE -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="trabajo.responsable" class="flex items-center">
                    <!-- Círculo con inicial del responsable -->
                    <div class="w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                      <span class="text-white font-bold text-sm">
                        {{ getInitial(trabajo.responsable.nombre) }}
                      </span>
                    </div>
                    <!-- Información del responsable -->
                    <div>
                      <div class="text-sm font-medium text-gray-200">
                        {{ trabajo.responsable.nombre }} {{ trabajo.responsable.apellido }}
                      </div>
                      <div class="text-sm text-gray-400">
                        {{ trabajo.responsable.rol }}
                      </div>
                    </div>
                  </div>
                  <div v-else class="text-sm text-gray-400 italic">
                    No asignado
                  </div>
                </td>

                <!-- CANTIDAD -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-200">
                    {{ trabajo.cantidad }}
                  </div>
                </td>

                <!-- TOTAL -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold text-green-400">
                    {{ formatPrecio(trabajo.total) }} Bs
                  </div>
                </td>

                <!-- A CUENTA -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-blue-400">
                    {{ formatPrecio(trabajo.aCuenta) }} Bs
                  </div>
                </td>

                <!-- SALDO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold" :class="getSaldoTrabajo(trabajo) > 0 ? 'text-red-400' : 'text-green-400'">
                    {{ formatPrecio(getSaldoTrabajo(trabajo)) }} Bs
                  </div>
                </td>

                <!-- FECHA ENTREGA -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-200">
                    {{ formatDate(trabajo.fechaEntrega) }}
                  </div>
                </td>

                <!-- ESTADO TRABAJO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getEstadoTrabajoClass(trabajo.estadoTrabajo.nombre)"
                  >
                    {{ trabajo.estadoTrabajo.nombre }}
                  </span>
                </td>

                <!-- ESTADO PAGO -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getEstadoPagoClass(trabajo.estadoPago.nombre)"
                  >
                    {{ trabajo.estadoPago.nombre }}
                  </span>
                </td>

                <!-- ACCIONES -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-wrap gap-2">
                    <!-- Botón Ver Detalle -->
                    <button 
                      @click="verDetalleTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-3 py-2 rounded-lg font-medium hover:from-blue-600 hover:to-cyan-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-blue-500/25 flex items-center"
                      title="Ver detalle"
                    >
                      <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      <span class="relative z-10 text-xs">Ver</span>
                    </button>
                    
                    <!-- Botón Editar -->
                    <button 
                      @click="editarTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-emerald-500 to-green-600 text-white px-3 py-2 rounded-lg font-medium hover:from-emerald-600 hover:to-green-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-emerald-500/25 flex items-center"
                      title="Editar trabajo"
                    >
                      <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                      <span class="relative z-10 text-xs">Editar</span>
                    </button>
                    
                    <!-- Botón Pagos en Cuotas -->
                    <button 
                      @click="abrirModalPagos(trabajo)"
                      class="group relative bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-2 rounded-lg font-bold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-green-500/25 flex items-center"
                      title="Pagos en cuotas"
                      v-if="getSaldoTrabajo(trabajo) > 0"
                    >
                      <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                      </svg>
                      <span class="relative z-10 text-xs">$</span>
                    </button>
                    
                    <!-- Botón Eliminar -->
                    <button 
                      @click="eliminarTrabajo(trabajo)"
                      class="group relative bg-gradient-to-r from-red-500 to-pink-600 text-white px-3 py-2 rounded-lg font-medium hover:from-red-600 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-red-500/25 flex items-center"
                      title="Eliminar trabajo"
                    >
                      <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                      <svg class="w-4 h-4 mr-1 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                      <span class="relative z-10 text-xs">Eliminar</span>
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
            {{ filtros.clienteId || filtros.estadoId || filtros.estadoPagoId ? 
               '🔍 Intenta ajustar los filtros de búsqueda para encontrar más resultados.' : 
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
  </AppShell>
</template>
