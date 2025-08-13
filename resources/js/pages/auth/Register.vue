<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Camera, UserPlus } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-black flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header con logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 mb-4 shadow-2xl">
                    <UserPlus class="w-8 h-8 text-white" />
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Foto Estudio EU</h1>
                <p class="text-slate-400">Crea tu cuenta nueva</p>
            </div>

            <!-- Card del formulario -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 shadow-2xl">
                <Head title="Registrarse" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <Label for="name" class="text-white font-medium">Nombre completo</Label>
                        <Input 
                            id="name" 
                            type="text" 
                            required 
                            autofocus 
                            :tabindex="1" 
                            autocomplete="name" 
                            v-model="form.name" 
                            placeholder="Tu nombre completo" 
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="email" class="text-white font-medium">Correo electrónico</Label>
                        <Input 
                            id="email" 
                            type="email" 
                            required 
                            :tabindex="2" 
                            autocomplete="email" 
                            v-model="form.email" 
                            placeholder="tu@email.com" 
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div>
                        <Label for="password" class="text-white font-medium">Contraseña</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="Tu contraseña"
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div>
                        <Label for="password_confirmation" class="text-white font-medium">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="Confirma tu contraseña"
                            class="mt-2 bg-white/10 border-white/20 text-white placeholder:text-slate-400 focus:border-fuchsia-500 focus:ring-fuchsia-500"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <Button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-fuchsia-600 via-pink-600 to-amber-500 hover:from-fuchsia-700 hover:via-pink-700 hover:to-amber-600 text-white font-semibold py-3 rounded-xl shadow-lg" 
                        tabindex="5" 
                        :disabled="form.processing"
                    >
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin mr-2" />
                        Crear cuenta
                    </Button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-400 text-sm">
                        ¿Ya tienes una cuenta?
                        <TextLink :href="route('login')" class="text-fuchsia-400 hover:text-fuchsia-300 font-medium" :tabindex="6">
                            Inicia sesión aquí
                        </TextLink>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
