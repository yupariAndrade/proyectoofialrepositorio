<script setup>
import NavFooter from '@/components/NavFooter.vue'
import NavMain from '@/components/NavMain.vue'
import NavUser from '@/components/NavUser.vue'
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar'
import { Link, usePage } from '@inertiajs/vue3'
import { LayoutGrid, BookOpen, ClipboardSignature, UserCheck, ClipboardList, Activity, Users, Settings, CheckCircle } from 'lucide-vue-next'
import { computed } from 'vue'

// Obtener datos del usuario autenticado
const page = usePage()
const user = computed(() => page.props.auth.user)

// Menú principal según rol
const mainNavItems = computed(() => {
  if (!user.value) return []

  if (user.value.isAdmin) {
    return [
      {
        section: 'Plataforma',
        items: [
          { title: 'Dashboard Admin', href: '/dashboard-admin', icon: LayoutGrid },
          { title: 'Dashboard Público', href: '/dashboard', icon: BookOpen },
        ]
      },
      {
        section: 'Gestión de Trabajos',
        items: [
          { title: 'Registrar Trabajos', href: '/registrar-trabajos', icon: ClipboardSignature },
          { title: 'Clientes', href: '/clientes', icon: UserCheck },
          { title: 'Servicios', href: '/servicios', icon: ClipboardList },
          { title: 'Estados de Pago', href: '/estado-pagos', icon: CheckCircle },
          { title: 'Estados Trabajo', href: '/estados-trabajo', icon: Activity },
        ]
      },
      {
        section: 'Usuarios',
        items: [
          { title: 'Usuarios', href: '/usuarios', icon: Users },
          { title: 'Roles', href: '/roles', icon: Settings },
        ]
      },
      {
        section: 'Reportes',
        items: [
          { title: 'Reportes', href: '/reportes', icon: Activity },
        ]
      }
    ]
  }

  if (user.value.isEmpleado || user.value.isEncargado) {
    return [
      {
        section: 'Plataforma',
        items: [
          { title: 'Dashboard Admin', href: '/dashboard-admin', icon: LayoutGrid },
          { title: 'Dashboard Público', href: '/dashboard', icon: BookOpen },
        ]
      },
      {
        section: 'Gestión de Trabajos',
        items: [
          { title: 'Registrar Trabajos', href: '/registrar-trabajos', icon: ClipboardSignature },
          { title: 'Clientes', href: '/clientes', icon: UserCheck },
          { title: 'Servicios', href: '/servicios', icon: ClipboardList },
        ]
      },
      {
        section: 'Reportes',
        items: [
          { title: 'Reportes', href: '/reportes', icon: Activity },
        ]
      }
    ]
  }

  if (user.value.isGerente) {
    return [
      {
        section: 'Plataforma',
        items: [
          { title: 'Dashboard Admin', href: '/dashboard-admin', icon: LayoutGrid },
          { title: 'Dashboard Público', href: '/dashboard', icon: BookOpen },
        ]
      },
      {
        section: 'Gestión de Trabajos',
        items: [
          { title: 'Registrar Trabajos', href: '/registrar-trabajos', icon: ClipboardSignature },
          { title: 'Clientes', href: '/clientes', icon: UserCheck },
          { title: 'Servicios', href: '/servicios', icon: ClipboardList },
        ]
      },
      {
        section: 'Reportes',
        items: [
          { title: 'Reportes', href: '/reportes', icon: Activity },
        ]
      }
    ]
  }

  return [
    {
      section: 'Plataforma',
      items: [
        { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid }
      ]
    }
  ]
})
</script>
<template>
  <Sidebar 
    collapsible="icon" 
    variant="inset" 
    class="app-sidebar bg-gradient-to-b from-black via-zinc-950 to-neutral-900 text-white border-r border-white/10">

    <!-- Header -->
    <SidebarHeader class="p-4">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/30">
            <Link :href="route('home')">
              <div class="absolute inset-0 bg-gradient-to-r from-red-800/20 via-red-600/20 to-red-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
              <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-red-700 via-red-600 to-red-500 grid place-items-center text-white text-lg shadow-lg">
                   <div class="w-11 h-11 bg-white rounded-lg flex items-center justify-center mr-4 shadow-lg">
                  <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                </div>
                </div>
                <div>
                  <p class="text-sm leading-tight text-white/70 group-hover:text-white transition-colors">FOTO STUDIO</p>
                  <p class="text-base font-extrabold bg-gradient-to-r from-red-400 via-red-300 to-red-500 bg-clip-text text-transparent">EU</p>
                </div>
              </div>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <!-- Content -->
    <SidebarContent class="px-3 pb-4 space-y-6">
      <div v-for="section in mainNavItems" :key="section.section">
        <p class="px-2 text-xs uppercase tracking-wider text-white/50 mb-2">{{ section.section }}</p>
        <NavMain :items="section.items" class="space-y-1" />
        <div class="h-px w-full bg-gradient-to-r from-red-800/30 via-white/10 to-red-500/30 mt-3 rounded-full"></div>
      </div>
    </SidebarContent>

    <!-- Footer -->
    <SidebarFooter class="px-3">
      <NavUser />
    </SidebarFooter>
  </Sidebar>

  <slot />
</template>

