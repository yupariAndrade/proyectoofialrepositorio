<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';

const $page = usePage()

interface Cliente {
    id: number;
    nombre: string;
    apellido?: string;
    ci?: string;
    telefono?: string;
    correoElectronico?: string;
    idUsuario: number;
    usuario?: {
        id: number;
        nombre: string;
    };
    created_at: string;
}

interface Props {
    clientes: Cliente[];
}

const props = defineProps<Props>();

// Estados reactivos
const searchTerm = ref('');
const filterUsuario = ref('');

// Computed properties
const filteredClientes = computed(() => {
    let filtered = props.clientes;

    // Filtro por búsqueda
    if (searchTerm.value) {
        const search = searchTerm.value.toLowerCase();
        filtered = filtered.filter(cliente => 
            cliente.nombre.toLowerCase().includes(search) ||
            (cliente.apellido && cliente.apellido.toLowerCase().includes(search)) ||
            (cliente.ci && cliente.ci.toLowerCase().includes(search)) ||
            (cliente.telefono && cliente.telefono.includes(search)) ||
            (cliente.correoElectronico && cliente.correoElectronico.toLowerCase().includes(search))
        );
    }

    // Filtro por usuario
    if (filterUsuario.value) {
        filtered = filtered.filter(cliente => cliente.idUsuario.toString() === filterUsuario.value);
    }

    return filtered;
});

const availableUsuarios = computed(() => {
    const usuarios = new Map();
    props.clientes.forEach(cliente => {
        if (cliente.usuario) {
            usuarios.set(cliente.usuario.id, cliente.usuario.nombre);
        }
    });
    return Array.from(usuarios.entries()).map(([id, nombre]) => ({ id, nombre }));
});

// Funciones
const deleteCliente = (id: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
        router.delete(`/clientes/${id}`);
    }
};

const generateReport = () => {
    // Generar reporte PDF de clientes
    window.open('/reportes/clientes/pdf', '_blank');
};

const getInitials = (nombre: string, apellido?: string) => {
    return nombre ? nombre.charAt(0).toUpperCase() : 'C';
};

