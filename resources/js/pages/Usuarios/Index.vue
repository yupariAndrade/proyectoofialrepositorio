<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-gray-800 flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-lg border-b border-red-500/20 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent mb-2 flex items-center">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
              </svg>
              Usuarios
            </h1>
            <p class="text-gray-300 text-sm sm:text-base">Gestión del personal del FOTO STUDIO </p>
          </div>
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
            <Link href="/usuarios/create" class="bg-gradient-to-r from-red-500 via-red-600 to-red-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-red-600 hover:via-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 flex items-center justify-center text-sm sm:text-base">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              <span class="hidden sm:inline">Nuevo Usuario</span>
              <span class="sm:hidden">Nuevo</span>
            </Link>
            <button @click="generateReport" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-gray-700 hover:to-gray-800 transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center text-sm sm:text-base">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span class="hidden sm:inline">Generar Reporte</span>
              <span class="sm:hidden">Reporte</span>
            </button>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <!-- Filtros y búsqueda -->
        <div class="bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-red-500/20 p-4 sm:p-6 mb-6 sm:mb-8">
          <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex-1">
              <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Buscar usuarios</label>
              <div class="relative">
                            <input v-model="searchTerm" type="text" id="search" placeholder="Buscar por nombre, apellidos, email o teléfono..." class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-3 bg-black/60 border border-gray-600/50 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base text-white placeholder:text-gray-400 backdrop-blur-sm" />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-4 w-4 sm:h-6 sm:w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
              </div>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
              <div class="flex-1 sm:flex-none">
                <label for="roleFilter" class="block text-sm font-medium text-gray-300 mb-2">Filtrar por rol</label>
                <select v-model="filterRole" id="roleFilter" class="w-full sm:w-auto px-3 sm:px-4 py-2 sm:py-3 bg-black/60 border border-gray-600/50 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base text-white backdrop-blur-sm">
                  <option value="">Todos</option>
                  <option v-for="rol in availableRoles" :key="rol.id" :value="rol.id">{{ rol.nombre }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>

<!-- Lista de usuarios en filas -->
        <div v-if="filteredUsuarios.length" class="space-y-3 sm:space-y-4">
  <div
    v-for="usuario in filteredUsuarios"
    :key="usuario.id"
            class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-red-500/20 p-3 sm:p-4 hover:shadow-red-500/20 transition-all duration-300"
          >
            <!-- Mobile Layout -->
            <div class="block sm:hidden">
            <div class="flex items-center space-x-3 mb-3">
              <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-sm">
                {{ getInitials(usuario.nombre) }}
              </div>
              <div class="flex-1 min-w-0">
                <h3 class="text-white font-semibold text-sm truncate">
                  {{ usuario.nombre }} {{ usuario.apellidoPaterno || '' }} {{ usuario.apellidoMaterno || '' }}
                </h3>
                <p class="text-gray-400 text-xs truncate">{{ usuario.email || 'Sin email' }}</p>
              </div>
                <div class="flex items-center space-x-1">
                  <Link :href="`/usuarios/${usuario.id}`" class="text-red-400 hover:text-red-300 p-1.5 rounded-lg hover:bg-red-500/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </Link>
                  <Link :href="`/usuarios/${usuario.id}/edit`" class="text-red-400 hover:text-red-300 p-1.5 rounded-lg hover:bg-red-500/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </Link>
                  <button @click="deleteUser(usuario.id)" class="text-red-400 hover:text-red-300 p-1.5 rounded-lg hover:bg-red-500/20 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div>
                  <span class="text-gray-400">Teléfono:</span>
                  <p class="text-gray-300">{{ usuario.telefono || 'No especificado' }}</p>
                </div>
                <div>
                  <span class="text-gray-400">Rol:</span>
                  <p class="text-red-400">{{ getRoleName(usuario.idRol, usuario.rol) }}</p>
                </div>
                <div>
                  <span class="text-gray-400">Estado:</span>
                  <span
                    :class="usuario.estado ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'"
                    class="px-2 py-1 rounded-full text-xs font-semibold border"
                  >
                    {{ usuario.estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </div>
                <div>
                  <span class="text-gray-400">Creado:</span>
                  <p class="text-gray-500 text-xs">{{ formatDate(usuario.created_at) }}</p>
                </div>
              </div>
            </div>

            <!-- Desktop Layout - Tabla Responsiva -->
            <div class="hidden sm:block">
              <div class="w-full">
                <!-- Primera fila: Avatar, Nombre Completo, Email -->
                <div class="grid grid-cols-12 gap-3 items-center text-base mb-3">
                  <!-- Avatar y Nombre Completo -->
                  <div class="col-span-12 md:col-span-5 lg:col-span-5">
                    <div class="flex items-center space-x-3">
                      <div class="w-12 h-12 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-base">
                        {{ getInitials(usuario.nombre) }}
                      </div>
                      <div class="flex flex-col min-w-0">
                        <span class="text-gray-400 text-sm">Nombre Completo</span>
                        <h3 class="text-white font-semibold truncate text-base">
                          {{ usuario.nombre }} {{ usuario.apellidoPaterno || '' }} {{ usuario.apellidoMaterno || '' }}
                        </h3>
                      </div>
                    </div>
                  </div>

                  <!-- Email -->
                  <div class="col-span-12 md:col-span-7 lg:col-span-7">
                    <div class="flex flex-col">
                      <span class="text-gray-400 text-sm">Email</span>
                      <span class="text-gray-300 truncate text-base">{{ usuario.email || 'Sin email' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Segunda fila: Teléfono, Rol, Estado, Fecha, Acciones -->
                <div class="grid grid-cols-12 gap-3 items-center text-base">
                  <!-- Teléfono -->
                  <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <div class="flex flex-col">
                      <span class="text-gray-400 text-sm">Teléfono</span>
                      <span class="text-gray-300 text-base">{{ usuario.telefono || 'No especificado' }}</span>
                    </div>
                  </div>

                  <!-- Rol -->
                  <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <div class="flex flex-col">
                      <span class="text-gray-400 text-sm">Rol</span>
                      <span class="text-red-400 text-base">{{ getRoleName(usuario.idRol, usuario.rol) }}</span>
                    </div>
                  </div>

                  <!-- Estado -->
                  <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <div class="flex flex-col">
                      <span class="text-gray-400 text-sm">Estado</span>
                      <span
                        :class="usuario.estado ? 'bg-green-500/20 text-green-400 border-green-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'"
                        class="px-3 py-1 rounded-full text-base font-semibold border text-center"
                      >
                        {{ usuario.estado ? 'Activo' : 'Inactivo' }}
                      </span>
                    </div>
                  </div>

                  <!-- Fecha -->
                  <div class="col-span-6 md:col-span-2 lg:col-span-2">
                    <div class="flex flex-col">
                      <span class="text-gray-400 text-sm">Fecha de registro:</span>
                      <span class="text-gray-500 text-base">{{ formatDate(usuario.created_at) }}</span>
                    </div>
                  </div>

                  <!-- Acciones -->
                  <div class="col-span-12 md:col-span-4 lg:col-span-4">
                    <div class="flex items-center justify-end space-x-3">
                      <Link :href="`/usuarios/${usuario.id}`" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/20 transition-all duration-200" title="Ver">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </Link>
                      <Link :href="`/usuarios/${usuario.id}/edit`" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/20 transition-all duration-200" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </Link>
                     <!-- <button @click="deleteUser(usuario.id)" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/20 transition-all duration-200" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                      </button>-->
                    </div>
                  </div>
                </div>
              </div>
            </div>
  </div>
</div>

        <!-- Estado vacío -->
        <div v-else class="text-center py-12 sm:py-16">
          <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-red-500/20 p-8 sm:p-12">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-r from-red-400 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
              <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-white mb-3 sm:mb-4">No hay usuarios registrados</h3>
            <p class="text-gray-300 mb-6 sm:mb-8 text-sm sm:text-base">Comienza agregando el primer usuario.</p>
            <Link href="/usuarios/create" class="bg-gradient-to-r from-red-500 via-red-600 to-red-700 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:from-red-600 hover:via-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 inline-flex items-center text-sm sm:text-base">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              <span class="hidden sm:inline">Crear Primer Usuario</span>
              <span class="sm:hidden">Crear Usuario</span>
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
      (u.apellidoPaterno || '').toLowerCase().includes(term) ||
      (u.apellidoMaterno || '').toLowerCase().includes(term) ||
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
  // Generar reporte PDF de usuarios
  window.open('/reportes/usuarios/pdf', '_blank');
}

const deleteUser = (id) => { if (confirm('¿Eliminar usuario?')) router.delete(window.route('usuarios.destroy', id)) }
const getInitials = (name) => (name || '').split(' ').map((n) => n[0]).join('').toUpperCase()
const getRoleName = (idRol, rol) => (rol ? rol.nombre : 'Sin rol')
const formatDate = (dateString) => { try { return new Date(dateString).toLocaleDateString('es-ES') } catch { return 'N/A' } }

onMounted(() => {})
</script> 