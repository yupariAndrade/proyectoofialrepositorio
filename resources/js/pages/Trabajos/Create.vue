<script setup>
import { useForm, Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { reactive } from 'vue'

const props = defineProps({ clientes: Array, servicios: Array, usuarios: Array, estados: Array })

const nuevoItem = () => ({ idCliente: '', idServicio: '', idUsuario: '', fechaRegistro: new Date().toISOString().split('T')[0], fechaEntrega: '', idEstado: '' })
const items = reactive([nuevoItem()])

const agregarOtro = () => { items.push(nuevoItem()) }
const eliminarItem = (idx) => { if (items.length > 1) items.splice(idx, 1) }

const submit = async () => {
  // Enviar en serie para reutilizar la ruta existente sin cambiar backend
  for (const it of items) {
    // Validación simple en cliente
    if (!it.idCliente || !it.idServicio || !it.idUsuario || !it.idEstado || !it.fechaRegistro) continue
    await router.post(route('trabajos.store'), it, { preserveScroll: true, preserveState: true })
  }
  router.visit(route('trabajos'))
}
</script>

<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">➕ Alta rápida de Trabajos</h1>
              <p class="text-slate-300">Agrega varios trabajos antes de guardar</p>
            </div>
            <Link :href="route('trabajos')" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl">Volver</Link>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
          <div class="max-w-5xl mx-auto space-y-6">
            <div v-for="(it, idx) in items" :key="idx" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6">
              <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl text-white font-semibold">Trabajo #{{ idx + 1 }}</h2>
                <button v-if="items.length > 1" @click="eliminarItem(idx)" class="px-3 py-1 text-red-300 hover:text-red-200 hover:bg-red-500/10 rounded-lg">Eliminar</button>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Cliente *</label>
                  <select v-model="it.idCliente" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white">
                    <option value="">Seleccionar cliente</option>
                    <option v-for="c in props.clientes" :key="c.id" :value="c.id">{{ c.nombre }} {{ c.apellido || '' }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Servicio *</label>
                  <select v-model="it.idServicio" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white">
                    <option value="">Seleccionar servicio</option>
                    <option v-for="s in props.servicios" :key="s.id" :value="s.id">{{ s.nombreServicio }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Usuario Asignado *</label>
                  <select v-model="it.idUsuario" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white">
                    <option value="">Seleccionar usuario</option>
                    <option v-for="u in props.usuarios" :key="u.id" :value="u.id">{{ u.nombre }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Estado *</label>
                  <select v-model="it.idEstado" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white">
                    <option value="">Seleccionar estado</option>
                    <option v-for="e in props.estados" :key="e.id" :value="e.id">{{ e.nombre }}</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Fecha de Registro *</label>
                  <input v-model="it.fechaRegistro" type="date" required class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-2">Fecha de Entrega</label>
                  <input v-model="it.fechaEntrega" type="date" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white" />
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <button type="button" @click="agregarOtro" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-xl hover:from-cyan-600 hover:to-indigo-700 transition">Agregar otro</button>
              <div class="flex items-center gap-3">
                <Link :href="route('trabajos')" class="px-6 py-3 bg-slate-700/60 text-white rounded-xl">Cancelar</Link>
                <button @click="submit" class="px-8 py-3 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white rounded-xl">Guardar todos</button>
              </div>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
