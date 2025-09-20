<script setup lang="ts">
// @ts-nocheck
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import ForgotPasswordModal from '@/components/ForgotPasswordModal.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { LoaderCircle, Camera, KeyRound } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showForgotPasswordModal = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const openForgotPasswordModal = () => {
    showForgotPasswordModal.value = true;
};

const closeForgotPasswordModal = () => {
    showForgotPasswordModal.value = false;
};

const onPasswordResetSuccess = (message: string) => {
    alert(message);
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-black via-zinc-900 to-neutral-950 relative overflow-hidden">
        <!-- Fondo con partículas oscuras -->
        <div class="absolute inset-0">
            <div class="absolute top-12 left-1/3 w-1 h-1 bg-red-700/40 rounded-full animate-bounce"></div>
            <div class="absolute bottom-24 right-1/4 w-1 h-1 bg-red-500/40 rounded-full animate-bounce delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 w-0.5 h-0.5 bg-white/20 rounded-full animate-bounce delay-2000"></div>
        </div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-sm">
                <div class="relative">
                    <!-- Encabezado -->
                    <div class="text-center mb-6 relative">
                        <!-- Título -->
                        <h1 class="text-2xl font-bold mb-2">
                            <span class="bg-gradient-to-r from-red-400 via-red-200 to-red-400 bg-clip-text text-transparent drop-shadow-lg">
                                FOTO STUDIO EU
                            </span>
                        </h1>
                        <p class="text-gray-200 text-sm font-medium animate-fade-in-up">
                            Inicia sesión con tu cuenta
                        </p>
                        <div class="w-16 h-0.5 bg-gradient-to-r from-red-600 to-red-400 mx-auto mt-3 rounded-full shadow-lg"></div>
                    </div>

                    <!-- Formulario -->
                    <div class="relative">
                        <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/10 shadow-xl">
                            <Head title="Iniciar Sesión" />

                            <div v-if="status" class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-300 text-sm">
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="space-y-4">
                                <!-- Email -->
                                <div class="group">
                                    <label for="email" class="block text-xs font-semibold text-white/90 mb-1.5">
                                        Correo Electrónico
                                    </label>
                                    <div class="relative">
                                        <!-- @ts-ignore -->
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="w-full px-3 py-2.5 bg-white/5 border border-red-700/40 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-400 transition-all duration-300 text-sm"
                                            placeholder="tu@email.com"
                                            required
                                            autofocus
                                            :tabindex="1"
                                            autocomplete="email"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" class="mt-1 text-red-400 text-xs" />
                                </div>

                                <!-- Password -->
                                <div class="group">
                                    <div class="flex items-center justify-between">
                                        <label for="password" class="block text-xs font-semibold text-white/90 mb-1.5">
                                            Contraseña
                                        </label>
                                    </div>
                                    <div class="relative">
                                        <!-- @ts-ignore -->
                                        <input
                                            id="password"
                                            v-model="form.password"
                                            type="password"
                                            class="w-full px-3 py-2.5 bg-white/5 border border-red-700/40 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-400 transition-all duration-300 text-sm"
                                            placeholder="••••••••"
                                            required
                                            :tabindex="2"
                                            autocomplete="current-password"
                                        />
                                    </div>
                                    <InputError :message="form.errors.password" class="mt-1 text-red-400 text-xs" />
                                </div>

                                <!-- Recordarme -->
                                <div class="flex items-center">
                                    <!-- @ts-ignore -->
                                    <input
                                        id="remember"
                                        v-model="form.remember"
                                        type="checkbox"
                                        class="w-4 h-4 text-red-500 bg-white/10 border-2 border-white/20 rounded focus:ring-2 focus:ring-red-500"
                                        :tabindex="3"
                                    />
                                    <label for="remember" class="ml-2 text-xs text-gray-200">
                                        Recordarme
                                    </label>
                                </div>

                                <!-- Botón -->
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="relative w-full py-2.5 px-4 bg-gradient-to-r from-red-800 via-red-600 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-all duration-300 text-sm disabled:opacity-50"
                                    :tabindex="4"
                                >
                                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2 inline" />
                                    <span v-if="form.processing">Iniciando sesión...</span>
                                    <span v-else>Iniciar Sesión</span>
                                </button>
                            </form>

                            <!-- Registro -->
                            <div class="mt-6 text-center">
                                <p class="text-xs text-gray-300">
            
                                     <button 
                                            type="button"
                                            @click.prevent="openForgotPasswordModal"
                                            class="text-xs text-gray-200 hover:text-red-300 transition-colors duration-200 flex items-center gap-1"
                                            :tabindex="5"
                                        >
                                            <KeyRound class="w-3 h-3" />
                                            ¿Olvidaste tu contraseña?
                                        </button>
                                   <!--<TextLink
                                        :href="route('register')"
                                        class="font-semibold text-red-400 hover:text-red-300 transition-colors duration-200 hover:underline"
                                        :tabindex="5"
                                    >
                                        Regístrate aquí
                                    </TextLink>-->
                                </p>
                            </div>
                        </div>
                        <div class="absolute -inset-2 bg-gradient-to-r from-red-800/20 via-red-600/20 to-red-400/20 rounded-2xl blur-lg -z-10"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Recuperación de Contraseña -->
        <!-- @ts-ignore -->
        <ForgotPasswordModal 
            :is-open="showForgotPasswordModal"
            :user-email="form.email"
            @close="closeForgotPasswordModal"
            @success="onPasswordResetSuccess"
        />
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate-fade-in-up {
    animation: fade-in-up 0.5s ease-out;
}
</style>