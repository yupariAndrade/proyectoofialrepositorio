<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({
  clientes: { type: Array, required: true }
})

const searchTerm = ref('')
const filterUsuario = ref('')

const filteredClientes = computed(() => {
  let filtered = props.clientes || []
  if (searchTerm.value) {
    const s = searchTerm.value.toLowerCase()
    filtered = filtered.filter((c) =>
      (c.nombre || '').toLowerCase().includes(s) ||
      (c.apellido || '').toLowerCase().includes(s) ||
      (c.ci || '').toLowerCase().includes(s) ||
      (c.telefono || '').toLowerCase().includes(s) ||
      (c.correoElectronico || '').toLowerCase().includes(s)
    )
  }
  if (filterUsuario.value) {
    filtered = filtered.filter((c) => String(c.idUsuario) === String(filterUsuario.value))
  }
  return filtered
})

const availableUsuarios = computed(() => {
  const users = new Map()
  ;(props.clientes || []).forEach((c) => {
    if (c.usuario) users.set(c.usuario.id, c.usuario.nombre)
  })
  return Array.from(users.entries()).map(([id, nombre]) => ({ id, nombre }))
})

const deleteCliente = (id) => {
  if (confirm('¿Eliminar cliente?')) router.delete(route('clientes.destroy', id))
}

const getInitials = (nombre, apellido) => {
  const n = (nombre || '').charAt(0).toUpperCase()
  const a = (apellido || '').charAt(0).toUpperCase()
  return `${n}${a}`
}

const formatDate = (dateString) => {
  try {
    return new Date(dateString).toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: 'numeric' })
  } catch (e) {
    return '—'
  }
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
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">👥 Clientes</h1>
              <p class="text-slate-300">Gestión de clientes</p>
            </div>
            <div class="flex items-center space-x-4">
              <Link :href="route('clientes.create')" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-amber-500/25">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Nuevo Cliente
              </Link>
            </div>
          </div>
        </header>

        <main class="p-8">
          <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-6 mb-8">
            <div class="grid md:grid-cols-3 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-300 mb-2">Buscar</label>
                <input v-model="searchTerm" type="text" placeholder="Nombre, CI, teléfono, email..." class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Usuario</label>
                <select v-model="filterUsuario" class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl text-white">
                  <option value="">Todos</option>
                  <option v-for="u in availableUsuarios" :key="u.id" :value="u.id">{{ u.nombre }}</option>
                </select>
              </div>
            </div>
          </div>

<div v-if="filteredClientes.length" class="space-y-4">
  <div v-for="c in filteredClientes" :key="c.id" 
       class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-xl border border-white/10 p-4">
       
    <div class="flex items-center justify-between text-sm">
      
      <!-- Nombre y Ref -->
      <div class="flex flex-col w-40">
        <span class="text-slate-400 text-xs">Cliente</span>
        <h3 class="text-white font-semibold">{{ c.nombre }} {{ c.apellido || '' }}</h3>
        <span class="text-slate-400 text-xs">Ref: {{ c.ci || '—' }}</span>
      </div>

      <!-- Correo -->
      <div class="flex flex-col w-48">
        <span class="text-slate-400 text-xs">Correo</span>
        <span class="text-slate-300">{{ c.correoElectronico || 'Sin email' }}</span>
      </div>

      <!-- Teléfono -->
      <div class="flex flex-col w-32">
        <span class="text-slate-400 text-xs">Teléfono</span>
        <span class="text-slate-300">{{ c.telefono || '—' }}</span>
      </div>

      <!-- Fecha -->
      <div class="flex flex-col w-28 text-right">
        <span class="text-slate-400 text-xs">Fecha Registro</span>
        <span class="text-slate-500 text-xs">{{ formatDate(c.created_at) }}</span>
      </div>

      <!-- Botones -->
      <div class="flex items-center space-x-2">
        <Link :href="route('clientes.show', c.id)" class="text-amber-400 hover:text-amber-300 p-2 rounded-lg hover:bg-amber-500/10">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 
                     0 8.268 2.943 9.542 7-1.274 4.057-5.064 
                     7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </Link>
        <Link :href="route('clientes.edit', c.id)" class="text-pink-400 hover:text-pink-300 p-2 rounded-lg hover:bg-pink-500/10">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 
                     0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                     2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </Link>
        <button @click="deleteCliente(c.id)" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M19 7l-.867 12.142A2 2 0 
                     0116.138 21H7.862a2 2 0 
                     01-1.995-1.858L5 7m5 
                     4v6m4-6v6m1-10V4a1 1 0 
                     00-1-1h-4a1 1 0 
                     00-1 1v3M4 7h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>



          <div v-else class="text-center py-16 text-slate-300">No hay clientes</div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template> 