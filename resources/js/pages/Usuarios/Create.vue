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
            <div v-if="errorGeneral" class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl backdrop-blur-sm">
               <div class="flex items-center">
                 <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                 </svg>
                 <span class="text-red-400 font-medium">{{ errorGeneral }}</span>
               </div>
             </div>

             <!-- Mensaje de éxito -->
            <div v-if="showSuccessMessage" class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl backdrop-blur-sm">
               <div class="flex items-center">
                 <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                   <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                 </svg>
                 <span class="text-green-400 font-medium">{{ successMessage }}</span>
               </div>
             </div>

             <form @submit.prevent="crearUsuario" class="space-y-8">
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
                      maxlength="50"
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
                      maxlength="50"
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
                      maxlength="50"
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
                       @blur="verificarCampo('ci')"
                       @input="validarSoloNumeros('ci', $event)"
                       type="text" 
                      maxlength="20"
                      placeholder="Ingrese CI..."
                       :class="[
                        'w-full px-4 py-3 bg-black/60 border rounded-xl text-white focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 backdrop-blur-sm shadow-lg',
                        errores.ci ? 'border-red-500' : 'border-gray-600'
                       ]"
                     />
                     <div v-if="errores.ci" class="mt-2 text-red-400 text-sm flex items-center">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                       </svg>
                       {{ errores.ci }}
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
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                      </svg>
                      Email
                    </label>
                     <input 
                       v-model="form.email" 
                       @blur="verificarCampo('email')"
                       @input="limpiarError('email')"
                       type="email" 
                      maxlength="150"
                       :class="[
                        'w-full px-4 py-3 bg-black/60 border rounded-xl text-white focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 backdrop-blur-sm shadow-lg',
                        errores.email ? 'border-red-500' : 'border-gray-600'
                       ]"
                      placeholder="Ingrese email..."
                     />
                     <div v-if="errores.email" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                       {{ errores.email }}
                     </div>
                   </div>
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                      </svg>
                      Teléfono
                    </label>
                    <input 
                      v-model="form.telefono" 
                      @blur="verificarCampo('telefono')"
                      @input="validarSoloNumeros('telefono', $event)"
                      type="tel" 
                      maxlength="20"
                      placeholder="Ingrese nmr. Celular..."
                      :class="[
                        'w-full px-4 py-3 bg-black/60 border rounded-xl text-white focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 backdrop-blur-sm shadow-lg',
                        errores.telefono ? 'border-red-500' : 'border-gray-600'
                      ]"
                    />
                    <div v-if="errores.telefono" class="mt-2 text-red-400 text-sm flex items-center">
                      <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                      {{ errores.telefono }}
                    </div>
                  </div>
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
                      maxlength="50"
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg" 
                      placeholder="Ingrese su Direccion..."
                    />
                  </div>
                </div>
              </div>

              <!-- Información Laboral -->
              <div>
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                  <div class="w-8 h-8 mr-3 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                  </div>
                  Información Laboral
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                      </svg>
                      Rol <span class="text-red-400">*</span>
                    </label>
                    <select v-model="form.idRol" class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg" required>
                      <option value="">Seleccione un rol</option>
                      <option v-for="rol in roles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                      </svg>
                      Contraseña <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                      <input 
                        v-model="form.password" 
                        :type="mostrarPassword ? 'text' : 'password'" 
                        class="w-full px-4 py-3 pr-12 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg" 
                        placeholder="Ingrese la contraseña..."
                        required
                      />
                      <button 
                        type="button" 
                        @click="mostrarPassword = !mostrarPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors duration-200"
                      >
                        <svg v-if="!mostrarPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                   <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      Fecha de Ingreso <span class="text-red-400">*</span>
                    </label>
                     <input 
                       v-model="form.fechaIngreso" 
                       type="date" 
                      class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white backdrop-blur-sm shadow-lg" 
                       required
                     />
                   </div>
                  <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                      <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                      </svg>
                      Fecha Final
                    </label>
                    <input v-model="form.fechaFinal" type="date" class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all duration-200 text-white placeholder:text-gray-400 backdrop-blur-sm shadow-lg" />
                  </div>
                  <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer select-none group">
                      <input v-model="form.estado" type="checkbox" class="sr-only peer" />
                      <div class="w-12 h-6 bg-gradient-to-r from-gray-600 to-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/30 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-red-500 peer-checked:to-red-600 shadow-lg"></div>
                      <span class="ml-3 text-sm font-medium text-gray-300 group-hover:text-white transition-colors">
                        {{ form.estado ? '🟢 Activo' : '🔴 Inactivo' }}
                      </span>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Botones de acción -->
              <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-600/50">
                <Link :href="route('usuarios')" class="px-4 sm:px-6 py-2 sm:py-3 text-gray-300 bg-gray-700/50 hover:bg-gray-700 rounded-xl font-semibold transition-all duration-200 backdrop-blur-sm border border-gray-600/50 transform hover:scale-105 flex items-center justify-center text-sm sm:text-base">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Cancelar
                </Link>
                <button 
                  type="submit" 
                  :disabled="procesando"
                  class="px-6 sm:px-8 py-2 sm:py-3 bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 flex items-center justify-center text-sm sm:text-base"
                >
                  <svg v-if="procesando" class="animate-spin -ml-1 mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <span class="hidden sm:inline">{{ procesando ? 'Creando...' : 'Registrar Usuario' }}</span>
                  <span class="sm:hidden">{{ procesando ? 'Creando...' : 'Crear' }}</span>
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
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({
  roles: { type: Array, required: true }
})

