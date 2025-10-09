<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';

const $page = usePage()

// Estado para controlar la visibilidad de los mensajes flash
const showFlashMessage = ref(true)

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

interface FlashMessages {
    success?: string;
    error?: string;
}

const props = defineProps<Props>();

// Estados reactivos
const searchTerm = ref('');
const filterUsuario = ref<string>('');

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

    // Ordenar por fecha de creación descendente (más reciente primero)
    filtered.sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return dateB.getTime() - dateA.getTime(); // Orden descendente
    });

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

// Computed properties para flash messages
const flashSuccess = computed(() => {
    const flash = $page.props.flash as FlashMessages | undefined;
    return flash?.success;
});

const flashError = computed(() => {
    const flash = $page.props.flash as FlashMessages | undefined;
    return flash?.error;
});

// Auto-dismiss para mensajes flash
onMounted(() => {
    if ($page.props.flash?.success || $page.props.flash?.error) {
        setTimeout(() => {
            showFlashMessage.value = false
        }, 4000) // Desaparece después de 4 segundos
    }
})

// Funciones
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
            <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex-1">
                <!-- Header -->
                <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="text-center lg:text-left">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">👥 Clientes</h1>
                            <p class="text-slate-300 text-sm sm:text-base">Gestión de clientes</p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                            <Link href="/clientes/create" class="w-full sm:w-auto bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 text-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="hidden sm:inline">Registrar Nuevo Cliente</span>
                                <span class="sm:hidden">Nuevo Cliente</span>
                            </Link>
                            <button @click="generateReport" class="w-full sm:w-auto bg-gradient-to-r from-slate-600 to-slate-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">Generar Reporte</span>
                                <span class="sm:hidden">Reporte</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    <!-- Notificaciones Flash -->
                    <div v-if="flashSuccess && showFlashMessage" class="mb-4 flex items-center justify-center">
                        <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 animate-pulse">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold text-sm">{{ flashSuccess }}</span>
                        </div>
                    </div>
                    
                    <div v-if="flashError && showFlashMessage" class="mb-4 flex items-center justify-center">
                        <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-3 animate-pulse">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-semibold text-sm">{{ flashError }}</span>
                        </div>
                    </div>

                    <!-- Filtros y búsqueda -->
                    <div class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-4 sm:p-6 mb-6 sm:mb-8">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 sm:gap-6">
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Buscar clientes</label>
                                <div class="relative">
                                    <input
                                        v-model="searchTerm"
                                        type="text"
                                        id="search"
                                        placeholder="Buscar por nombre, CI, teléfono o email..."
                                        class="w-full pl-10 sm:pl-12 pr-4 py-2 sm:py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm sm:text-base lg:text-lg text-white placeholder:text-slate-400 backdrop-blur-sm"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5 lg:h-6 lg:w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 sm:gap-4">
                                <div class="flex-1 sm:flex-none sm:min-w-[200px]">
                                    <label for="usuarioFilter" class="block text-sm font-medium text-slate-300 mb-2">Filtrar por usuario</label>
                                    <select
                                        v-model="filterUsuario"
                                        id="usuarioFilter"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-slate-800/50 border border-slate-600/50 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm sm:text-base lg:text-lg text-white backdrop-blur-sm"
                                    >
                                        <option value="">Todos</option>
                                        <option v-for="usuario in availableUsuarios" :key="usuario.id" :value="usuario.id">{{ usuario.nombre }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de clientes -->
                    <div v-if="filteredClientes.length > 0" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[800px]">
                                <thead class="bg-gradient-to-r from-slate-700/60 to-slate-800/60 backdrop-blur-sm">
                                    <tr>
                                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Cliente</span>
                                            </div>
                                        </th>
                                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-slate-300 border-b border-white/10 hidden md:table-cell">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Correo</span>
                                            </div>
                                        </th>
                                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-slate-300 border-b border-white/10 hidden lg:table-cell">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Teléfono</span>
                                            </div>
                                        </th>
                                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-semibold text-slate-300 border-b border-white/10 hidden xl:table-cell">
                                            <div class="flex items-center gap-1 sm:gap-2">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Fecha Registro</span>
                                            </div>
                                        </th>
                                        <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm font-semibold text-slate-300 border-b border-white/10">
                                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Acciones</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    <tr v-for="cliente in filteredClientes" :key="cliente.id" class="hover:bg-slate-700/30 transition-colors duration-200">
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                            <div class="flex items-center gap-2 sm:gap-3">
                                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-orange-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm shadow-lg ring-1 ring-white/20">
                                                    {{ getInitials(cliente.nombre, cliente.apellido) }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-white font-semibold text-sm sm:text-base truncate">{{ cliente.nombre }} {{ cliente.apellido }}</div>
                                                  <!--  <div class="text-slate-400 text-xs hidden sm:block">ID: {{ cliente.id }}</div>-->
                                                    <!-- Información adicional en móvil -->
                                                    <div class="text-slate-400 text-xs sm:hidden">
                                                        <div v-if="cliente.correoElectronico" class="truncate">{{ cliente.correoElectronico }}</div>
                                                        <div v-if="cliente.telefono" class="truncate">{{ cliente.telefono }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 hidden md:table-cell">
                                            <div class="text-white font-medium text-sm sm:text-base truncate">{{ cliente.correoElectronico || 'No especificado' }}</div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                            <div class="text-white font-medium text-sm sm:text-base">{{ cliente.telefono || 'No especificado' }}</div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 hidden xl:table-cell">
                                            <div class="text-slate-300 text-xs sm:text-sm">{{ formatDate(cliente.created_at) }}</div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                                <Link :href="`/clientes/${cliente.id}`" class="p-1.5 sm:p-2 text-yellow-400 hover:text-yellow-300 hover:bg-yellow-400/10 rounded-lg transition-colors duration-200" title="Ver detalles">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </Link>
                                                <Link :href="`/clientes/${cliente.id}/edit`" class="p-1.5 sm:p-2 text-pink-400 hover:text-pink-300 hover:bg-pink-400/10 rounded-lg transition-colors duration-200" title="Editar">
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Estado vacío -->
                    <div v-else class="text-center py-8 sm:py-12 lg:py-16">
                        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-6 sm:p-8 lg:p-12">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6 shadow-lg">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-3 sm:mb-4">No hay clientes registrados</h3>
                            <p class="text-slate-300 mb-6 sm:mb-8 text-sm sm:text-base">Comienza agregando el primer cliente al sistema de gestión.</p>
                            <Link href="/clientes/create" class="bg-gradient-to-r from-amber-500 via-pink-500 to-purple-600 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-xl font-semibold hover:from-amber-600 hover:via-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-amber-500/25 inline-flex items-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="hidden sm:inline">Crear Primer Cliente</span>
                                <span class="sm:hidden">Crear Cliente</span>
                            </Link>
                        </div>
                    </div>
                </main>
            </div>
        </AppContent>
    </AppShell>
</template>
