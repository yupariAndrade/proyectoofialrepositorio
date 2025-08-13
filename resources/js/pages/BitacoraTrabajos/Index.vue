<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent">Bitácora de Trabajos</h1>
              <p class="text-slate-300">Registros de acciones realizadas en trabajos</p>
            </div>
            <Link :href="route('bitacora-trabajos.create')" class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-indigo-600 text-white rounded-lg hover:from-cyan-600 hover:to-indigo-700 transition">Nuevo registro</Link>
          </div>
        </header>

        <main class="p-8">
          <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-6 mb-6">
            <div class="grid md:grid-cols-3 gap-4">
              <div class="relative md:col-span-2">
                <input v-model="term" placeholder="Buscar por acción, descripción, cliente..." class="w-full pl-11 pr-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" />
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <div>
                <input v-model="date" type="date" class="w-full px-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" />
              </div>
            </div>
          </div>

          <div v-if="filtered.length" class="grid lg:grid-cols-2 gap-6">
            <div v-for="b in filtered" :key="b.id" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-white/10 rounded-xl p-6 backdrop-blur-lg">
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h3 class="text-white font-semibold">{{ b.accion || 'Acción' }}</h3>
                  <p class="text-slate-300 text-sm">Trabajo #{{ b.idTrabajo }} — {{ b.trabajo?.servicio?.nombreServicio || 'Servicio' }}</p>
                </div>
                <span class="text-xs text-slate-400">{{ formatDateTime(b.fecha) }}</span>
              </div>
              <p v-if="b.descripcion" class="text-slate-200 mb-4">{{ b.descripcion }}</p>
              <div class="flex items-center justify-end gap-2">
                <Link :href="route('bitacora-trabajos.show', b.id)" class="px-3 py-2 text-cyan-400 hover:text-cyan-300 hover:bg-cyan-500/10 rounded-lg">Ver</Link>
                <Link :href="route('bitacora-trabajos.edit', b.id)" class="px-3 py-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-500/10 rounded-lg">Editar</Link>
                <button @click="del(b.id)" class="px-3 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg">Eliminar</button>
              </div>
            </div>
          </div>
          <div v-else class="text-center text-slate-300 py-16">Sin registros</div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ bitacoras: { type: Array, default: () => [] } })
const term = ref('')
const date = ref('')

const filtered = computed(() => {
  let list = props.bitacoras || []
  if (term.value) {
    const s = term.value.toLowerCase()
    list = list.filter(b => (b.accion || '').toLowerCase().includes(s) || (b.descripcion || '').toLowerCase().includes(s) || (b.trabajo?.cliente?.nombre || '').toLowerCase().includes(s))
  }
  if (date.value) list = list.filter(b => (b.fecha || '').slice(0,10) === date.value)
  return list
})

const del = (id) => { if (confirm('¿Eliminar registro?')) router.delete(route('bitacora-trabajos.destroy', id)) }
const formatDateTime = (d) => { try { return new Date(d).toLocaleString('es-ES') } catch { return '—' } }
</script>