const formatDate = (dateString: string) => {
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
            <div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-slate-900 flex-1 relative overflow-hidden">
                <!-- Efectos de fondo animados -->
                <div class="absolute inset-0 bg-gradient-to-br from-red-900/5 via-purple-900/5 to-pink-900/5 animate-pulse"></div>
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-900/10 via-transparent to-transparent"></div>
                <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-purple-900/10 via-transparent to-transparent"></div>
                <!-- Header -->
                 <!-- Header -->
                <header class="relative bg-gradient-to-r from-black/90 via-gray-900/90 to-gray-800/90 backdrop-blur-xl border-b border-red-600/30 px-8 py-6 shadow-2xl">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 via-gray-600/10 to-black/10 backdrop-blur-sm"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-red-500 via-gray-200 to-white bg-clip-text text-transparent mb-2">👥 Clientes</h1>
                            <p class="text-gray-300">Gestión de clientes</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/clientes/create" class="bg-gradient-to-r from-red-600 rom-red-500 from-red-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-red-700 hover:via-gray-800 hover:to-black transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Registrar Nuevo Cliente
                            </Link>
                            <button @click="generateReport" class="bg-gradient-to-r from-gray-700 to-gray-800 text-white px-6 py-3 rounded-xl font-semibold hover:from-gray-800 hover:to-black transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Generar Reporte PDF
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="relative z-10 flex-1 overflow-y-auto p-8">
                    <!-- Notificaciones Flash -->
                    <div v-if="$page.props.flash?.success" class="mb-6 relative p-4 bg-gradient-to-r from-green-900/90 to-emerald-900/90 border border-green-400/30 text-white rounded-xl backdrop-blur-xl shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-emerald-500/10 rounded-xl"></div>
                        <div class="relative z-10 flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-lg">{{ $page.props.flash.success }}</span>
                        </div>
                    </div>
                    
                    <div v-if="$page.props.flash?.error" class="mb-6 relative p-4 bg-gradient-to-r from-red-900/90 to-rose-900/90 border border-red-400/30 text-white rounded-xl backdrop-blur-xl shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-rose-500/10 rounded-xl"></div>
                        <div class="relative z-10 flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-red-400 to-rose-400 rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-lg">{{ $page.props.flash.error }}</span>
                        </div>
                    </div>

                    <!-- Filtros y búsqueda -->
                    <div class="relative bg-gradient-to-r from-black/60 via-gray-900/60 to-slate-900/60 backdrop-blur-xl rounded-xl shadow-2xl border border-red-500/20 p-6 mb-8">
                        <!-- Efecto cristal -->
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 via-purple-500/5 to-pink-500/5 rounded-xl"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/3 to-transparent rounded-xl"></div>
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar clientes</label>
                                <div class="relative">
                                    <input
                                        v-model="searchTerm"
                                        type="text"
                                        id="search"
                                        placeholder="Buscar por nombre, CI, teléfono o email..."
                                        class="w-full pl-12 pr-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white placeholder:text-slate-400 backdrop-blur-sm"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div>
                                    <label for="usuarioFilter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por usuario</label>
                                    <select
                                        v-model="filterUsuario"
                                        id="usuarioFilter"
                                        class="px-4 py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg text-white backdrop-blur-sm"
                                    >
                                        <option value="">Todos</option>
                                        <option v-for="usuario in availableUsuarios" :key="usuario.id" :value="usuario.id">{{ usuario.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de clientes -->
                    <div v-if="filteredClientes.length > 0" class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-slate-900/60 backdrop-blur-xl rounded-xl shadow-2xl border border-red-500/20 overflow-hidden">
                        <!-- Efecto cristal -->
                        <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-purple-500/5 to-pink-500/5"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/3 to-transparent"></div>
                        <div class="relative z-10 overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gradient-to-r from-red-900/40 via-purple-900/40 to-pink-900/40 backdrop-blur-sm">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Cliente
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                Correo
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                Teléfono
                                            </div>
                                        </th>


                                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Fecha Registro
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Acciones
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    <tr v-for="cliente in filteredClientes" :key="cliente.id" class="hover:bg-slate-700/30 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-r from-orange-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg ring-1 ring-white/20">
                                                    {{ getInitials(cliente.nombre, cliente.apellido) }}
                                                </div>
                                                <div>
                                                    <div class="text-white font-semibold">{{ cliente.nombre }} {{ cliente.apellido }}</div>
                                                  <!--  <div class="text-slate-400 text-xs">Ref: {{ cliente.id }}</div>-->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-white font-medium">{{ cliente.correoElectronico || 'No especificado' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-white font-medium">{{ cliente.telefono || 'No especificado' }}</div>
                                        </td>


                                        <td class="px-6 py-4">
                                            <div class="text-slate-300 text-sm">{{ formatDate(cliente.created_at) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <Link :href="`/clientes/${cliente.id}`" class="p-2 text-yellow-400 hover:text-yellow-300 hover:bg-yellow-400/10 rounded-lg transition-colors duration-200" title="Ver detalles">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </Link>
                                                <Link :href="`/clientes/${cliente.id}/edit`" class="p-2 text-pink-400 hover:text-pink-300 hover:bg-pink-400/10 rounded-lg transition-colors duration-200" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </Link>
                                                <button @click="deleteCliente(cliente.id)" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-colors duration-200" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Estado vacío -->
                    <div v-else class="text-center py-16">
                        <div class="relative bg-gradient-to-br from-black/60 via-gray-900/60 to-slate-900/60 backdrop-blur-xl rounded-xl shadow-2xl border border-red-500/20 p-12">
                            <!-- Efecto cristal -->
                            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 via-purple-500/5 to-pink-500/5 rounded-xl"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/3 to-transparent rounded-xl"></div>
                            <div class="relative z-10 w-24 h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="relative z-10 text-2xl font-bold text-white mb-4">No hay clientes registrados</h3>
                            <p class="relative z-10 text-slate-300 mb-8">Comienza agregando el primer cliente al sistema de gestión.</p>
                            <Link href="/clientes/create" class="relative z-10 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crear Primer Cliente
                            </Link>
                        </div>
                    </div>
                </main>
            </div>
        </AppContent>
    </AppShell>
</template>
