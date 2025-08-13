<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import UserMenuContent from './UserMenuContent.vue';

const page = usePage();
const user = page.props.auth.user as User;
const { isMobile, state } = useSidebar();
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton 
                        size="lg" 
                        class="group relative overflow-hidden rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-purple-500/20 data-[state=open]:bg-gradient-to-r data-[state=open]:from-purple-500/20 data-[state=open]:via-pink-500/20 data-[state=open]:to-amber-500/20 data-[state=open]:border data-[state=open]:border-purple-500/30"
                    >
                        <!-- Efecto de cover en hover -->
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-amber-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl"></div>
                        
                        <!-- Contenido del usuario -->
                        <div class="relative z-10 flex items-center w-full">
                            <UserInfo :user="user" />
                            <ChevronsUpDown class="ml-auto size-4 text-slate-400 group-hover:text-purple-400 transition-colors duration-300" />
                        </div>
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-(--reka-dropdown-menu-trigger-width) min-w-56 rounded-lg bg-gradient-to-br from-slate-800/95 to-slate-900/95 backdrop-blur-lg border border-white/10 shadow-2xl"
                    :side="isMobile ? 'bottom' : state === 'collapsed' ? 'left' : 'bottom'"
                    align="end"
                    :side-offset="4"
                >
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
