<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-gray-800">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-lg border-b border-red-500/20 py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
          <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent mb-2 sm:mb-3 flex items-center">
                  <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10 mr-2 sm:mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <span class="hidden sm:inline">Crear Nuevo Usuario</span>
                  <span class="sm:hidden">Crear Usuario</span>
                </h1>
                <p class="text-gray-300 text-sm sm:text-base lg:text-lg">Registra un nuevo usuario en el sistema</p>
              </div>
              <Link :href="route('usuarios')" class="bg-gradient-to-r from-gray-600/50 to-gray-700/50 text-white hover:from-gray-600 hover:to-gray-700 text-sm sm:text-base lg:text-lg px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg backdrop-blur-sm border border-gray-600/50 flex items-center justify-center">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver
              </Link>
            </div>
          </div>
        </div>

        <!-- Formulario principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-red-500/20 p-4 sm:p-6 lg:p-8">
             
             <!-- Mensaje de error general -->
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl backdrop-blur-sm">
               <div class="flex items-center">
                 <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                 </svg>
                 <div>
                   <p class="text-red-400 font-medium">⚠️ Errores en el formulario:</p>
                   <ul class="text-sm mt-1 space-y-1">
                     <li v-for="(error, field) in $page.props.errors" :key="field" class="flex items-center gap-2">
                       <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                       <span class="text-red-300">{{ error }}</span>
                     </li>
                   </ul>
                 </div>
               </div>
             </div>

             <!-- Mensaje de éxito -->
            <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl backdrop-blur-sm">
               <div class="flex items-center">
                 <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                 </svg>
                 <span class="text-green-400 font-medium">{{ $page.props.flash.success }}</span>
               </div>
             </div>

             <form @submit.prevent="submit" class="space-y-8">
              <!-- Información Personal -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  Información Personal
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                   <!-- Nombre -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      Nombre <span class="text-red-400">*</span>
                    </label>
                     <input 
                       v-model="form.nombre" 
                       type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                      placeholder="Ingrese nombre..."
                       required 
                     />
                   </div>

                  <!-- Apellido Paterno -->
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      Apellido Paterno
                    </label>
                    <input 
                      v-model="form.apellidoPaterno" 
                      type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                      placeholder="Ingrese Apellido Paterno..."
                    />
                  </div>

                  <!-- Apellido Materno -->
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                      </svg>
                      Apellido Materno
                    </label>
                    <input 
                      v-model="form.apellidoMaterno" 
                      type="text" 
                      maxlength="100"
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                      placeholder="Ingrese Apellido Materno..."
                    />
                  </div>

                   <!-- CI -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                      CI
                    </label>
                     <input 
                       v-model="form.ci" 
                       type="text" 
                       pattern="[0-9]*"
                       inputmode="numeric"
                       maxlength="20"
                       placeholder="Solo números"
                       @input="form.ci = form.ci.replace(/[^0-9]/g, '')"
                       :class="[
                         'w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg',
                         $page.props.errors?.ci ? 'border-red-500' : ''
                       ]"
                    />
                    <div v-if="$page.props.errors?.ci" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.ci }}
                    </div>
                   </div>
                </div>
              </div>

              <!-- Información de Contacto -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  Información de Contacto
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                   <!-- Email -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      Email
                    </label>
                     <input 
                       v-model="form.email" 
                       type="email" 
                      maxlength="150"
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                      placeholder="Ingrese email..."
                     />
                   </div>

                   <!-- Teléfono -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                      Teléfono
                    </label>
                    <input 
                      v-model="form.telefono" 
                      type="tel" 
                      pattern="[0-9]*"
                      inputmode="numeric"
                      maxlength="20"
                      placeholder="Solo números"
                      @input="form.telefono = form.telefono.replace(/[^0-9]/g, '')"
                      :class="[
                        'w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg',
                        $page.props.errors?.telefono ? 'border-red-500' : ''
                      ]"
                    />
                    <div v-if="$page.props.errors?.telefono" class="text-xs text-red-400 mt-1">
                      {{ $page.props.errors.telefono }}
                    </div>
                   </div>

                   <!-- Dirección -->
                   <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                      Dirección
                    </label>
                    <input 
                      v-model="form.direccion" 
                      type="text" 
                      maxlength="255"
                      placeholder="Ingrese dirección..."
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                    />
                   </div>
                </div>
              </div>

              <!-- Información Laboral -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"/></svg>
                  </div>
                  Información Laboral
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                   <!-- Rol -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                      </svg>
                      Rol <span class="text-red-400">*</span>
                    </label>
                    <select 
                      v-model="form.idRol" 
                      required
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg"
                    >
                      <option value="">Seleccionar rol...</option>
                      <option v-for="rol in roles" :key="rol.id" :value="rol.id">
                        {{ rol.nombre }}
                      </option>
                    </select>
                   </div>

                   <!-- Estado -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                      Estado <span class="text-red-400">*</span>
                    </label>
                    <select 
                      v-model="form.estado" 
                      required
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg"
                    >
                      <option :value="true">Activo</option>
                      <option :value="false">Inactivo</option>
                    </select>
                   </div>

                   <!-- Fecha de Ingreso -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      Fecha de Ingreso
                    </label>
                    <input 
                      v-model="form.fechaIngreso" 
                      type="date" 
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg"
                    />
                   </div>

                   <!-- Fecha Final -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      Fecha Final
                    </label>
                    <input 
                      v-model="form.fechaFinal" 
                      type="date" 
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg"
                    />
                   </div>
                </div>
              </div>

              <!-- Contraseña -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </div>
                  Contraseña
                </h3>
                
                <div class="grid grid-cols-1 gap-4 sm:gap-6">
                   <!-- Contraseña -->
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                      Contraseña
                    </label>
                    <input 
                      v-model="form.password" 
                      type="password" 
                      maxlength="255"
                      placeholder="Ingrese contraseña (mínimo 6 caracteres)..."
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg"
                    />
                   </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-600/50">
                <Link :href="route('usuarios')" class="px-8 py-3 text-gray-300 bg-gray-700/50 hover:bg-gray-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-gray-600/50 text-center">
                  Cancelar
                </Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-8 py-3 bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                >
                  <svg v-if="form.processing" class="w-5 h-5 mr-2 inline animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                  </svg>
                  <svg v-else class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  {{ form.processing ? 'Registrando...' : 'Registrar Usuario' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  roles: { type: Array, required: true }
})

// Formulario usando Inertia
const form = useForm({
  nombre: '',
  apellidoPaterno: '',
  apellidoMaterno: '',
  ci: '',
  telefono: '',
  direccion: '',
  email: '',
  password: '',
  fechaIngreso: '',
  fechaFinal: '',
  estado: true,
  idRol: ''
})


// Función de envío
const submit = () => {
  form.post(route('usuarios.store'), {
    onSuccess: () => {
      // El mensaje de éxito se maneja desde el controlador
      console.log('Usuario registrado exitosamente')
    },
    onError: (errors) => {
      console.log('Errores de validación:', errors)
    }
  })
}
</script>