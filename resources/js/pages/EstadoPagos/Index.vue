<template>
  <AppShell>
    <AppSidebar />
    <!-- Fondo degradado oscuro -->
    <AppContent class="bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 min-h-screen p-6">
      
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1
              class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-pink-600 bg-clip-text text-transparent"
            >
              Estados de Pago
            </h1>
            <p class="text-slate-400 mt-2">Gestión de estados para el control de pagos</p>
          </div>
          <div class="flex space-x-3">
            <Link
              :href="route('estado-pagos.create')"
              class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-xl shadow-md hover:shadow-xl hover:scale-105 transition duration-300"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Nuevo Estado
            </Link>
            <button
              @click="generateReport"
              class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-xl shadow-md hover:shadow-xl hover:scale-105 transition duration-300"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                ></path>
              </svg>
              Generar Reporte
            </button>
          </div>
        </div>
      </div>

      <!-- Search -->
      <div class="mb-6">
        <div class="relative">
          <input
            v-model="searchTerm"
            type="text"
            placeholder="Buscar estados de pago..."
            class="w-full px-4 py-3 pl-12 bg-slate-900/60 border border-slate-700 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition"
          />
          <svg
            class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-slate-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
      </div>

 <!-- Estados en filas -->
<div class="space-y-6 max-w-15x12 mx-auto px-8">
  <div
    v-for="estado in filteredEstados"
    :key="estado.id"
    class="flex flex-col md:flex-row items-start md:items-center justify-between p-5 rounded-2xl 
           bg-gradient-to-r from-slate-800/80 via-slate-900/70 to-slate-800/80 
           border border-slate-700/50 shadow-xl 
           hover:shadow-2xl hover:scale-[1.01] transition-all duration-300"
  >
    <!-- Info izquierda -->
    <div class="flex items-center space-x-4">
      <div
        class="w-12 h-12 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold"
      >
        {{ getInitials(estado.nombre) }}
      </div>
      <div>
        <h3 class="text-lg font-semibold text-white">{{ estado.nombre }}</h3>
        <p class="text-sm text-slate-400">
          ID: {{ estado.id }} · {{ formatDate(estado.created_at) }}
        </p>
      </div>
    </div>

    <!-- Botones derecha -->
    <div class="flex space-x-2 mt-4 md:mt-0">
      <Link
        :href="route('estado-pagos.show', estado.id)"
        class="px-4 py-2 flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow hover:shadow-lg hover:scale-105 transition text-sm"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
          />
        </svg>
        Ver
      </Link>
      <Link
        :href="route('estado-pagos.edit', estado.id)"
        class="px-4 py-2 flex items-center bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg shadow hover:shadow-lg hover:scale-105 transition text-sm"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar
      </Link>
      <button
        @click="deleteEstado(estado.id)"
        class="px-4 py-2 flex items-center bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg shadow hover:shadow-lg hover:scale-105 transition text-sm"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Eliminar
      </button>
    </div>
  </div>
</div>

      <!-- Empty State -->
      <div v-if="filteredEstados.length === 0" class="text-center py-12">
        <h3 class="text-xl font-semibold text-white mb-2">No hay estados de pago</h3>
        <p class="text-slate-400 mb-6">Comienza creando el primer estado de pago para el sistema</p>
        <Link
          :href="route('estado-pagos.create')"
          class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-lg hover:scale-105 hover:from-amber-600 hover:to-pink-600 shadow-lg transition"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Crear Primer Estado
        </Link>
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

const props = defineProps({ estados: { type: Array, required: true } })

const searchTerm = ref('')

const filteredEstados = computed(() => {
  if (!searchTerm.value) return props.estados
  return props.estados.filter((estado) =>
    estado.nombre.toLowerCase().includes(searchTerm.value.toLowerCase())
  )
})

const deleteEstado = (id) => {
  if (confirm('¿Estás seguro de que quieres eliminar este estado de pago?'))
    router.delete(route('estado-pagos.destroy', id))
}
const generateReport = () => alert('Función de reporte en desarrollo')
const getInitials = (nombre) =>
  !nombre ? 'EP' : nombre.split(' ').map((w) => w.charAt(0)).join('').toUpperCase().slice(0, 2)
const formatDate = (dateString) => {
  try {
    return new Date(dateString).toLocaleDateString('es-ES', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  } catch {
    return 'Fecha inválida'
  }
}
</script>
