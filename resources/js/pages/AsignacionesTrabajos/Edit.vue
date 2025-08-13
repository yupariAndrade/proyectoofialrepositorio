<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="mb-8 bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10">
        <div class="px-8 py-6 flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Editar Asignación</h1>
            <p class="text-slate-400">Modifica la información de la asignación</p>
          </div>
          <Link :href="route('asignaciones-trabajos')" class="px-4 py-2 bg-gradient-to-r from-slate-700/60 to-slate-600/60 text-white rounded-lg border border-white/10">Volver</Link>
        </div>
      </div>

      <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-white/10 p-8">
          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <label class="block text-sm text-slate-300 mb-2">Trabajo *</label>
              <select v-model="form.idTrabajo" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" required>
                <option value="">Selecciona un trabajo</option>
                <option v-for="t in trabajos" :key="t.id" :value="t.id">{{ t.servicio?.nombreServicio || 'Servicio' }} — {{ t.cliente?.nombre || 'Cliente' }}</option>
              </select>
              <div v-if="form.errors.idTrabajo" class="text-red-400 text-sm mt-1">{{ form.errors.idTrabajo }}</div>
            </div>

            <div>
              <label class="block text-sm text-slate-300 mb-2">Usuario *</label>
              <select v-model="form.idUsuario" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" required>
                <option value="">Selecciona un usuario</option>
                <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.nombre }}</option>
              </select>
              <div v-if="form.errors.idUsuario" class="text-red-400 text-sm mt-1">{{ form.errors.idUsuario }}</div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm text-slate-300 mb-2">Turno</label>
                <input v-model="form.turno" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" />
                <div v-if="form.errors.turno" class="text-red-400 text-sm mt-1">{{ form.errors.turno }}</div>
              </div>
              <div>
                <label class="block text-sm text-slate-300 mb-2">Fecha de Asignación *</label>
                <input type="date" v-model="form.fechaAsignacion" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500" required />
                <div v-if="form.errors.fechaAsignacion" class="text-red-400 text-sm mt-1">{{ form.errors.fechaAsignacion }}</div>
              </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-white/10">
              <Link :href="route('asignaciones-trabajos')" class="px-6 py-3 bg-slate-700/60 text-white rounded-lg">Cancelar</Link>
              <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg">{{ form.processing ? 'Actualizando...' : 'Actualizar' }}</button>
            </div>
          </form>
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

const props = defineProps({ asignacion: { type: Object, required: true }, trabajos: { type: Array, required: true }, usuarios: { type: Array, required: true } })

const form = useForm({ idTrabajo: props.asignacion.idTrabajo || '', idUsuario: props.asignacion.idUsuario || '', turno: props.asignacion.turno || '', fechaAsignacion: props.asignacion.fechaAsignacion || '' })
const submit = () => form.put(route('asignaciones-trabajos.update', props.asignacion.id))
</script>
