<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

// Función para verificar si la ruta está activa
const isActive = (href: string) => {
    const currentPath = page.url;
    return currentPath === href || currentPath.startsWith(href + '/');
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel class="text-slate-300 font-semibold text-xs uppercase tracking-wider mb-3">PLATAFORMA</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton 
                    as-child 
                    :is-active="isActive(item.href)" 
                    :tooltip="item.title"
                    class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-amber-500/20"
                >
                    <Link 
                        :href="item.href"
                        class="flex items-center gap-3 px-3 py-3 w-full relative z-10 transition-all duration-300"
                        :class="[
                            isActive(item.href) 
                                ? 'text-white bg-gradient-to-r from-amber-500/20 via-pink-500/20 to-purple-500/20 border border-amber-500/30' 
                                : 'text-slate-300 hover:text-white'
                        ]"
                    >
                        <!-- Efecto de cover en hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-pink-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        
                        <!-- Icono con efectos -->
                        <div class="relative z-10">
                            <component 
                                :is="item.icon" 
                                class="w-5 h-5 transition-all duration-300 group-hover:scale-110"
                                :class="[
                                    isActive(item.href) 
                                        ? 'text-amber-400 group-hover:text-amber-300' 
                                        : 'text-slate-400 group-hover:text-amber-400'
                                ]"
                            />
                        </div>
                        
                        <!-- Texto con efectos -->
                        <span class="relative z-10 font-medium transition-all duration-300 group-hover:font-semibold">
                            {{ item.title }}
                        </span>
                        
                        <!-- Indicador de estado activo -->
                        <div 
                            v-if="isActive(item.href)"
                            class="absolute right-2 w-2 h-2 bg-gradient-to-r from-amber-400 to-pink-500 rounded-full animate-pulse"
                        ></div>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
