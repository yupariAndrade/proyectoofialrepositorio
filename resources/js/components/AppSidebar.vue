

<script setup>
import NavFooter from '@/components/NavFooter.vue'
import NavMain from '@/components/NavMain.vue'
import NavUser from '@/components/NavUser.vue'
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar'
import { Link } from '@inertiajs/vue3'
import AppLogo from './AppLogo.vue'
import { BookOpen, Folder, LayoutGrid, Users, Settings, ClipboardList, UserCheck, Briefcase, Activity, CreditCard, CheckCircle, ClipboardCheck, ClipboardSignature, Notebook } from 'lucide-vue-next'

// Menú principal
const mainNavItems = [
  { title: 'Dashboard', href: '/dashboard', icon: LayoutGrid },
  { title: 'Registrar Trabajos', href: '/registrar-trabajos', icon: ClipboardSignature },
  { title: 'Clientes', href: '/clientes', icon: UserCheck },
  { title: 'Servicios', href: '/servicios', icon: ClipboardList },
  /*{ title: 'Trabajos', href: '/trabajos', icon: Briefcase },*/
  /*{ title: 'Detalle de Trabajo', href: '/detalle-trabajo', icon: ClipboardCheck },*/
  /*{ title: 'Asignaciones', href: '/asignaciones-trabajos', icon: ClipboardSignature },*/
  /*{ title: 'Bitácora', href: '/bitacora-trabajos', icon: Notebook },*/
  /*{ title: 'Pagos', href: '/pagos', icon: CreditCard },*/
  { title: 'Estados de Pago', href: '/estado-pagos', icon: CheckCircle },
  { title: 'Estados Trabajo', href: '/estados-trabajo', icon: Activity },
  { title: 'Usuarios', href: '/usuarios', icon: Users },
  { title: 'Roles', href: '/roles', icon: Settings }
]

// Footer
const footerNavItems = [
  { title: 'Github Repo', href: 'https://github.com/laravel/vue-starter-kit', icon: Folder },
  { title: 'Documentation', href: 'https://laravel.com/docs/starter-kits#vue', icon: BookOpen }
]
</script>

<template>
  <Sidebar collapsible="icon" variant="inset" class="bg-gradient-to-b from-slate-950 via-slate-900 to-black text-slate-100 border-r border-white/10">
    <SidebarHeader class="p-4">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-fuchsia-500/20">
            <Link :href="route('dashboard')">
              <!-- Efecto de cover en hover -->
              <div class="absolute inset-0 bg-gradient-to-r from-fuchsia-500/10 via-pink-500/10 to-amber-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
              <!-- Contenido del logo -->
              <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 grid place-items-center text-white text-lg shadow-lg group-hover:shadow-fuchsia-500/50 transition-all duration-300">📸</div>
                <div>
                  <p class="text-sm leading-tight text-white/80 group-hover:text-white transition-colors duration-300">Foto Estudio</p>
                  <p class="text-base font-extrabold bg-gradient-to-r from-amber-300 via-pink-400 to-purple-400 bg-clip-text text-transparent group-hover:from-amber-200 group-hover:via-pink-300 group-hover:to-purple-300 transition-all duration-300">EU</p>
                </div>
              </div>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent class="px-2 pb-2">
      <div class="h-px w-full bg-gradient-to-r from-fuchsia-500/20 via-white/20 to-amber-400/20 mb-4 rounded-full"></div>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter class="px-2">
      <NavFooter :items="footerNavItems" />
      <div class="h-px w-full bg-gradient-to-r from-slate-600/50 via-white/20 to-slate-600/50 my-3 rounded-full"></div>
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>

