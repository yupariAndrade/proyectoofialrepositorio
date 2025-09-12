<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';

// Props
const props = defineProps({
    estados: Array
});

// Estados reactivos
const searchTerm = ref('');

// Computed properties
const filteredEstados = computed(() => {
    if (!searchTerm.value) return props.estados;
    
    const search = searchTerm.value.toLowerCase();
    return props.estados.filter(estado => 
        estado.nombre.toLowerCase().includes(search)
    );
});

// Funciones
const deleteEstado = (id) => {
    if (confirm('¿Estás seguro de que quieres eliminar este estado de trabajo?')) {
        router.delete(`/estados-trabajo/${id}`);
    }
};

const generateReport = () => {
    // Implementar generación de reporte
    alert('Función de reporte en desarrollo');
};

const getEstadoColor = (nombre) => {
    const estado = nombre.toLowerCase();
    if (estado.includes('pendiente') || estado.includes('en proceso')) return 'amber';
    if (estado.includes('completado') || estado.includes('finalizado')) return 'green';
    if (estado.includes('cancelado') || estado.includes('rechazado')) return 'red';
    if (estado.includes('aprobado')) return 'blue';
    return 'purple';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <AppShell>
        <AppSidebar />
        <AppContent>
            <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
                <!-- Header -->
                <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">📊 Estados de Trabajo</h1>
                            <p class="text-slate-300">Gestión de estados para los trabajos del estudio fotográfico</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/estados-trabajo/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nuevo Estado
                            </Link>
                            <button @click="generateReport" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
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
                                <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar estados</label>
                                <div class="relative">
                                    <input
                                        v-model="searchTerm"
                                        type="text"
                                        id="search"
                                        placeholder="Buscar por nombre del estado..."
                                        class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Lista de estados en filas -->
<div v-if="filteredEstados.length > 0" class="space-y-4">
  <div
    v-for="estado in filteredEstados"
    :key="estado.id"
    class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-4 hover:shadow-amber-500/20 transition-all duration-300"
  >
    <div class="flex items-center justify-between text-sm">
      
      <!-- Nombre -->
      <div class="flex flex-col w-40">
        <span class="text-slate-400 text-xs">Nombre</span>
        <h3 class="text-white font-semibold">{{ estado.nombre }}</h3>
      </div>

      <!-- Descripción -->
     <!-- <div class="flex flex-col w-56">
        <span class="text-slate-400 text-xs">Descripción</span>
        <span class="text-slate-300">Estado de trabajo del sistema</span>
      </div>-->

      <!-- Estado con color -->
      <div class="flex flex-col w-32">
        <span class="text-slate-400 text-xs">Estado</span>
        <span
          :class="`bg-${getEstadoColor(estado.nombre)}-500/20 text-${getEstadoColor(estado.nombre)}-400 border-${getEstadoColor(estado.nombre)}-500/30 px-3 py-1 rounded-full text-sm font-semibold border text-center`"
        >
          {{ estado.nombre }}
        </span>
      </div>

      <!-- Fecha -->
      <div class="flex flex-col w-32">
        <span class="text-slate-400 text-xs">Fecha Registro</span>
        <span class="text-slate-500 text-xs">{{ formatDate(estado.created_at) }}</span>
      </div>

      <!-- Acciones -->
      <div class="flex items-center space-x-2">
        <Link :href="`/estados-trabajo/${estado.id}`" class="text-amber-400 hover:text-amber-300 p-2 rounded-lg hover:bg-amber-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
        </Link>
        <Link :href="`/estados-trabajo/${estado.id}/edit`" class="text-pink-400 hover:text-pink-300 p-2 rounded-lg hover:bg-pink-500/20">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
          </svg>
        </Link>
        <button @click="deleteEstado(estado.id)" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/20">
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
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-4">No hay estados de trabajo registrados</h3>
                            <p class="text-slate-300 mb-8">Comienza agregando el primer estado de trabajo al sistema.</p>
                            <Link href="/estados-trabajo/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crear Primer Estado
                            </Link>
                        </div>
                    </div>
                </main>
            </div>
        </AppContent>
    </AppShell>
</template>






