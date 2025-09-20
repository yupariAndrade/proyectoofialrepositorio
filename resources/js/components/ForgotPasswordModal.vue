<template>
  <!-- Modal de Recuperación de Contraseña -->
  <Teleport to="body">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
    <!-- Fondo con partículas oscuras -->
    <div class="absolute inset-0 bg-gradient-to-br from-black via-zinc-900 to-neutral-950">
      <div class="absolute top-12 left-1/3 w-1 h-1 bg-red-700/40 rounded-full animate-bounce"></div>
      <div class="absolute bottom-24 right-1/4 w-1 h-1 bg-red-500/40 rounded-full animate-bounce delay-1000"></div>
      <div class="absolute top-1/2 left-1/2 w-0.5 h-0.5 bg-white/20 rounded-full animate-bounce delay-2000"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-sm mx-4">
      <div class="relative">
        <!-- Botón cerrar -->
        <button 
          @click="closeModal" 
          class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors duration-200 z-20"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>

        <!-- Encabezado -->
        <div class="text-center mb-6 relative">
          <h1 class="text-2xl font-bold mb-2">
            <span class="bg-gradient-to-r from-red-400 via-red-200 to-red-400 bg-clip-text text-transparent drop-shadow-lg">
              RECUPERAR CONTRASEÑA
            </span>
          </h1>
          <p class="text-gray-200 text-sm font-medium animate-fade-in-up">
            Ingresa tu nueva contraseña para continuar
          </p>
          <div class="w-16 h-0.5 bg-gradient-to-r from-red-600 to-red-400 mx-auto mt-3 rounded-full shadow-lg"></div>
        </div>

        <!-- Formulario -->
        <div class="relative">
          <div class="bg-white/5 backdrop-blur-xl rounded-2xl p-6 border border-white/10 shadow-xl">
            <form @submit.prevent="updatePassword" class="space-y-4">
              <!-- Email (solo lectura) -->
              <div class="group">
                <label for="email" class="block text-xs font-semibold text-white/90 mb-1.5">
                  Usuario
                </label>
                <div class="relative">
                  <input 
                    id="email"
                    v-model="form.email" 
                    type="email" 
                    readonly
                    class="w-full px-3 py-2.5 bg-white/5 border border-red-700/40 rounded-xl text-gray-300 placeholder-white/40 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-400 transition-all duration-300 text-sm"
                    placeholder="Email del usuario"
                  />
                </div>
              </div>

              <!-- Nueva Contraseña -->
              <div class="group">
                <label for="password" class="block text-xs font-semibold text-white/90 mb-1.5">
                  Nueva Contraseña
                </label>
                <div class="relative">
                  <input 
                    id="password"
                    v-model="form.password" 
                    :type="showPassword ? 'text' : 'password'" 
                    required
                    minlength="6"
                    class="w-full px-3 py-2.5 bg-white/5 border border-red-700/40 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-400 transition-all duration-300 text-sm pr-12"
                    placeholder="Ingresa tu nueva contraseña"
                  />
                  <button 
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors duration-200"
                  >
                    <svg v-if="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </div>
                <div class="text-xs text-gray-400 mt-1">Mínimo 6 caracteres</div>
                <p v-if="$page.props.errors?.password || passwordError" class="mt-1 text-red-400 text-xs">
                  {{ $page.props.errors?.password || passwordError }}
                </p>
              </div>

              <!-- Confirmar Contraseña -->
              <div class="group">
                <label for="password_confirmation" class="block text-xs font-semibold text-white/90 mb-1.5">
                  Confirmar Contraseña
                </label>
                <div class="relative">
                  <input 
                    id="password_confirmation"
                    v-model="form.password_confirmation" 
                    :type="showConfirmPassword ? 'text' : 'password'" 
                    required
                    minlength="6"
                    class="w-full px-3 py-2.5 bg-white/5 border border-red-700/40 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-400 transition-all duration-300 text-sm pr-12"
                    placeholder="Repite tu nueva contraseña"
                  />
                  <button 
                    type="button"
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors duration-200"
                  >
                    <svg v-if="showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                  </button>
                </div>
                <p v-if="confirmPasswordError" class="mt-1 text-red-400 text-xs">
                  {{ confirmPasswordError }}
                </p>
              </div>

              <!-- Botones -->
              <div class="flex space-x-4">
                <button 
                  type="button"
                  @click="closeModal"
                  class="flex-1 px-4 py-2.5 bg-gray-700/50 hover:bg-gray-700 text-white rounded-xl font-semibold transition-all duration-200 text-sm"
                >
                  Cancelar
                </button>
                <button 
                  type="submit"
                  :disabled="isLoading"
                  class="relative flex-1 py-2.5 px-4 bg-gradient-to-r from-red-800 via-red-600 to-red-500 text-white font-semibold rounded-xl shadow-lg hover:scale-105 transition-all duration-300 text-sm disabled:opacity-50"
                >
                  <svg v-if="isLoading" class="h-4 w-4 animate-spin mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span v-if="isLoading">Actualizando...</span>
                  <span v-else>Actualizar Contraseña</span>
                </button>
              </div>
            </form>
          </div>
          <div class="absolute -inset-2 bg-gradient-to-r from-red-800/20 via-red-600/20 to-red-400/20 rounded-2xl blur-lg -z-10"></div>
        </div>
      </div>
    </div>
  </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, Teleport } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  userEmail: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close', 'success'])

const $page = usePage()

const form = ref({
  email: '',
  password: '',
  password_confirmation: ''
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const isLoading = ref(false)

const passwordError = ref('')
const confirmPasswordError = ref('')

// Computed para validar contraseñas
const passwordsMatch = computed(() => {
  return form.value.password === form.value.password_confirmation
})

// Watchers para validación en tiempo real
watch(() => form.value.password, () => {
  if (form.value.password && form.value.password.length < 6) {
    passwordError.value = 'La contraseña debe tener al menos 6 caracteres'
  } else {
    passwordError.value = ''
  }
})

watch(() => form.value.password_confirmation, () => {
  if (form.value.password_confirmation && !passwordsMatch.value) {
    confirmPasswordError.value = 'Las contraseñas no coinciden'
  } else {
    confirmPasswordError.value = ''
  }
})

// Inicializar email cuando se abre el modal
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    form.value.email = props.userEmail
    form.value.password = ''
    form.value.password_confirmation = ''
    passwordError.value = ''
    confirmPasswordError.value = ''
  }
})

const closeModal = () => {
  emit('close')
}

const updatePassword = () => {
  // Validaciones
  if (form.value.password.length < 6) {
    passwordError.value = 'La contraseña debe tener al menos 6 caracteres'
    return
  }

  if (!passwordsMatch.value) {
    confirmPasswordError.value = 'Las contraseñas no coinciden'
    return
  }

  isLoading.value = true

  // Enviar solicitud
  router.post('/forgot-password', {
    email: form.value.email,
    password: form.value.password,
    password_confirmation: form.value.password_confirmation
  }, {
    onSuccess: () => {
      emit('success', 'Contraseña actualizada exitosamente')
      closeModal()
    },
    onError: (errors) => {
      console.error('Error al actualizar contraseña:', errors)
    },
    onFinish: () => {
      isLoading.value = false
    }
  })
}
</script>

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