<script setup lang="ts">
const token = useCookie<string | null>('token')
function deconnexion() {
  token.value = null
  navigateTo('/login')
}
</script>

<template>
  <div>
    <nav class="barre">
      <span class="titre">FloLiv</span>
      <NuxtLink to="/">Livreurs</NuxtLink>
      <NuxtLink to="/courses">Courses</NuxtLink>
      <NuxtLink to="/chiffre-affaires">Chiffre d'affaires</NuxtLink>
      <button class="secondaire" @click="deconnexion">Déconnexion</button>
    </nav>
    <slot />
  </div>
</template>