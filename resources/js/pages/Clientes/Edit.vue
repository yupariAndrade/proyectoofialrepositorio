<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">✏️ Editar Cliente</h1>
              <p class="text-slate-300">Modifica la información del cliente</p>
            </div>
            <Link :href="route('clientes')" class="bg-gradient-to-r from-slate-600/60 to-slate-700/60 text-white px-6 py-3 rounded-xl font-medium border border-white/10 hover:from-slate-700/60 hover:to-slate-800/60 transition-all">Volver</Link>
          </div>
        </header>

        <!-- Form -->
        <main class="p-8">
          <div class="max-w-4xl mx-auto bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-2xl border border-white/10 p-8 shadow-2xl">
            <form @submit.prevent="submit" class="space-y-8">
              <div class="grid md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Nombre *</label>
                  <input v-model="form.nombre" type="text" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                  <div v-if="form.errors.nombre" class="text-red-400 text-sm mt-1">{{ form.errors.nombre }}</div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Apellido *</label>
                  <input v-model="form.apellido" type="text" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                  <div v-if="form.errors.apellido" class="text-red-400 text-sm mt-1">{{ form.errors.apellido }}</div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">CI *</label>
                  <input v-model="form.ci" type="text" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                  <div v-if="form.errors.ci" class="text-red-400 text-sm mt-1">{{ form.errors.ci }}</div>
                </div>
                <div>
                  <label class="block text-sm text-slate-300 mb-2">Teléfono *</label>
                  <input v-model="form.telefono" type="tel" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                  <div v-if="form.errors.telefono" class="text-red-400 text-sm mt-1">{{ form.errors.telefono }}</div>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm text-slate-300 mb-2">Correo Electrónico *</label>
                  <input v-model="form.correoElectronico" type="email" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" />
                  <div v-if="form.errors.correoElectronico" class="text-red-400 text-sm mt-1">{{ form.errors.correoElectronico }}</div>
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm text-slate-300 mb-2">Usuario Asignado *</label>
                  <select v-model="form.idUsuario" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="">Seleccione un usuario</option>
                    <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.nombre }}</option>
                  </select>
                  <div v-if="form.errors.idUsuario" class="text-red-400 text-sm mt-1">{{ form.errors.idUsuario }}</div>
                </div>
              </div>

              <div class="flex justify-end space-x-3 pt-6 border-t border-white/10">
                <Link :href="route('clientes')" class="px-6 py-3 bg-slate-700/60 text-white rounded-xl hover:bg-slate-700 transition-all">Cancelar</Link>
                <button :disabled="form.processing" class="px-6 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white rounded-xl hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all disabled:opacity-50">{{ form.processing ? 'Actualizando...' : 'Actualizar Cliente' }}</button>
              </div>
            </form>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ cliente: { type: Object, required: true }, usuarios: { type: Array, required: true } })

const form = useForm({
  nombre: props.cliente?.nombre || '',
  apellido: props.cliente?.apellido || '',
  ci: props.cliente?.ci || '',
  telefono: props.cliente?.telefono || '',
  correoElectronico: props.cliente?.correoElectronico || '',
  idUsuario: props.cliente?.idUsuario || ''
})

const submit = () => {
  form.put(route('clientes.update', props.cliente.id), {
    preserveScroll: true,
    onSuccess: () => router.visit(route('clientes')),
  })
}
</script>