const form = ref({
  nombre: '',
  apellidoPaterno: '',
  apellidoMaterno: '',
  ci: '',
  telefono: '',
  direccion: '',
  email: '',
  password: '',
  fechaIngreso: '', // Fecha seleccionable
  fechaFinal: '',
  estado: true,
  idRol: ''
})

// Variables para validaciones y mensajes
const mostrarPassword = ref(false)
const errores = ref({})
const errorGeneral = ref('')
const procesando = ref(false)
const showSuccessMessage = ref(false)
const successMessage = ref('')

// Computed para verificar si hay errores
const hayErrores = computed(() => {
  return Object.keys(errores.value).length > 0 || !form.value.nombre || !form.value.idRol
})

// Limpiar error de un campo específico
const limpiarError = (campo) => {
  if (errores.value[campo]) {
    delete errores.value[campo]
  }
}

// Función para validar que solo se ingresen números
const validarSoloNumeros = (campo, event) => {
  const input = event.target
  const valor = input.value
  
  // Remover cualquier carácter que no sea número
  const soloNumeros = valor.replace(/[^0-9]/g, '')
  
  // Actualizar el valor del input
  input.value = soloNumeros
  form.value[campo] = soloNumeros
  
  // Limpiar error si el campo es válido
  if (soloNumeros.length > 0) {
    delete errores.value[campo]
  } else if (campo === 'telefono') {
    errores.value[campo] = 'El teléfono debe contener solo números'
  } else if (campo === 'ci') {
    errores.value[campo] = 'El CI debe contener solo números'
  }
}

// Verificar si un campo ya existe
const verificarCampo = async (campo) => {
  if (!form.value[campo] || campo === 'fechaFinal') return
  
  try {
    procesando.value = true
    const response = await fetch(`/api/usuarios/verificar-campo`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({
        campo: campo,
        valor: form.value[campo]
      })
    })
    
    const data = await response.json()
    
    if (data.existe) {
      if (campo === 'nombre_completo') {
        errores.value.nombre_completo = 'Ya existe un usuario con este nombre completo'
      } else {
        errores.value[campo] = `El ${campo} ya existe en otro usuario`
      }
    } else {
      limpiarError(campo)
    }
  } catch (error) {
    console.error('Error al verificar campo:', error)
  } finally {
    procesando.value = false
  }
}


const crearUsuario = async () => {
  if (!form.value.nombre || !form.value.idRol) {
    errorGeneral.value = 'Nombre y Rol son obligatorios'
    return
  }
  
  // Verificar campos únicos antes de enviar
  const camposParaVerificar = ['ci', 'telefono', 'email']
  
  for (const campo of camposParaVerificar) {
    if (form.value[campo]) {
      await verificarCampo(campo)
    }
  }
  
  // Si hay errores, no enviar
  if (Object.keys(errores.value).length > 0) {
    errorGeneral.value = 'Por favor, corrige los errores antes de continuar'
    return
  }
  
  // Limpiar errores y enviar
  errorGeneral.value = ''
  procesando.value = true
  
  router.post('/usuarios', form.value, { 
    onSuccess: () => {
      showSuccessMessage.value = true
      successMessage.value = 'Usuario registrado exitosamente'
      setTimeout(() => {
        router.visit('/usuarios')
      }, 2000)
    },
    onError: (errors) => {
      // Manejar errores del backend
      Object.keys(errors).forEach(key => {
        if (key !== 'general') {
          errores.value[key] = errors[key]
        }
      })
      if (errors.general) {
        errorGeneral.value = errors.general
      }
    },
    onFinish: () => {
      procesando.value = false
    }
  })
}
</script>