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
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg ring-2 ring-white/10 group-hover:ring-amber-500/30 transition-all duration-300">
        <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
        <AvatarFallback class="rounded-lg bg-gradient-to-r from-amber-400 to-pink-500 text-white font-semibold shadow-lg">
            {{ getInitials(user.name) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium text-white group-hover:text-amber-100 transition-colors duration-300">{{ user.name }}</span>
        <span v-if="showEmail" class="truncate text-xs text-slate-400 group-hover:text-slate-300 transition-colors duration-300">{{ user.email }}</span>
    </div>
</template>
