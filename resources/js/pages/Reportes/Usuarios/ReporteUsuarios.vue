<template>
  <AppShell>
    <AppSidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
      <!-- Header -->
      <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">
              📊 Reporte de Usuarios
            </h2>
            <p class="text-slate-300">Generar reporte PDF de todos los usuarios registrados</p>
          </div>
          <div class="flex space-x-4">
            <button 
              @click="generarPDF"
              :disabled="isGenerando"
              class="bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded flex items-center space-x-2"
            >
              <svg v-if="isGenerando" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <span>{{ isGenerando ? 'Generando...' : 'Generar PDF' }}</span>
            </button>
            <Link 
              :href="route('usuarios')"
              class="bg-gray-700 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded"
            >
              Volver
            </Link>
          </div>
        </div>
      </header>

      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-7xl mx-auto">
          
          <!-- Estadísticas del Reporte -->
          <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 p-6 rounded-xl shadow-lg mb-6 border border-white/10">
            <h3 class="text-lg font-semibold mb-4 text-slate-200">📈 Estadísticas del Reporte</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div class="bg-slate-700/50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-400">{{ usuarios.length }}</div>
                <div class="text-sm text-slate-300">Total de Usuarios</div>
              </div>
              <div class="bg-slate-700/50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-400">{{ usuariosActivos }}</div>
                <div class="text-sm text-slate-300">Usuarios Activos</div>
              </div>
              <div class="bg-slate-700/50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-yellow-400">{{ usuariosInactivos }}</div>
                <div class="text-sm text-slate-300">Usuarios Inactivos</div>
              </div>
              <div class="bg-slate-700/50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-400">{{ fechaGeneracion }}</div>
                <div class="text-sm text-slate-300">Fecha de Generación</div>
              </div>
            </div>
          </div>

          <!-- Tabla de Usuarios -->
          <div class="bg-slate-900/50 overflow-hidden shadow-lg sm:rounded-lg border border-white/10">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-white/10">
                <thead class="bg-gradient-to-r from-slate-800/70 to-slate-900/70">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      Nombre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      Rol
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                      Fecha Registro
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-slate-900/50 divide-y divide-white/10">
                  <tr v-for="usuario in usuarios" :key="usuario.id" class="hover:bg-slate-800/50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-200">
                      {{ usuario.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-slate-200">
                        {{ usuario.nombre }} {{ usuario.apellido }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                      {{ usuario.email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getRolClass(usuario.rol?.nombre)">
                        {{ usuario.rol?.nombre || 'Sin rol' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getEstadoClass(usuario.estado)">
                        {{ usuario.estado ? 'Activo' : 'Inactivo' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                      {{ formatDate(usuario.created_at) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Mensaje si no hay usuarios -->
          <div v-if="usuarios.length === 0" class="text-center py-12">
            <p class="text-gray-400 text-lg">No hay usuarios registrados para generar el reporte</p>
          </div>

        </div>
      </main>
    </div>
  </AppShell>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'

// Props
const props = defineProps({
  usuarios: Array,
})

// Estado reactivo
const isGenerando = ref(false)

// Computed properties
const usuariosActivos = computed(() => {
  return props.usuarios.filter(usuario => usuario.estado).length
})

const usuariosInactivos = computed(() => {
  return props.usuarios.filter(usuario => !usuario.estado).length
})

const fechaGeneracion = computed(() => {
  return new Date().toLocaleDateString('es-ES')
})

// Métodos
const formatDate = (date) => {
  if (!date) return 'No definida'
  return new Date(date).toLocaleDateString('es-ES')
}

const getRolClass = (rol) => {
  const classes = {
    'Administrador': 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full',
    'Usuario': 'px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full',
    'Editor': 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full',
  }
  return classes[rol] || 'px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full'
}

const getEstadoClass = (estado) => {
  return estado 
    ? 'px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full'
    : 'px-3 py-1 text-sm font-medium bg-red-100 text-red-800 rounded-full'
}

const generarPDF = () => {
  isGenerando.value = true
  
  // Simular generación de PDF (aquí irá la lógica real)
  setTimeout(() => {
    isGenerando.value = false
    alert('Reporte PDF generado exitosamente')
  }, 2000)
}
</script>
