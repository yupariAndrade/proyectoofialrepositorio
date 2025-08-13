<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Camera } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header con logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 mb-4 shadow-2xl">
                    <Camera class="w-8 h-8 text-white" />
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Foto Estudio EU</h1>
                <p class="text-slate-400">Inicia sesión en tu cuenta</p>
            </div>

            <!-- Card del formulario -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 shadow-2xl">
                <Head title="Iniciar Sesión" />

                <div v-if="status" class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg text-green-300 text-sm">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Label for="email" class="text-white font-medium">Correo electrónico</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="tu@email.com"
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <Label for="password" class="text-white font-medium">Contraseña</Label>
                            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm text-fuchsia-400 hover:text-fuchsia-300" :tabindex="5">
                                ¿Olvidaste tu contraseña?
                            </TextLink>
                        </div>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            v-model="form.password"
                            placeholder="Tu contraseña"
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between">
                        <Label for="remember" class="flex items-center space-x-3 text-white">
                            <Checkbox id="remember" v-model="form.remember" :tabindex="3" class="border-white/20" />
                            <span>Recordarme</span>
                        </Label>
                    </div>

                    <Button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 hover:from-fuchsia-700 hover:via-pink-700 hover:to-amber-600 text-white font-semibold py-3 rounded-xl shadow-lg" 
                        :tabindex="4" 
                        :disabled="form.processing"
                    >
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                        Iniciar Sesión
                    </Button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        ¿No tienes una cuenta?
                        <TextLink :href="route('register')" class="text-fuchsia-400 hover:text-fuchsia-300 font-medium" :tabindex="5">
                            Regístrate aquí
                        </TextLink>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
