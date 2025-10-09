<template>
  <canvas id="particle-canvas" class="particle-canvas"></canvas>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue'

let animationFrameId: number | null = null
let particles: any[] = []
let canvas: HTMLCanvasElement | null = null
let ctx: CanvasRenderingContext2D | null = null

// Constants
const NUM_PARTICLES = 400 // Reducido para mejor rendimiento
const PARTICLE_SIZE = 0.5
const SPEED = 20000

// Modified version of random-normal
function normalPool(o: any) {
  var r = 0
  do {
    var a = Math.round(normal({ mean: o.mean, dev: o.dev }))
    if (a < o.pool.length && a >= 0) return o.pool[a]
    r++
  } while (r < 100)
}

function randomNormal(o: any) {
  if ((o = Object.assign({ mean: 0, dev: 1, pool: [] }, o)), Array.isArray(o.pool) && o.pool.length > 0) return normalPool(o)
  var r: number, a: number, n: number, e: number, l = o.mean, t = o.dev
  do {
    r = (a = 2 * Math.random() - 1) * a + (n = 2 * Math.random() - 1) * n
  } while (r >= 1)
  return e = a * Math.sqrt(-2 * Math.log(r) / r), t * e + l
}

function normal(o: any) {
  return randomNormal(o)
}

function rand(low: number, high: number) {
  return Math.random() * (high - low) + low
}

function createParticle() {
  const colour = {
    r: 255,
    g: randomNormal({ mean: 125, dev: 20 }),
    b: 50,
    a: rand(0, 1),
  }
  return {
    x: -2,
    y: -2,
    diameter: Math.max(0, randomNormal({ mean: PARTICLE_SIZE, dev: PARTICLE_SIZE / 2 })),
    duration: randomNormal({ mean: SPEED, dev: SPEED * 0.1 }),
    amplitude: randomNormal({ mean: 16, dev: 2 }),
    offsetY: randomNormal({ mean: 0, dev: 10 }),
    arc: Math.PI * 2,
    startTime: performance.now() - rand(0, SPEED),
    colour: `rgba(${colour.r}, ${colour.g}, ${colour.b}, ${colour.a})`,
  }
}

function moveParticle(particle: any, time: number) {
  const progress = ((time - particle.startTime) % particle.duration) / particle.duration
  return {
    ...particle,
    x: progress,
    y: ((Math.sin(progress * particle.arc) * particle.amplitude) + particle.offsetY),
  }
}

function drawParticle(particle: any) {
  if (!canvas || !ctx) return
  
  const vh = canvas.height / 100

  ctx.fillStyle = particle.colour
  ctx.beginPath()
  ctx.ellipse(
    particle.x * canvas.width,
    particle.y * vh + (canvas.height / 2),
    particle.diameter * vh,
    particle.diameter * vh,
    0,
    0,
    2 * Math.PI
  )
  ctx.fill()
}

function draw(time: number) {
  if (!canvas || !ctx) return

  // Move particles
  particles.forEach((particle, index) => {
    particles[index] = moveParticle(particle, time)
  })

  // Clear the canvas
  ctx.clearRect(0, 0, canvas.width, canvas.height)

  // Draw the particles
  particles.forEach((particle) => {
    drawParticle(particle)
  })

  // Schedule next frame
  animationFrameId = requestAnimationFrame((time) => draw(time))
}

function initializeCanvas() {
  canvas = document.getElementById('particle-canvas') as HTMLCanvasElement
  if (!canvas) return [null, null]
  
  canvas.width = canvas.offsetWidth * window.devicePixelRatio
  canvas.height = canvas.offsetHeight * window.devicePixelRatio
  ctx = canvas.getContext("2d")

  const handleResize = () => {
    if (!canvas) return
    canvas.width = canvas.offsetWidth * window.devicePixelRatio
    canvas.height = canvas.offsetHeight * window.devicePixelRatio
    ctx = canvas.getContext("2d")
  }

  window.addEventListener('resize', handleResize)
  
  return [canvas, ctx]
}

function startAnimation() {
  const [canvasElement, ctxElement] = initializeCanvas()
  
  if (!canvasElement || !ctxElement) return

  // Create particles
  particles = []
  for (let i = 0; i < NUM_PARTICLES; i++) {
    particles.push(createParticle())
  }
  
  animationFrameId = requestAnimationFrame((time) => draw(time))
}

onMounted(() => {
  // Esperar un poco para que el DOM esté listo
  setTimeout(() => {
    startAnimation()
  }, 100)
})

onUnmounted(() => {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
  }
  window.removeEventListener('resize', () => {})
})
</script>

<style scoped>
.particle-canvas {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgb(10, 10, 50) 0%, rgb(60, 10, 60) 100%);
  pointer-events: none;
  z-index: 1;
}
</style>

