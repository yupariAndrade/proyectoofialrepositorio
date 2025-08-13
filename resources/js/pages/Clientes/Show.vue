<template>
  <AppShell>
    <AppSidebar />
    <AppContent>
      <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black">
        <!-- Header -->
        <header class="bg-gradient-to-r from-slate-800/50 to-slate-900/50 backdrop-blur-lg border-b border-white/10 px-8 py-6">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold bg-gradient-to-r from-amber-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-2">🧾 Detalles del Cliente</h1>
              <p class="text-slate-300">Información completa del cliente</p>
            </div>
            <div class="flex space-x-3">
              <Link :href="route('clientes.edit', cliente.id)" class="bg-gradient-to-r from-amber-500 to-pink-600 text-white px-6 py-3 rounded-xl font-medium hover:from-amber-600 hover:to-pink-700 transition-all">Editar</Link>
              <Link :href="route('clientes')" class="bg-gradient-to-r from-slate-600/60 to-slate-700/60 text-white px-6 py-3 rounded-xl font-medium border border-white/10 hover:from-slate-700/60 hover:to-slate-800/60 transition-all">Volver</Link>
            </div>
          </div>
        </header>

        <!-- Content -->
        <main class="p-8">
          <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-white/10 p-6 backdrop-blur-lg shadow-2xl">
                <h2 class="text-xl font-semibold text-white mb-4">Información Personal</h2>
                <div class="grid md:grid-cols-2 gap-6">
                  <div>
                    <div class="text-slate-400 text-sm mb-1">Nombre Completo</div>
                    <div class="text-white font-semibold text-lg">{{ cliente.nombre || 'No especificado' }} {{ cliente.apellido || '' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm mb-1">CI</div>
                    <div class="text-white font-mono">{{ cliente.ci || 'No especificado' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm mb-1">Teléfono</div>
                    <div class="text-white">{{ cliente.telefono || 'No especificado' }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm mb-1">Correo</div>
                    <div class="text-white break-all">{{ cliente.correoElectronico || 'No especificado' }}</div>
                  </div>
                </div>
              </div>

              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-white/10 p-6 backdrop-blur-lg shadow-2xl">
                <h2 class="text-xl font-semibold text-white mb-4">Usuario Asignado</h2>
                <div v-if="cliente.usuario" class="flex items-center">
                  <div class="w-12 h-12 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">{{ getInitials(cliente.usuario.nombre, cliente.usuario.apellido || '') }}</div>
                  <div>
                    <div class="text-white font-semibold">{{ cliente.usuario.nombre }} {{ cliente.usuario.apellido || '' }}</div>
                    <div class="text-slate-400">{{ cliente.usuario.email }}</div>
                  </div>
                </div>
                <div v-else class="text-slate-400">No hay usuario asignado</div>
              </div>
            </div>

            <div class="space-y-6">
              <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-white/10 p-6 backdrop-blur-lg shadow-2xl">
                <h2 class="text-xl font-semibold text-white mb-4">Información del Sistema</h2>
                <div class="space-y-3">
                  <div>
                    <div class="text-slate-400 text-sm mb-1">Fecha de creación</div>
                    <div class="text-white">{{ formatDate(cliente.created_at) }}</div>
                  </div>
                  <div>
                    <div class="text-slate-400 text-sm mb-1">Última actualización</div>
                    <div class="text-white">{{ formatDate(cliente.updated_at) }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </AppContent>
  </AppShell>
</template>

<script setup>
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppContent from '@/components/AppContent.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ cliente: { type: Object, required: true } })

const getInitials = (nombre, apellido) => {
  const n = (nombre || '').charAt(0)
  const a = (apellido || '').charAt(0)
  return `${n}${a}`.toUpperCase()
}

const formatDate = (dateString) => {
  if (!dateString) return 'No disponible'
  try {
    return new Date(dateString).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })
  } catch (e) {
    return 'Fecha inválida'
  }
}
</script>
