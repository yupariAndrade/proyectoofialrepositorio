<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">👥 Usuarios</h1>
            <p class="text-slate-300">Gestión del personal del estudio fotográfico</p>
          </div>
          <div class="flex items-center space-x-4">
            <Link href="/usuarios/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">
              <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              Nuevo Usuario
            </Link>
            <button @click="generateReport" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
              <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Generar Reporte
            </button>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <!-- Filtros y búsqueda -->
        <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6 mb-8">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar usuarios</label>
              <div class="relative">
                <input v-model="searchTerm" type="text" id="search" placeholder="Buscar por nombre, email o teléfono..." class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-4">
              <div>
                <label for="roleFilter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por rol</label>
                <select v-model="filterRole" id="roleFilter" class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm">
                  <option value="">Todos</option>
                  <option v-for="rol in availableRoles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>

<!-- Lista de usuarios en filas -->
<div v-if="filteredUsuarios.length" class="space-y-4">
  <div
    v-for="usuario in filteredUsuarios"
    :key="usuario.id"
    class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-4 hover:shadow-amber-500/20 transition-all duration-300"
  >
    <div class="flex items-center justify-between text-sm">

      <!-- Avatar con inicial -->
      <div class="flex items-center space-x-4 w-48">
        <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
          {{ getInitials(usuario.nombre) }}
        </div>
        <div class="flex flex-col">
          <span class="text-slate-400 text-xs">Nombre</span>
          <h3 class="text-white font-semibold">{{ usuario.nombre }}</h3>
        </div>
      </div>

      <!-- Email -->
      <div class="flex flex-col w-64">
        <span class="text-slate-400 text-xs">Email</span>
        <span class="text-slate-300">{{ usuario.email || 'Sin email' }}</span>
      </div>

      <!-- Teléfono -->
      <div class="flex flex-col w-40">
        <span class="text-slate-400 text-xs">Teléfono</span>
        <span class="text-slate-300">{{ usuario.telefono || 'No especificado' }}</span>
      </div>

      <!-- Rol -->
      <div class="flex flex-col w-40">
        <span class="text-slate-400 text-xs">Rol</span>
        <span class="text-amber-400 text-sm">{{ getRoleName(usuario.idRol, usuario.rol) }}</span>
      </div>

      <!-- Estado -->
      <div class="flex flex-col w-32">
        <span class="text-slate-400 text-xs">Estado</span>
        <span
          :class="usuario.estado ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'"
          class="px-3 py-1 rounded-full text-sm font-semibold border text-center"
        >
          {{ usuario.estado ? 'Activo' : 'Inactivo' }}
        </span>
      </div>

      <!-- Fecha -->
      <div class="flex flex-col w-40">
        <span class="text-slate-400 text-xs">Creado</span>
        <span class="text-slate-500 text-xs">{{ formatDate(usuario.created_at) }}</span>
      </div>

      <!-- Acciones -->
      <div class="flex items-center space-x-2">
        <Link :href="`/usuarios/${usuario.id}`" class="text-amber-400 hover:text-amber-300 p-2 rounded-lg hover:bg-amber-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </Link>
        <Link :href="`/usuarios/${usuario.id}/edit`" class="text-pink-400 hover:text-pink-300 p-2 rounded-lg hover:bg-pink-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </Link>
        <button @click="deleteUser(usuario.id)" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>


        <!-- Estado vacío -->
        <div v-else class="text-center py-16">
          <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-12">
            <div class="w-24 h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">No hay usuarios registrados</h3>
            <p class="text-slate-300 mb-8">Comienza agregando el primer usuario.</p>
            <Link href="/usuarios/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              Crear Primer Usuario
            </Link>
          </div>
        </div>
      </main>
    </div>
  </AppShell>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

const props = defineProps({
  usuarios: { type: Array, required: true },
  roles: { type: Array, required: true }
})

const searchTerm = ref('')
const filterRole = ref('')
const availableRoles = computed(() => props.roles || [])

const filteredUsuarios = computed(() => {
  let filtered = props.usuarios || []
  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase()
    filtered = filtered.filter((u) =>
      (u.nombre || '').toLowerCase().includes(term) ||
      (u.email || '').toLowerCase().includes(term) ||
      (u.telefono || '').toLowerCase().includes(term)
    )
  }
  if (filterRole.value !== '') {
    filtered = filtered.filter((u) => String(u.idRol) === String(filterRole.value))
  }
  return filtered
})

const generateReport = () => {
  // Crear un enlace temporal para descargar el PDF
  const link = document.createElement('a')
  link.href = window.route('reportes.usuarios.pdf')
  link.download = 'reporte-usuarios.pdf'
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}
const deleteUser = (id) => { if (confirm('¿Eliminar usuario?')) router.delete(window.route('usuarios.destroy', id)) }
const getInitials = (name) => (name || '').split(' ').map((n) => n[0]).join('').toUpperCase()
const getRoleName = (idRol, rol) => (rol ? rol.nombre : 'Sin rol')
const formatDate = (dateString) => { try { return new Date(dateString).toLocaleDateString('es-ES') } catch { return 'N/A' } }

onMounted(() => {})
</script>