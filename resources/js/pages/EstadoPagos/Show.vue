<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-500 to-purple-600 bg-clip-text text-transparent">
              Detalles del Estado de Pago
            </h1>
            <p class="text-slate-400 mt-2">
              Información detallada del estado seleccionado
            </p>
          </div>
          <Link :href="route('estado-pagos')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg hover:from-slate-600 hover:to-slate-500 transition-all duration-300 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
          </Link>
        </div>
      </div>

      <!-- Main Content -->
      <div class="max-w-4xl mx-auto">
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl p-8">
          <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-slate-800/50 rounded-xl p-4 border border-white/10">
              <h3 class="text-sm font-medium text-slate-400 mb-2">ID</h3>
              <p class="text-white font-semibold">#{{ estado.id }}</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-4 border border-white/10">
              <h3 class="text-sm font-medium text-slate-400 mb-2">Nombre</h3>
              <p class="text-white font-semibold">{{ estado.nombre }}</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-4 border border-white/10">
              <h3 class="text-sm font-medium text-slate-400 mb-2">Creado</h3>
              <p class="text-white">{{ formatDate(estado.created_at) }}</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-4 border border-white/10">
              <h3 class="text-sm font-medium text-slate-400 mb-2">Actualizado</h3>
              <p class="text-white">{{ formatDate(estado.updated_at) }}</p>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <Link :href="route('estado-pagos.edit', estado.id)" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-500 hover:to-purple-500 transition-all">Editar</Link>
            <Link :href="route('estado-pagos')" class="px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg hover:from-slate-600 hover:to-slate-500 transition-all">Volver</Link>
          </div>
        </div>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'

const props = defineProps({ estado: { type: Object, required: true } })
const estado = props.estado

const formatDate = (d) => { try { return new Date(d).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) } catch { return '—' } }
</script>




