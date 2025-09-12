<template>
  <div class="relative w-full overflow-hidden rounded-2xl shadow-xl bg-gray-900">
    <!-- Slides -->
    <div class="relative h-64 md:h-80 lg:h-96">
      <transition-group name="fade" tag="div">
        <div
          v-for="(item, idx) in visibleSlides"
          :key="item.id + '-' + (playKey)"
          class="absolute inset-0"
        >
          <div class="w-full h-full">
            <img
              v-if="item.imagenReferencia"
              :src="getImageUrl(item.imagenReferencia)"
              :alt="item.nombreServicio"
              class="w-full h-full object-cover select-none"
              :class="[ 'transition-transform duration-[1800ms] ease-in-out', isZoomed ? 'scale-110' : 'scale-100' ]"
              draggable="false"
            />
            <!-- Imagen de placeholder cuando no hay imagen -->
            <div
              v-else
              class="w-full h-full bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 flex items-center justify-center"
              :class="[ 'transition-transform duration-[1800ms] ease-in-out', isZoomed ? 'scale-110' : 'scale-100' ]"
            >
              <div class="text-center text-white">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg font-semibold">{{ item.nombreServicio }}</p>
              </div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white/90 backdrop-blur-[2px]">
              <div class="flex items-end justify-between">
                <div>
                  <h3 class="text-2xl md:text-4xl font-extrabold tracking-wide bg-gradient-to-r from-amber-300 via-pink-400 to-purple-400 bg-clip-text text-transparent drop-shadow-[0_2px_6px_rgba(0,0,0,0.45)]">
                    {{ item.nombreServicio }}
                  </h3>
                  <div class="h-1 w-24 mt-2 rounded-full bg-gradient-to-r from-pink-500 to-purple-600"></div>
                  <p class="text-xs md:text-sm text-white/80 mt-2">Imagen de referencia del servicio</p>
                </div>
                <div class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 font-semibold shadow-lg">
                  Bs. {{ Number(item.precioReferencial).toFixed(2) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition-group>
    </div>

    <!-- Controles -->
    <button @click="prev" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-2 rounded-full backdrop-blur shadow-lg">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button @click="next" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-2 rounded-full backdrop-blur shadow-lg">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    <!-- Indicadores -->
    <div class="absolute bottom-3 left-0 right-0 flex items-center justify-center gap-2">
      <button
        v-for="(s, i) in servicios"
        :key="s.id"
        @click="go(i)"
        class="w-2.5 h-2.5 rounded-full transition-all"
        :class="current === i ? 'bg-white w-6' : 'bg-white/50 hover:bg-white/70'"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'

type Servicio = {
  id: number
  nombreServicio: string
  precioReferencial: number | string
  imagenReferencia: string | null
}

const props = defineProps<{ servicios: Servicio[] }>()

const current = ref(0)
const timer = ref<number | null>(null)
const isZoomed = ref(false)
const playKey = ref(0) // fuerza el re-render de transición cuando cambia slide

const visibleSlides = computed(() => {
  if (!props.servicios || props.servicios.length === 0) return []
  const item = props.servicios[current.value]
  return item ? [item] : []
})

const start = () => {
  stop()
  timer.value = window.setInterval(() => {
    toggleZoom()
    next()
  }, 4500)
}

const stop = () => {
  if (timer.value) {
    clearInterval(timer.value)
    timer.value = null
  }
}

const toggleZoom = () => {
  isZoomed.value = true
  window.setTimeout(() => (isZoomed.value = false), 1800)
}

const next = () => {
  if (!props.servicios?.length) return
  current.value = (current.value + 1) % props.servicios.length
  playKey.value++
}

const prev = () => {
  if (!props.servicios?.length) return
  current.value = (current.value - 1 + props.servicios.length) % props.servicios.length
  playKey.value++
}

const go = (index: number) => {
  if (!props.servicios?.length) return
  current.value = index % props.servicios.length
  playKey.value++
}

const getImageUrl = (path: string | null) => {
  if (!path) return ''
  const normalized = path.replace(/\\\\/g, '/').replace(/\\/g, '/')
  return `/storage/${normalized}`
}

onMounted(start)
onBeforeUnmount(stop)

watch(() => props.servicios?.length, () => {
  current.value = 0
  playKey.value++
  start()
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 600ms ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>


