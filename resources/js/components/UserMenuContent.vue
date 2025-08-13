<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-3 py-3 text-left text-sm bg-gradient-to-r from-slate-800/50 to-slate-700/50 rounded-lg m-2">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator class="bg-slate-600/50" />
    <DropdownMenuGroup>
        <DropdownMenuItem 
            :as-child="true"
            class="group relative overflow-hidden rounded-lg mx-2 my-1 transition-all duration-300 hover:scale-105"
        >
            <Link 
                class="flex items-center w-full px-3 py-2 text-slate-300 hover:text-white transition-colors duration-300" 
                :href="route('profile.edit')" 
                prefetch 
                as="button"
            >
                <!-- Efecto de cover en hover -->
                <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 via-pink-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>
                
                <!-- Contenido -->
                <div class="relative z-10 flex items-center">
                    <Settings class="mr-3 h-4 w-4 text-slate-400 group-hover:text-amber-400 transition-colors duration-300" />
                    <span class="font-medium">Configuración</span>
                </div>
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator class="bg-slate-600/50" />
    <DropdownMenuItem 
        :as-child="true"
        class="group relative overflow-hidden rounded-lg mx-2 my-1 transition-all duration-300 hover:scale-105"
    >
        <Link 
            class="flex items-center w-full px-3 py-2 text-slate-300 hover:text-white transition-colors duration-300" 
            method="post" 
            :href="route('logout')" 
            @click="handleLogout" 
            as="button"
        >
            <!-- Efecto de cover en hover -->
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 via-pink-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg"></div>
            
            <!-- Contenido -->
            <div class="relative z-10 flex items-center">
                <LogOut class="mr-3 h-4 w-4 text-slate-400 group-hover:text-red-400 transition-colors duration-300" />
                <span class="font-medium">Cerrar Sesión</span>
            </div>
        </Link>
    </DropdownMenuItem>
</template>
