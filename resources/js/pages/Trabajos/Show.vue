<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppContent from '@/components/AppContent.vue';

interface Trabajo {
    id: number;
    idCliente: number;
    idServicio: number;
    idUsuario: number;
    fechaRegistro: string;
    fechaEntrega?: string;
    idEstado: number;
    created_at: string;
    updated_at: string;
    cliente?: {
        id: number;
        nombre: string;
        apellido?: string;
        ci?: string;
        telefono?: string;
        correoElectronico?: string;
    };
    servicio?: {
        id: number;
        nombreServicio: string;
        precioReferencial: number;
        descripcion?: string;
    };
    usuario?: {
        id: number;
        nombre: string;
    };
    estado?: {
        id: number;
        nombre: string;
    };
}

interface Props {
    trabajo: Trabajo;
}

const props = defineProps<Props>();

const getEstadoColor = (nombre: string) => {
    const estado = nombre.toLowerCase();
    if (estado.includes('pendiente') || estado.includes('en proceso')) return 'amber';
    if (estado.includes('completado') || estado.includes('finalizado')) return 'green';
    if (estado.includes('cancelado') || estado.includes('rechazado')) return 'red';
    if (estado.includes('aprobado')) return 'blue';
    return 'purple';
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB'
    }).format(amount);
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
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">💼 Detalles del Trabajo</h1>
                            <p class="text-slate-300">Información completa del trabajo registrado</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link :href="`/trabajos/${trabajo.id}/edit`" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-pink-500/25">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </Link>
                            <a href="/trabajos" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
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
                    <div class="max-w-6xl mx-auto">
                        <!-- Información Principal -->
                        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8 mb-8">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold text-white mb-2">{{ trabajo.servicio?.nombreServicio || 'Servicio no especificado' }}</h2>
                                    <p class="text-slate-300">Trabajo registrado en el sistema</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span :class="`bg-${getEstadoColor(trabajo.estado?.nombre || '')}-500/20 text-${getEstadoColor(trabajo.estado?.nombre || '')}-400 border-${getEstadoColor(trabajo.estado?.nombre || '')}-500/30 px-4 py-2 rounded-full text-sm font-semibold border backdrop-blur-sm`">
                                        {{ trabajo.estado?.nombre || 'Sin estado' }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="bg-gradient-to-r from-slate-700/30 to-slate-800/30 rounded-xl p-6 border border-white/10">
                                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Información Básica
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-slate-400 text-sm">ID del Trabajo</span>
                                            <p class="text-white font-semibold text-lg">#{{ trabajo.id }}</p>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 text-sm">Servicio</span>
                                            <p class="text-white font-semibold">{{ trabajo.servicio?.nombreServicio || 'No especificado' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 text-sm">Precio Referencial</span>
                                            <p class="text-amber-400 font-semibold">{{ formatCurrency(trabajo.servicio?.precioReferencial || 0) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-slate-700/30 to-slate-800/30 rounded-xl p-6 border border-white/10">
                                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        Información del Cliente
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-slate-400 text-sm">Cliente</span>
                                            <p class="text-white font-semibold">{{ trabajo.cliente?.nombre }} {{ trabajo.cliente?.apellido || '' }}</p>
                                        </div>
                                        <div v-if="trabajo.cliente?.ci">
                                            <span class="text-slate-400 text-sm">CI</span>
                                            <p class="text-white font-semibold">{{ trabajo.cliente.ci }}</p>
                                        </div>
                                        <div v-if="trabajo.cliente?.telefono">
                                            <span class="text-slate-400 text-sm">Teléfono</span>
                                            <p class="text-white font-semibold">{{ trabajo.cliente.telefono }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-slate-700/30 to-slate-800/30 rounded-xl p-6 border border-white/10">
                                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        Asignación y Estado
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <span class="text-slate-400 text-sm">Usuario Asignado</span>
                                            <p class="text-white font-semibold">{{ trabajo.usuario?.nombre || 'No asignado' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 text-sm">Estado</span>
                                            <p class="text-white font-semibold">{{ trabajo.estado?.nombre || 'Sin estado' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas y Detalles -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8">
                                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fechas del Trabajo
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-slate-400 text-sm">Fecha de Registro</span>
                                        <p class="text-white font-semibold">{{ formatDate(trabajo.fechaRegistro) }}</p>
                                    </div>
                                    <div v-if="trabajo.fechaEntrega">
                                        <span class="text-slate-400 text-sm">Fecha de Entrega</span>
                                        <p class="text-white font-semibold">{{ formatDate(trabajo.fechaEntrega) }}</p>
                                    </div>
                                    <div v-else>
                                        <span class="text-slate-400 text-sm">Fecha de Entrega</span>
                                        <p class="text-slate-500 font-semibold">No establecida</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8">
                                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Fechas de Registro
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-slate-400 text-sm">Fecha de Creación</span>
                                        <p class="text-white font-semibold">{{ formatDate(trabajo.created_at) }}</p>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 text-sm">Última Actualización</span>
                                        <p class="text-white font-semibold">{{ formatDate(trabajo.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Adicional del Cliente -->
                        <div v-if="trabajo.cliente?.correoElectronico" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8 mb-8">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Información de Contacto
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <span class="text-slate-400 text-sm">Correo Electrónico</span>
                                    <p class="text-white font-semibold">{{ trabajo.cliente.correoElectronico }}</p>
                                </div>
                                <div v-if="trabajo.cliente.telefono">
                                    <span class="text-slate-400 text-sm">Teléfono</span>
                                    <p class="text-white font-semibold">{{ trabajo.cliente.telefono }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción del Servicio -->
                        <div v-if="trabajo.servicio?.descripcion" class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8 mb-8">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Descripción del Servicio
                            </h3>
                            <p class="text-slate-300 leading-relaxed">{{ trabajo.servicio.descripcion }}</p>
                        </div>

                        <!-- Acciones -->
                        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-lg rounded-xl shadow-2xl border border-white/10 p-8">
                            <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Acciones Disponibles
                            </h3>
                            <div class="flex flex-wrap gap-4">
                                <Link :href="`/trabajos/${trabajo.id}/edit`" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-pink-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105 shadow-lg shadow-pink-500/25">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar Trabajo
                                </Link>
                                <a href="/trabajos" class="bg-gradient-to-r from-slate-600 to-slate-700 text-white px-6 py-3 rounded-xl font-semibold hover:from-slate-700 hover:to-slate-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Ver Todos los Trabajos
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </AppContent>
    </AppShell>
</template>
