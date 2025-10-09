<template>
  <div id="universe" class="universe-container"></div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue'

let animationFrameId: number | null = null

const createUniverse = () => {
  const layerCount = 5
  const starCount = 300 // Reducido para mejor rendimiento
  const maxTime = 30
  
  const universe = document.getElementById("universe")
  if (!universe) return
  
  // Limpiar estrellas existentes
  universe.innerHTML = ''
  
  const w = window
  const d = document
  const e = d.documentElement
  const g = d.getElementsByTagName("body")[0]
  const width = w.innerWidth || e.clientWidth || g.clientWidth
  const height = w.innerHeight || e.clientHeight || g.clientHeight
  
  // Crear estrellas
  for (let i = 0; i < starCount; ++i) {
    const ypos = Math.round(Math.random() * height)
    const star = document.createElement("div")
    const speed = 1000 * (Math.random() * maxTime + 1)
    const starClass = "star" + (3 - Math.floor(speed / 1000 / 8))
    
    star.setAttribute("class", starClass)
    star.style.backgroundColor = "white"
    star.style.position = "absolute"
    star.style.pointerEvents = "none" // Evitar interferencia con interacciones
    
    universe.appendChild(star)
    
    // Animación de las estrellas
    star.animate(
      [
        {
          transform: `translate3d(${width}px, ${ypos}px, 0)`,
          opacity: 0
        },
        {
          transform: `translate3d(${width * 0.3}px, ${ypos}px, 0)`,
          opacity: 1
        },
        {
          transform: `translate3d(-${Math.random() * 256}px, ${ypos}px, 0)`,
          opacity: 0
        }
      ],
      {
        delay: Math.random() * -speed,
        duration: speed,
        iterations: Infinity,
        easing: "linear"
      }
    )
  }
}

const handleResize = () => {
  // Recrear universo en resize
  createUniverse()
}

onMounted(() => {
  // Esperar un poco para que el DOM esté listo
  setTimeout(() => {
    createUniverse()
  }, 100)
  
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
  }
})
</script>

<style scoped>
.universe-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
  background: linear-gradient(to right, #00223e, #ffa17f);
  overflow: hidden;
}

/* Estilos para las estrellas */
:global(.star0) {
  height: 1px;
  width: 1px;
  opacity: 1;
  position: absolute;
}

:global(.star1) {
  height: 2px;
  width: 2px;
  border-radius: 50%;
  opacity: 1;
  position: absolute;
}

:global(.star2) {
  height: 3px;
  width: 3px;
  border-radius: 50%;
  opacity: 1;
  position: absolute;
}

:global(.star3) {
  height: 4px;
  width: 4px;
  border-radius: 50%;
  opacity: 1;
  position: absolute;
}
</style>
