<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Encabezado -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 py-12 px-8">
          <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
              <h1 class="text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-3">📸 Nuevo Servicio Fotográfico</h1>
              <p class="text-slate-300 text-lg">Registra un nuevo servicio para el estudio</p>
            </div>
            <Link :href="route('servicios')" class="bg-gradient-to-r from-slate-600/60 to-slate-700/60 text-white px-6 py-3 rounded-xl border border-white/10 hover:from-slate-700/60 hover:to-slate-800/60 transition-all">Volver</Link>
          </div>
        </div>

        <!-- Formulario -->
        <div class="max-w-4xl mx-auto px-8 py-8">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/10 p-8">
            <form @submit.prevent="guardarServicio" class="space-y-8">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-300 mb-2">Nombre del Servicio *</label>
                  <input v-model="form.nombreServicio" type="text" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-300 mb-2">Precio Referencial (Bs) *</label>
                  <input v-model="form.precioReferencial" type="number" step="0.01" min="0" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                </div>
                <div>
                  <label class="block text-sm font-semibold text-slate-300 mb-2">Fotógrafo Responsable *</label>
                  <select v-model="form.idUsuario" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">Seleccione un fotógrafo</option>
                    <option v-for="usuario in usuarios" :key="usuario.id" :value="usuario.id">{{ usuario.nombre }} {{ usuario.apellidoPaterno || '' }}</option>
                  </select>
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-300 mb-2">Descripción</label>
                  <textarea v-model="form.descripcion" rows="3" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Describe los detalles del servicio..."></textarea>
                </div>

                <!-- Subida de Imagen estilizada -->
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-slate-300 mb-2">Imagen de Referencia</label>
                  <div class="rounded-2xl border-2 border-dashed border-slate-600/60 bg-slate-900/40 p-6 flex flex-col items-center justify-center text-slate-300 hover:border-amber-500/60 transition-colors">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-amber-400 to-pink-500 flex items-center justify-center shadow-lg mb-3">
                      <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2-3h6l2 3h4a1 1 0 011 1v10a1 1 0 01-1 1H3a1 1 0 01-1-1V8a1 1 0 011-1z"/></svg>
                    </div>
                    <p class="text-sm">Haz clic para seleccionar una imagen identificable del servicio</p>
                    <p class="text-xs text-slate-400">PNG, JPG hasta 10MB</p>
                    <input id="imagen" type="file" accept="image/*" @change="seleccionarImagen" class="mt-4 block w-full text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-700/60 file:text-slate-100 hover:file:bg-slate-700" />
                    <div v-if="previewImage" class="mt-4 w-full">
                      <img :src="previewImage" alt="Preview" class="h-40 w-full object-cover rounded-xl border border-slate-600/50 shadow" />
                    </div>
                  </div>
                </div>

                <!-- Estado -->
                <div class="md:col-span-2">
                  <label class="relative inline-flex items-center cursor-pointer select-none">
                    <input v-model="form.estado" type="checkbox" class="sr-only peer" />
                    <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    <span class="ml-3 text-sm font-medium text-slate-300">Servicio Activo</span>
                  </label>
                </div>
              </div>

              <div class="flex items-center justify-end pt-8 border-t border-slate-600/50">
                <Link :href="route('servicios')" class="px-6 py-3 text-slate-300 bg-slate-700/60 hover:bg-slate-700 rounded-xl border border-slate-600/50 mr-3 transition-all">Cancelar</Link>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 text-white rounded-xl font-semibold transition-all">Guardar Servicio</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ usuarios: { type: Array, required: true } })

const form = ref({ nombreServicio: '', precioReferencial: '', descripcion: '', estado: true, imagenReferencia: null, idUsuario: '' })
const previewImage = ref(null)

const seleccionarImagen = (event) => {
  const input = event.target
  if (input && input.files && input.files[0]) {
    form.value.imagenReferencia = input.files[0]
    previewImage.value = URL.createObjectURL(input.files[0])
  }
}

const guardarServicio = () => {
  if (!form.value.nombreServicio || !form.value.precioReferencial || !form.value.idUsuario) {
    return alert('Nombre, precio e idUsuario son obligatorios')
  }
  const fd = new FormData()
  fd.append('nombreServicio', form.value.nombreServicio)
  fd.append('precioReferencial', String(form.value.precioReferencial))
  fd.append('descripcion', form.value.descripcion || '')
  fd.append('estado', form.value.estado ? '1' : '0')
  fd.append('idUsuario', String(form.value.idUsuario))
  if (form.value.imagenReferencia) fd.append('imagenReferencia', form.value.imagenReferencia)
  router.post(route('servicios.store'), fd, { forceFormData: true })
}
</script>