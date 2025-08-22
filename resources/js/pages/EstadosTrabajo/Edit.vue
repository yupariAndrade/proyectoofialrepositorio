<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';

interface EstadoTrabajo {
    id: number;
    nombre: string;
}

interface Props {
    estado: EstadoTrabajo;
}

const props = defineProps<Props>();

const form = useForm({
    nombre: props.estado.nombre
});

const submit = () => {
    form.put(`/estados-trabajo/${props.estado.id}`);
};
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
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">✏️ Editar Estado de Trabajo</h1>
                            <p class="text-slate-300">Modifica la información del estado de trabajo</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="/estados-trabajo" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="flex-1 overflow-y-auto p-8">
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8">
                            <form @submit.prevent="submit" class="space-y-6">
                                <!-- Información del Estado -->
                                <div class="bg-gradient-to-r from-slate-700/30 to-slate-800/30 rounded-xl p-6 border border-white/10">
                                    <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Información del Estado
                                    </h3>
                                    
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-slate-300 mb-2">Nombre del Estado *</label>
                                        <input
                                            v-model="form.nombre"
                                            type="text"
                                            id="nombre"
                                            required
                                            class="w-full px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-white placeholder:text-slate-400 backdrop-blur-sm"
                                            placeholder="Ej: Pendiente, En Proceso, Completado, etc."
                                        >
                                        <div v-if="form.errors.nombre" class="text-red-400 text-sm mt-1">{{ form.errors.nombre }}</div>
                                        <p class="text-slate-400 text-sm mt-2">El nombre debe ser único y descriptivo del estado del trabajo</p>
                                    </div>
                                </div>

                                <!-- Información Actual -->
                                <div class="bg-gradient-to-r from-slate-700/30 to-slate-800/30 rounded-xl p-6 border border-white/10">
                                    <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                        <svg class="w-6 h-6 mr-3 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Información Actual
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="bg-slate-800/50 rounded-lg p-4 border border-slate-600/50">
                                            <span class="text-slate-400 text-sm">ID del Estado</span>
                                            <p class="text-white font-semibold">#{{ estado.id }}</p>
                                        </div>
                                        <div class="bg-slate-800/50 rounded-lg p-4 border border-slate-600/50">
                                            <span class="text-slate-400 text-sm">Nombre Actual</span>
                                            <p class="text-white font-semibold">{{ estado.nombre }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones de Acción -->
                                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-white/10">
                                    <a href="/estados-trabajo" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-8 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        Cancelar
                                    </a>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ form.processing ? 'Actualizando...' : 'Actualizar Estado' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </AppContent>
    </AppShell>
</template>




