<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

interface Props {
    user: User;
    showEmail?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.user.avatar && props.user.avatar !== '');

// Debug para ver qué datos recibe el componente
console.log('UserInfo - Usuario completo recibido:', props.user);
console.log('UserInfo - Todas las propiedades del usuario:', Object.keys(props.user));
console.log('UserInfo - Rol del usuario:', props.user.rol);
console.log('UserInfo - Nombre completo:', props.user.fullName);
console.log('UserInfo - Nombre individual:', props.user.nombre);
console.log('UserInfo - Apellido paterno:', props.user.apellidoPaterno);
console.log('UserInfo - Apellido materno:', props.user.apellidoMaterno);
console.log('UserInfo - Email del usuario:', props.user.email);
console.log('UserInfo - ID del usuario:', props.user.id);
</script>

<template>
    <!-- Avatar personalizado con colores sólidos -->
    <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg ring-2 ring-white/20 group-hover:ring-blue-500/50 transition-all duration-300 overflow-hidden">
        <span v-if="!showAvatar">{{ getInitials(user.fullName || user.nombre) }}</span>
        <img v-else :src="user.avatar!" :alt="user.name" class="w-full h-full object-cover" />
    </div>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold text-white group-hover:text-blue-200 transition-colors duration-300">
            {{ user.fullName || user.nombre || 'Usuario' }}
        </span>
        <span class="truncate text-xs font-medium text-blue-300 group-hover:text-blue-200 transition-colors duration-300">
            {{ user.rol?.nombre ? user.rol.nombre.charAt(0).toUpperCase() + user.rol.nombre.slice(1) : 'Usuario' }}
        </span>
    </div>
</template>
