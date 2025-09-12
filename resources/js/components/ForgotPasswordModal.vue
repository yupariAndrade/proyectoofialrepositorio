<template>
  <!-- Modal de Recuperación de Contraseña -->
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="relative bg-gradient-to-br from-gray-800/95 to-gray-900/95 rounded-2xl shadow-2xl border border-red-500/20 p-8 w-full max-w-md mx-4">
      <!-- Botón cerrar -->
      <button 
        @click="closeModal" 
        class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors duration-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Header -->
      <div class="text-center mb-6">
        <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
          </svg>
        </div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-red-400 via-red-500 to-red-600 bg-clip-text text-transparent mb-2">
          Recuperar Contraseña
        </h2>
        <p class="text-gray-300 text-sm">Ingresa tu nueva contraseña para continuar</p>
      </div>

      <!-- Formulario -->
      <form @submit.prevent="updatePassword" class="space-y-6">
        <!-- Email (solo lectura) -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            <svg class="w-4 h-4 inline mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Usuario
          </label>
          <input 
            v-model="form.email" 
            type="email" 
            readonly
            class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-gray-300 cursor-not-allowed"
            placeholder="Email del usuario"
          />
        </div>

        <!-- Nueva Contraseña -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            <svg class="w-4 h-4 inline mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Nueva Contraseña
          </label>
          <div class="relative">
            <input 
              v-model="form.password" 
              :type="showPassword ? 'text' : 'password'" 
              required
              minlength="6"
              :class="[
                'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm pr-12',
                $page.props.errors?.password || passwordError
                  ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                  : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
              ]"
              placeholder="Ingresa tu nueva contraseña"
            />
            <button 
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors duration-200"
            >
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <div class="text-xs text-gray-400 mt-1">Mínimo 6 caracteres</div>
          <p v-if="$page.props.errors?.password || passwordError" class="mt-2 text-sm text-red-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $page.props.errors?.password || passwordError }}
          </p>
        </div>

        <!-- Confirmar Contraseña -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            <svg class="w-4 h-4 inline mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Confirmar Contraseña
          </label>
          <div class="relative">
            <input 
              v-model="form.password_confirmation" 
              :type="showConfirmPassword ? 'text' : 'password'" 
              required
              minlength="6"
              :class="[
                'w-full px-4 py-3 bg-black/60 border-2 rounded-xl text-white placeholder-gray-400 focus:ring-4 transition-all duration-300 shadow-lg backdrop-blur-sm pr-12',
                confirmPasswordError
                  ? 'border-red-500 focus:ring-red-500/30 focus:border-red-400' 
                  : 'border-gray-600 focus:ring-red-500/30 focus:border-red-400'
              ]"
              placeholder="Repite tu nueva contraseña"
            />
            <button 
              type="button"
              @click="showConfirmPassword = !showConfirmPassword"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors duration-200"
            >
              <svg v-if="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <p v-if="confirmPasswordError" class="mt-2 text-sm text-red-400 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ confirmPasswordError }}
          </p>
        </div>

        <!-- Botones -->
        <div class="flex space-x-4">
          <button 
            type="button"
            @click="closeModal"
            class="flex-1 px-4 py-3 bg-gray-700/50 hover:bg-gray-700 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105"
          >
            Cancelar
          </button>
          <button 
            type="submit"
            :disabled="isLoading"
            class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 text-white rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 shadow-lg shadow-red-500/25 flex items-center justify-center"
          >
            <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ isLoading ? 'Actualizando...' : 'Actualizar Contraseña' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
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
