<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="relative overflow-hidden bg-gradient-to-r from-black via-gray-900 to-black p-6">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2260%22%20height%3D%2260%22%20viewBox%3D%220%200%2060%2060%22%20xmlns%3D%22http://www.w3.org/2000/svg%22%3E%3Cg%20fill%3D%22none%22%20fill-rule%3D%22evenodd%22%3E%3Cg%20fill%3D%22%23ffffff%22%20fill-opacity%3D%220.03%22%3E%3Ccircle%20cx%3D%2230%22%20cy%3D%2230%22%20r%3D%222%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        <div class="relative max-w-7xl mx-auto text-center">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-red-600 via-red-700 to-red-800 rounded-2xl shadow-2xl mb-4 animate-pulse">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/>
            </svg>
          </div>
          <h1 class="text-4xl font-extrabold mb-2 bg-gradient-to-r from-red-500 via-white to-gray-300 bg-clip-text text-transparent drop-shadow-lg">
            Crear Servicio
          </h1>
          <p class="text-lg text-gray-300 opacity-90">
            🛠️ Registra un nuevo servicio en el catálogo
          </p>
        </div>
      </div>

      <!-- Formulario -->
      <div class="max-w-7xl mx-auto px-6 py-8 -mt-4 relative z-10">
        <!-- Mensaje de Éxito -->
        <div v-if="showSuccessMessage" class="mb-6 p-4 bg-green-900/80 border border-green-500/50 text-white rounded-xl backdrop-blur-md shadow-lg animate-pulse">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold">{{ successMessage }}</span>
          </div>
        </div>

        <div class="bg-gradient-to-br from-black/80 via-gray-900/70 to-black/80 backdrop-blur-2xl rounded-2xl shadow-2xl border border-red-500/30 p-8 relative overflow-hidden">
          <!-- Efectos de fondo -->
          <div class="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-red-700/20 to-red-400/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
          <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-gray-800/20 to-gray-600/20 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

          <!-- Errores -->
          <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl">
            <div class="flex items-center gap-3 text-red-300">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <p class="font-semibold">⚠️ Errores en el formulario:</p>
                <ul class="text-sm mt-1 space-y-1">
                  <li v-for="(error, field) in $page.props.errors" :key="field" class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                    <span class="capitalize">{{ field }}:</span> {{ error }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          
          <!-- Form -->
          <form @submit.prevent="guardarServicio" class="relative z-10 space-y-6">
            <!-- Grid 2 col -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Nombre -->
              <div>
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2"/>
                  </svg>
                  Nombre del Servicio <span class="text-red-400"></span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.nombreServicio" 
                    type="text" required maxlength="50"
                    :class="[
                      'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm',
                      $page.props.errors?.nombreServicio 
                        ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                        : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
                    ]"
                    placeholder="EIngrese nombre del servicio..."
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

              <!-- Precio -->
              <div>
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                  </svg>
                  Precio Referencial <span class="text-red-400"></span>
                </label>
                <div class="relative">
                  <input 
                    v-model="form.precioReferencial" 
                    type="text" 
                    pattern="[0-9]+(\.[0-9]{1,2})?"
                    maxlength="8"
                    required
                    @input="validarPrecio"
                    :class="[
                      'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm',
                      $page.props.errors?.precioReferencial || precioInvalido
                        ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                        : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
                    ]"
                    placeholder=" Ingrese Precio Ejm... 20 Bs  150.50 Bs..."
                  />
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent rounded-xl pointer-events-none"></div>
                </div>
                <div class="text-xs text-gray-400 mt-1">Formato: 123.45 (máximo 6 dígitos + 2 decimales)</div>
                <p v-if="$page.props.errors?.precioReferencial || precioInvalido" class="mt-2 text-sm text-red-400 flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  {{ $page.props.errors?.precioReferencial || 'Solo se permiten números y un punto decimal' }}
                </p>
              </div>
            </div>

            <!-- Descripción -->
            <div>
              <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descripción del Servicio
              </label>
              <textarea 
                v-model="form.descripcion" rows="3" maxlength="100"
                class="w-full px-4 py-3 bg-black/60 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:ring-4 focus:ring-red-500/30 focus:border-red-400 transition-all resize-none backdrop-blur-sm"
                placeholder="Describe los detalles del servicio..."
              ></textarea>
              <div class="text-xs text-gray-400 mt-1">{{ form.descripcion.length }}/100 caracteres</div>
            </div>

            <!-- Imagen y Estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Imagen -->
              <div>
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  Imagen de Referencia <span class="text-red-400"></span>
                </label>
                <input 
                  id="imagen" type="file" accept="image/*" required
                  @change="seleccionarImagen"
                  class="block w-full text-gray-200 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gradient-to-r file:from-red-600 file:to-red-700 file:text-white hover:file:from-red-700 hover:file:to-red-800 transition"
                />
                <div class="text-xs text-gray-400 mt-1">Formatos: JPG, PNG, GIF (máx. 10MB)</div>
                <div v-if="previewImage" class="mt-4">
                  <img :src="previewImage" alt="Preview" class="w-full h-32 object-cover rounded-lg border border-gray-600 shadow-md" />
                </div>
              </div>

              <!-- Estado -->
              <div>
                <label class="flex items-center gap-3 text-base font-bold text-white mb-3">
                  <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Estado del Servicio
                </label>
                <label class="inline-flex items-center cursor-pointer">
                  <input v-model="form.estado" type="checkbox" class="sr-only peer" />
                  <div class="w-12 h-6 bg-gray-700 rounded-full peer-checked:bg-gradient-to-r peer-checked:from-red-600 peer-checked:to-red-500 relative transition">
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition peer-checked:translate-x-6"></div>
                  </div>
                  <span class="ml-3 text-sm text-gray-200">
                    {{ form.estado ? 'Disponible' : 'No disponible' }}
                  </span>
                </label>
              </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end pt-6 border-t border-gray-600/30">
              <Link :href="route('servicios')" 
                class="px-6 py-3 mr-3 text-white bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-black rounded-xl transition-all hover:scale-105 shadow-md border border-gray-600/50">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Cancelar
              </Link>
              <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-red-600 via-red-700 to-red-800 hover:from-red-700 hover:via-red-800 hover:to-red-900 text-white rounded-xl font-bold transition-all hover:scale-105 shadow-xl flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Crear Servicio
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
import { router, Link, usePage } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const $page = usePage()
const form = ref({ nombreServicio: '', precioReferencial: '', descripcion: '', estado: true, imagenReferencia: null })
const previewImage = ref(null)
const showSuccessMessage = ref(false)
const successMessage = ref('')
const precioInvalido = ref(false)

