<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const api = useApi()
const { data: livreurs, error } = await useAsyncData('livreurs', () => api<any[]>('/livreurs'))
</script>

<template>
  <main>
    <h1>Livreurs</h1>
    <p v-if="error">Erreur de chargement.</p>
    <ul v-else>
      <li v-for="l in livreurs" :key="l.id">{{ l.nom }} {{ l.prenom }} — {{ l.zone?.nom }}</li>
    </ul>
  </main>
</template>