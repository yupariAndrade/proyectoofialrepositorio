<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header más compacto -->
      <div class="relative overflow-hidden bg-gradient-to-r from-black via-gray-900 to-black p-6">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.05%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%222%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        <div class="relative max-w-7xl mx-auto text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-600 via-red-700 to-red-800 rounded-2xl shadow-xl mb-4 animate-pulse">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
          </div>
          <h1 class="text-4xl font-bold text-white mb-2 bg-gradient-to-r from-red-500 via-white to-gray-300 bg-clip-text text-transparent">
            Editar Servicio
          </h1>
          <p class="text-lg text-gray-300">
            ✨ Modifica los detalles del servicio fotográfico ✨
          </p>
        </div>
      </div>

      <!-- Formulario más ancho y compacto -->
      <div class="max-w-7xl mx-auto px-6 py-8 -mt-4 relative z-10">
        
        <!-- Mensaje de Éxito -->
        <div v-if="$page.props.flash?.success" class="mb-4 flex items-center justify-center">
          <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 animate-pulse">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold text-sm">{{ $page.props.flash.success }}</span>
          </div>
        </div>

        <div class="bg-gradient-to-br from-black/80 via-gray-900/80 to-black/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-red-500/30 p-8 relative overflow-hidden">
          <!-- Efectos de fondo -->
          <div class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-red-500/20 to-red-400/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-br from-gray-800/20 to-gray-600/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
          
          <!-- Mensajes de error -->
          <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl">
            <div class="flex items-center gap-3 text-red-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="font-semibold">Errores de validación:</p>
                <ul class="text-sm mt-1 space-y-1">
                  <li v-for="(error, field) in $page.props.errors" :key="field" class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                    <span class="capitalize">{{ field }}:</span> {{ error }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          
          <form @submit.prevent="submit" class="relative z-10 space-y-6">
            <!-- Grid de 2 columnas para hacer el formulario más ancho -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Nombre del Servicio -->
              <div class="group">
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/>
                  </svg>
                  Nombre del Servicio <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.nombreServicio" 
                    type="text" 
                    required 
                    maxlength="50"
                    :class="[
                      'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm',
                      $page.props.errors?.nombreServicio 
                        ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                        : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
                    ]"
                    placeholder="Ej: Fotos de Boda, Retratos... (máx. 50 caracteres)" 
                  />
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent rounded-xl pointer-events-none"></div>
                </div>
                <div class="text-xs text-gray-400 mt-1">{{ form.nombreServicio.length }}/50 caracteres</div>
                <p v-if="$page.props.errors?.nombreServicio" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $page.props.errors.nombreServicio }}
                </p>
              </div>

              <!-- Precio Referencial -->
              <div class="group">
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                  </svg>
                  Precio Referencial <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.precioReferencial" 
                    type="text" 
                    pattern="[0-9]+(\.[0-9]{1,2})?"
                    inputmode="numeric"
                    maxlength="8"
                    required 
                    @input="form.precioReferencial = form.precioReferencial.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')"
                    :class="[
                      'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm',
                      $page.props.errors?.precioReferencial
                        ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                        : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
                    ]"
                    placeholder="0.00 (ej: 150.50)" 
                  />
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent rounded-xl pointer-events-none"></div>
                </div>
                <div v-if="$page.props.errors?.precioReferencial" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $page.props.errors.precioReferencial }}
                </div>
              </div>
            </div>

            <!-- Descripción en una sola columna -->
            <div class="group">
              <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descripción del Servicio
              </label>
              <div class="relative">
                <textarea 
                  v-model="form.descripcion" 
                  rows="3" 
                  maxlength="100"
                  :class="[
                    'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm resize-none',
                    $page.props.errors?.descripcion 
                      ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                      : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
                  ]"
                  placeholder="Describe los detalles y características de tu servicio (máx. 100 caracteres)..."
                ></textarea>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent rounded-xl pointer-events-none"></div>
              </div>
              <div class="text-xs text-gray-400 mt-1">{{ form.descripcion.length }}/100 caracteres</div>
              <p v-if="$page.props.errors?.descripcion" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $page.props.errors.descripcion }}
              </p>
            </div>

            <!-- Grid de 2 columnas para imagen y estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Subida de Imagen más compacta -->
              <div class="group">
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Imagen de Referencia
                </label>
                <div class="relative">
                  <div class="rounded-xl border-2 border-dashed border-red-400/60 bg-gradient-to-br from-black/60 to-gray-900/60 p-4 flex flex-col items-center justify-center text-gray-300 hover:border-red-400/80 hover:bg-gray-800/80 transition-all duration-300 group-hover:scale-[1.01] backdrop-blur-sm">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg mb-3 group-hover:scale-110 transition-transform duration-300">
                      <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-3h6l2 3h4a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V8a1 1 0 011-1z"/>
                      </svg>
                    </div>
                    <p class="text-sm font-medium mb-2">📸 Cambiar imagen</p>
                    <p class="text-xs text-gray-400 text-center mb-3">PNG, JPG hasta 10MB</p>
                    <input 
                      id="imagen" 
                      type="file" 
                      accept="image/*" 
                      @change="seleccionarImagen" 
                      class="block w-full text-gray-300 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-red-600 file:to-red-700 file:text-white hover:file:from-red-700 file:to-red-800 file:transition-all file:duration-300 cursor-pointer" 
                    />
                    
                    <!-- Preview de imagen actual o nueva -->
                    <div v-if="previewImage || servicio.imagenReferencia" class="mt-4 w-full">
                      <div class="relative group">
                        <img :src="previewImage || `/storage/${servicio.imagenReferencia}`" alt="Preview" class="w-full h-32 object-cover rounded-lg border border-gray-600 shadow-lg group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-2 left-2 text-white font-semibold text-xs">✨ {{ previewImage ? 'Nueva imagen' : 'Imagen actual' }}</div>
                      </div>
                    </div>
                  </div>
                </div>
                <p v-if="$page.props.errors?.imagenReferencia" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $page.props.errors.imagenReferencia }}
                </p>
              </div>

              <!-- Estado del Servicio -->
              <div class="group">
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Estado del Servicio
                </label>
                <div class="flex items-center gap-3">
                  <label class="relative inline-flex items-center cursor-pointer select-none group">
                    <input v-model="form.estado" type="checkbox" class="sr-only peer" />
                    <div class="w-12 h-6 bg-gradient-to-r from-gray-600 to-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/30 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-red-500 peer-checked:to-red-600 shadow-lg"></div>
                    <span class="ml-3 text-sm font-medium text-gray-300 group-hover:text-white transition-colors">
                      {{ form.estado ? '🟢 Disponible' : '🔴 No disponible' }}
                    </span>
                  </label>
                </div>
                <p v-if="$page.props.errors?.estado" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $page.props.errors.estado }}
                </p>
              </div>
            </div>

            <!-- Botones de acción más compactos -->
            <div class="flex items-center justify-end pt-6 border-t border-gray-600/30">
              <Link 
                :href="route('servicios')" 
                class="px-6 py-3 text-gray-300 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-black rounded-xl border border-gray-600/50 mr-3 transition-all duration-300 hover:scale-105 hover:shadow-lg backdrop-blur-sm group"
              >
                <span class="flex items-center gap-2">
                  <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                  </svg>
                  Cancelar
                </span>
              </Link>
              
              <button 
                type="submit" 
                class="px-8 py-3 bg-gradient-to-r from-red-600 via-red-700 to-red-800 hover:from-red-700 hover:via-red-800 hover:to-red-900 text-white rounded-xl font-bold transition-all duration-300 hover:scale-105 hover:shadow-xl shadow-lg group relative overflow-hidden"
              >
                <span class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative flex items-center gap-2">
                  <svg class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  Actualizar Servicio
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const $page = usePage()
const props = defineProps({
  servicio: { type: Object, required: true },
  usuarios: { type: Array, required: true }
})

// Usar useForm con los datos del servicio
const form = useForm({
  nombreServicio: props.servicio.nombreServicio || '',
  precioReferencial: props.servicio.precioReferencial || '',
  descripcion: props.servicio.descripcion || '',
  estado: Boolean(props.servicio.estado),
  imagenReferencia: null
})

const previewImage = ref('')

const seleccionarImagen = (event) => {
  const input = event.target
  if (input && input.files && input.files[0]) {
    form.imagenReferencia = input.files[0]
    previewImage.value = URL.createObjectURL(input.files[0])
  }
}

const submit = () => {
  form.put(route('servicios.update', props.servicio.id), {
    onSuccess: () => {
      // Redirigir a la lista de servicios después de actualizar
      window.location.href = route('servicios')
    },
    onError: (errors) => {
      console.error('Errores de validación:', errors)
    }
  })
}
</script>