const seleccionarImagen = (event) => {
  const input = event.target
  if (input && input.files && input.files[0]) {
    form.value.imagenReferencia = input.files[0]
    previewImage.value = URL.createObjectURL(input.files[0])
  }
}

const validarPrecio = (event) => {
  const valor = event.target.value
  // Solo permitir números y un punto decimal
  const valorLimpio = valor.replace(/[^0-9.]/g, '')
  
  // Evitar múltiples puntos decimales
  const partes = valorLimpio.split('.')
  if (partes.length > 2) {
    form.value.precioReferencial = partes[0] + '.' + partes.slice(1).join('')
  } else {
    form.value.precioReferencial = valorLimpio
  }
  
  // Validar formato final
  const precioPattern = /^\d{1,6}(\.\d{1,2})?$/
  precioInvalido.value = form.value.precioReferencial !== '' && !precioPattern.test(form.value.precioReferencial)
}

const guardarServicio = () => {
  if (!form.value.nombreServicio || !form.value.precioReferencial || !form.value.imagenReferencia) {
    alert('⚠️ Nombre, precio e imagen son obligatorios')
    return
  }
  
  // Validar formato de precio
  const precioPattern = /^\d{1,6}(\.\d{1,2})?$/
  if (!precioPattern.test(form.value.precioReferencial)) {
    alert('⚠️ El precio debe tener máximo 6 dígitos y 2 decimales (ej: 150.50)')
    return
  }
  const fd = new FormData()
  fd.append('nombreServicio', form.value.nombreServicio)
  fd.append('precioReferencial', String(form.value.precioReferencial))
  fd.append('descripcion', form.value.descripcion || '')
  fd.append('estado', form.value.estado ? '1' : '0')
  if (form.value.imagenReferencia) {
    fd.append('imagenReferencia', form.value.imagenReferencia)
  }
  router.post(route('servicios.store'), fd, { 
    forceFormData: true,
    onSuccess: () => {
      showSuccessMessage.value = true
      successMessage.value = 'Servicio registrado exitosamente'
      setTimeout(() => {
        router.visit(route('servicios'))
      }, 2000)
    }
  })
}
</script>
