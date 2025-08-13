<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';

interface Props {
    items: NavItem[];
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <SidebarGroup :class="`group-data-[collapsible=icon]:p-0 ${$props.class || ''}`">
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                    <SidebarMenuButton 
                        class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20" 
                        as-child
                    >
                        <a 
                            :href="item.href" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="flex items-center gap-3 px-3 py-3 w-full relative z-10 transition-all duration-300 text-slate-300 hover:text-white"
                        >
                            <!-- Efecto de cover en hover -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                            
                            <!-- Icono con efectos -->
                            <div class="relative z-10">
                                <component 
                                    :is="item.icon" 
                                    class="w-5 h-5 transition-all duration-300 group-hover:scale-110 text-slate-400 group-hover:text-blue-400"
                                />
                            </div>
                            
                            <!-- Texto con efectos -->
                            <span class="relative z-10 font-medium transition-all duration-300 group-hover:font-semibold">
                                {{ item.title }}
                            </span>
                            
                            <!-- Indicador de enlace externo -->
                            <div class="relative z-10 ml-auto">
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-blue-400 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </div>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
