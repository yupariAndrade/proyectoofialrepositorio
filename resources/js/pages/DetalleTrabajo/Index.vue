<script setup>
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({ detalles: { type: Array, default: () => [] } })

const searchTerm = ref('')
const filterTrabajo = ref('')

const availableTrabajos = computed(() => {
  const map = new Map()
  ;(props.detalles || []).forEach((d) => {
    const t = d.trabajo
    if (t) map.set(t.id, `${t.servicio?.nombreServicio || 'Servicio'} — ${t.cliente?.nombre || 'Cliente'} ${t.cliente?.apellido || ''}`.trim())
  })
  return Array.from(map.entries()).map(([id, label]) => ({ id, label }))
})

const filteredDetalles = computed(() => {
  let list = props.detalles || []
  if (filterTrabajo.value) list = list.filter((d) => String(d.idTrabajo) === String(filterTrabajo.value) || String(d.trabajo?.id) === String(filterTrabajo.value))
  if (searchTerm.value) {
    const s = searchTerm.value.toLowerCase()
    list = list.filter((d) =>
      (d.descripcion || '').toLowerCase().includes(s) ||
      (d.tamano || '').toLowerCase().includes(s) ||
      (d.color || '').toLowerCase().includes(s) ||
      (d.modelo || '').toLowerCase().includes(s) ||
      (d.tipoDocumento || '').toLowerCase().includes(s) ||
      (d.tipoEvento || '').toLowerCase().includes(s) ||
      (d.trabajo?.servicio?.nombreServicio || '').toLowerCase().includes(s) ||
      (d.trabajo?.cliente?.nombre || '').toLowerCase().includes(s) ||
      (d.trabajo?.cliente?.apellido || '').toLowerCase().includes(s)
    )
  }
  return list
})

const deleteDetalle = (id) => {
  if (confirm('¿Eliminar este detalle de trabajo?')) router.delete(route('detalle-trabajo.destroy', id))
}

const formatDate = (dateString) => { try { return new Date(dateString).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' }) } catch { return '—' } }
</script>

<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-300 via-sky-400 to-indigo-400 bg-clip-text text-transparent mb-2">🧩 Detalles de Trabajo</h1>
              <p class="text-slate-300">Listado de detalles asociados a trabajos</p>
            </div>
            <Link :href="route('detalle-trabajo.create')" class="bg-gradient-to-r from-cyan-500 via-sky-500 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-cyan-600 hover:via-sky-600 hover:to-indigo-700 transition-all shadow-lg">Nuevo detalle</Link>
          </div>
        </header>

        <!-- Filtros -->
        <main class="p-8">
          <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-6 mb-6 backdrop-blur-lg">
            <div class="grid md:grid-cols-3 gap-4">
              <div class="relative md:col-span-2">
                <input v-model="searchTerm" type="text" placeholder="Buscar por descripción, servicio, cliente..." class="w-full pl-11 pr-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent" />
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              </div>
              <div>
                <select v-model="filterTrabajo" class="w-full px-3 py-3 bg-slate-800/50 border border-slate-600/50 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                  <option value="">Todos los trabajos</option>
                  <option v-for="t in availableTrabajos" :key="t.id" :value="t.id">{{ t.label }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Lista -->
          <div v-if="filteredDetalles.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="d in filteredDetalles" :key="d.id" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 border border-white/10 rounded-xl p-6 backdrop-blur-lg shadow-2xl">
              <div class="flex items-center justify-between mb-3">
                <div>
                  <h3 class="text-white font-semibold">{{ d.trabajo?.servicio?.nombreServicio || 'Servicio' }}</h3>
                  <p class="text-slate-300 text-sm">Trabajo #{{ d.trabajo?.id || d.idTrabajo }} — {{ d.trabajo?.cliente?.nombre || 'Cliente' }} {{ d.trabajo?.cliente?.apellido || '' }}</p>
                </div>
                <span class="text-xs text-slate-400">{{ formatDate(d.created_at) }}</span>
              </div>

              <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="col-span-2" v-if="d.descripcion">
                  <div class="text-slate-400">Descripción</div>
                  <div class="text-white">{{ d.descripcion }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Tamaño</div>
                  <div class="text-white font-medium">{{ d.tamano || '—' }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Color</div>
                  <div class="text-white font-medium">{{ d.color || '—' }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Modelo</div>
                  <div class="text-white font-medium">{{ d.modelo || '—' }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Cantidad</div>
                  <div class="text-white font-medium">{{ d.cantidad || '—' }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Tipo Doc.</div>
                  <div class="text-white font-medium">{{ d.tipoDocumento || '—' }}</div>
                </div>
                <div>
                  <div class="text-slate-400">Tipo Evento</div>
                  <div class="text-white font-medium">{{ d.tipoEvento || '—' }}</div>
                </div>
              </div>

              <div class="flex items-center justify-end gap-2 mt-5">
                <Link :href="route('detalle-trabajo.show', d.id)" class="px-3 py-2 text-cyan-400 hover:text-cyan-300 hover:bg-cyan-500/10 rounded-lg transition">Ver</Link>
                <Link :href="route('detalle-trabajo.edit', d.id)" class="px-3 py-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-500/10 rounded-lg transition">Editar</Link>
                <button @click="deleteDetalle(d.id)" class="px-3 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition">Eliminar</button>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-16 text-slate-300">No hay detalles registrados</div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>
