<template>
  <AppShell>
    <AppSidebar />
    
    <div class="min-h-screen overflow-hidden bg-gradient-to-br from-red-950 via-red-900 to-red-800 flex-1">
      <!-- Header del Dashboard con logo y branding -->
      <div class="relative overflow-hidden">
        <!-- Fondo decorativo con patrón sutil -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23dc2626%22%20fill-opacity%3D%220.03%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%222%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <!-- Header principal -->
        <div class="relative p-6 pb-8">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
            <div class="mb-6 lg:mb-0">
              <!-- Logo del estudio -->
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                <div>
                  <h1 class="text-3xl lg:text-4xl font-bold text-white mb-1">
                    FOTO STUDIO EU
                  </h1>
                  <p class="text-red-200 text-lg italic">Hace que la magia suceda</p>
                </div>
              </div>
              
              <!-- Información del usuario -->
              <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                <h2 class="text-xl font-semibold text-white mb-2">
                  Bienvenido, {{ usuario?.fullName || 'Usuario' }}
                </h2>
                <p class="text-red-200">
                  Rol: <span class="font-medium text-white">{{ usuario?.rol?.nombre || 'Usuario' }}</span>
                </p>
              </div>
            </div>
            
            <!-- Indicador de estado del sistema -->
            <div class="bg-gradient-to-r from-red-600 to-red-500 rounded-xl p-4 text-center shadow-lg">
              <div class="text-white">
                <p class="text-sm opacity-90">Estado del Sistema</p>
                <p class="text-lg font-bold">Operativo</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="px-6 pb-6">
        <!-- Estadísticas del Sistema con diseño mejorado -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <!-- Total Servicios -->
          <div class="group bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20 hover:border-red-300/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-red-200 text-sm font-medium mb-1">Total Servicios</p>
                <p class="text-3xl font-bold text-white">{{ stats?.servicios?.total || 0 }}</p>
                <p class="text-red-300 text-xs mt-1">Activos en el sistema</p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-red-500/30 transition-all duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Clientes -->
          <div class="group bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20 hover:border-red-300/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-red-200 text-sm font-medium mb-1">Total Clientes</p>
                <p class="text-3xl font-bold text-white">{{ stats?.clientes?.total || 0 }}</p>
                <p class="text-red-300 text-xs mt-1">Clientes registrados</p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-red-500/30 transition-all duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Trabajos -->
          <div class="group bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20 hover:border-red-300/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-red-200 text-sm font-medium mb-1">Total Trabajos</p>
                <p class="text-3xl font-bold text-white">{{ stats?.trabajos?.total || 0 }}</p>
                <p class="text-red-300 text-xs mt-1">Proyectos activos</p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-red-500/30 transition-all duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2m0 0V4a2 2 0 00-2-2H8a2 2 0 00-2 2v2"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Total Usuarios -->
          <div class="group bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20 hover:border-red-300/30 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/20">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-red-200 text-sm font-medium mb-1">Total Usuarios</p>
                <p class="text-3xl font-bold text-white">{{ stats?.usuarios?.total || 0 }}</p>
                <p class="text-red-300 text-xs mt-1">Equipo del estudio</p>
              </div>
              <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-red-500/30 transition-all duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Alertas y Notificaciones -->
        <div v-if="alertas && alertas.length > 0" class="mb-8">
          <div class="bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L19.5 4.5M4.5 4.5L19.5 19.5"/>
                </svg>
                Alertas Importantes
              </h3>
              <div class="bg-red-600/20 px-3 py-1 rounded-full">
                <span class="text-red-200 text-sm font-medium">{{ alertas.length }} alertas</span>
              </div>
            </div>
            
            <div class="space-y-4">
              <div v-for="alerta in alertas" :key="alerta.titulo" 
                   class="group bg-white/5 hover:bg-white/10 rounded-xl p-4 border border-white/10 hover:border-red-300/30 transition-all duration-300 hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg"
                         :class="getAlertaIconClass(alerta.tipo)">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="alerta.icono === 'clock'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        <path v-else-if="alerta.icono === 'credit-card'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        <path v-else-if="alerta.icono === 'alert-triangle'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        <path v-else-if="alerta.icono === 'user-x'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        <path v-else-if="alerta.icono === 'check-circle'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-white font-semibold text-lg">{{ alerta.titulo }}</p>
                      <p class="text-red-200">{{ alerta.mensaje }}</p>
                    </div>
                  </div>
                  <div class="text-right">
                    <a :href="alerta.accion" 
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300"
                       :class="getAlertaButtonClass(alerta.tipo)">
                      Ver detalles
                      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Trabajos Recientes con diseño mejorado -->
        <div class="bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 mb-8 border border-red-200/20">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-white flex items-center">
              <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Trabajos Recientes
            </h3>
            <div class="bg-red-600/20 px-3 py-1 rounded-full">
              <span class="text-red-200 text-sm font-medium">{{ trabajosRecientes?.length || 0 }} trabajos</span>
            </div>
          </div>
          
          <div class="space-y-4">
            <div v-for="trabajo in trabajosRecientes" :key="trabajo.id" 
                 class="group bg-white/5 hover:bg-white/10 rounded-xl p-4 border border-white/10 hover:border-red-300/30 transition-all duration-300 hover:scale-[1.02]">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2m0 0V4a2 2 0 00-2-2H8a2 2 0 00-2 2v2"/>
                    </svg>
                  </div>
                  <div>
                    <p class="text-white font-semibold text-lg">{{ trabajo.cliente?.nombre || 'Cliente' }}</p>
                    <p class="text-red-200">{{ trabajo.servicio?.nombreServicio || 'Servicio' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-lg"
                        :class="getEstadoClass(trabajo.estado?.nombre)">
                    {{ trabajo.estado?.nombre || 'Pendiente' }}
                  </span>
                  <p class="text-red-300 text-sm mt-2 font-medium">{{ formatDate(trabajo.fechaRegistro) }}</p>
                </div>
              </div>
            </div>
            
            <!-- Mensaje cuando no hay trabajos -->
            <div v-if="!trabajosRecientes || trabajosRecientes.length === 0" 
                 class="text-center py-8">
              <svg class="w-16 h-16 text-red-400/50 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <p class="text-red-200 text-lg">No hay trabajos recientes</p>
              <p class="text-red-300">Los trabajos aparecerán aquí cuando se registren</p>
            </div>
          </div>
        </div>

        <!-- Estados de Trabajos -->
        <div class="bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 mb-8 border border-red-200/20">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-white flex items-center">
              <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              Estados de Trabajos
            </h3>
            <div class="bg-red-600/20 px-3 py-1 rounded-full">
              <span class="text-red-200 text-sm font-medium">Resumen de estados</span>
            </div>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pendientes -->
            <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-6 text-center border border-white/10 hover:border-yellow-300/30 transition-all duration-300 hover:scale-105">
              <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-500/30 transition-all duration-300">
                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Pendientes</h4>
              <p class="text-3xl font-bold text-yellow-400">{{ stats?.trabajos?.pendientes || 0 }}</p>
              <p class="text-red-300 text-sm mt-2">Esperando inicio</p>
            </div>

            <!-- En Proceso -->
            <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-6 text-center border border-white/10 hover:border-blue-300/30 transition-all duration-300 hover:scale-105">
              <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500/30 transition-all duration-300">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">En Proceso</h4>
              <p class="text-3xl font-bold text-blue-400">{{ stats?.trabajos?.enProceso || 0 }}</p>
              <p class="text-red-300 text-sm mt-2">En desarrollo</p>
            </div>

            <!-- Completados -->
            <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-6 text-center border border-white/10 hover:border-green-300/30 transition-all duration-300 hover:scale-105">
              <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500/30 transition-all duration-300">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Completados</h4>
              <p class="text-3xl font-bold text-green-400">{{ stats?.trabajos?.completados || 0 }}</p>
              <p class="text-red-300 text-sm mt-2">Listos para entregar</p>
            </div>

            <!-- Cancelados -->
            <div class="group bg-white/5 hover:bg-white/10 rounded-xl p-6 text-center border border-white/10 hover:border-red-300/30 transition-all duration-300 hover:scale-105">
              <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-500/30 transition-all duration-300">
                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-white mb-2">Cancelados</h4>
              <p class="text-3xl font-bold text-red-400">{{ stats?.trabajos?.cancelados || 0 }}</p>
              <p class="text-red-300 text-sm mt-2">Proyectos cancelados</p>
            </div>
          </div>
        </div>

        <!-- Acciones Rápidas con diseño mejorado -->
        <div class="bg-gradient-to-br from-red-50/10 to-red-100/5 backdrop-blur-md rounded-2xl p-6 border border-red-200/20">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-white flex items-center">
              <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              Acciones Rápidas
            </h3>
            <p class="text-red-200 text-sm">Accede rápidamente a las funciones principales</p>
          </div>
          
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="/registrar-trabajos" class="group">
              <div class="bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 rounded-xl p-6 text-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/30 border border-red-500/30">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition-all duration-300">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
                <p class="text-white font-bold text-lg mb-2">Registrar Trabajo</p>
                <p class="text-red-100 text-sm">Crear nuevo proyecto</p>
              </div>
            </a>

            <a href="/clientes" class="group">
              <div class="bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 rounded-xl p-6 text-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/30 border border-red-500/30">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition-all duration-300">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
                <p class="text-white font-bold text-lg mb-2">Gestionar Clientes</p>
                <p class="text-red-100 text-sm">Administrar base de datos</p>
              </div>
            </a>

            <a href="/servicios" class="group">
              <div class="bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 rounded-xl p-6 text-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/30 border border-red-500/30">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition-all duration-300">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                </div>
                <p class="text-white font-bold text-lg mb-2">Gestionar Servicios</p>
                <p class="text-red-100 text-sm">Configurar catálogo</p>
              </div>
            </a>

            <a v-if="usuario?.isAdmin" href="/usuarios" class="group">
              <div class="bg-gradient-to-br from-red-600 to-red-700 hover:from-red-500 hover:to-red-600 rounded-xl p-6 text-center transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-red-500/30 border border-red-500/30">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-white/30 transition-all duration-300">
                  <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                </div>
                <p class="text-white font-bold text-lg mb-2">Gestionar Usuarios</p>
                <p class="text-red-100 text-sm">Administrar equipo</p>
              </div>
            </a>
          </div>
        </div>

        <!-- Footer del dashboard -->
        <div class="mt-8 text-center">
          <div class="bg-white/5 backdrop-blur-md rounded-xl p-4 border border-white/10">
            <p class="text-red-200 text-sm">
              © 2024 FOTO STUDIO EU - Sistema de Gestión de Trabajos Fotográficos
            </p>
            <p class="text-red-300 text-xs mt-1">
              Desarrollado con ❤️ para hacer que la magia suceda
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppShell>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

interface Props {
  usuario?: {
    id: number
    nombre: string
    apellidoPaterno: string
    apellidoMaterno: string
    email: string
    fullName: string
    rol?: {
      id: number
      nombre: string
    }
    isAdmin?: boolean
    isEmpleado?: boolean
    isGerente?: boolean
  }
  stats?: {
    servicios: {
      total: number
      activos: number
      inactivos: number
    }
    clientes: {
      total: number
    }
    trabajos: {
      pendientes: number
      enProceso: number
      completados: number
      cancelados: number
      total: number
    }
    usuarios: {
      total: number
      activos: number
    }
  }
  trabajosRecientes?: Array<{
    id: number
    cliente?: {
      nombre: string
    }
    servicio?: {
      nombreServicio: string
    }
    estado?: {
      nombre: string
    }
    fechaRegistro: string
  }>
  alertas?: Array<{
    tipo: string
    titulo: string
    mensaje: string
    icono: string
    accion: string
  }>
}

const props = defineProps<Props>()

// Debug para ver qué datos estamos recibiendo
console.log('AuthenticatedDashboard - Props recibidas:', props)
console.log('AuthenticatedDashboard - Usuario:', props.usuario)
console.log('AuthenticatedDashboard - Stats:', props.stats)
console.log('AuthenticatedDashboard - Trabajos recientes:', props.trabajosRecientes)
console.log('AuthenticatedDashboard - Alertas:', props.alertas)

// Funciones auxiliares
const getEstadoClass = (estado: string | undefined) => {
  if (!estado) return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
  
  switch (estado) {
    case 'Pendiente':
      return 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30'
    case 'En Proceso':
      return 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
    case 'Completado':
      return 'bg-green-500/20 text-green-300 border border-green-500/30'
    case 'Cancelado':
      return 'bg-red-500/20 text-red-300 border border-red-500/30'
    default:
      return 'bg-gray-500/20 text-gray-300 border border-gray-500/30'
  }
}

const formatDate = (date: string | undefined) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-BO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

// Funciones para alertas
const getAlertaIconClass = (tipo: string) => {
  switch (tipo) {
    case 'warning':
      return 'bg-gradient-to-br from-yellow-500 to-orange-600'
    case 'info':
      return 'bg-gradient-to-br from-blue-500 to-cyan-600'
    case 'error':
      return 'bg-gradient-to-br from-red-500 to-red-600'
    case 'success':
      return 'bg-gradient-to-br from-green-500 to-emerald-600'
    default:
      return 'bg-gradient-to-br from-gray-500 to-gray-600'
  }
}

const getAlertaButtonClass = (tipo: string) => {
  switch (tipo) {
    case 'warning':
      return 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 hover:bg-yellow-500/30'
    case 'info':
      return 'bg-blue-500/20 text-blue-300 border border-blue-500/30 hover:bg-blue-500/30'
    case 'error':
      return 'bg-red-500/20 text-red-300 border border-red-500/30 hover:bg-red-500/30'
    case 'success':
      return 'bg-green-500/20 text-green-300 border border-green-500/30 hover:bg-green-500/30'
    default:
      return 'bg-gray-500/20 text-gray-300 border border-gray-500/30 hover:bg-gray-500/30'
  }
}
</script>
