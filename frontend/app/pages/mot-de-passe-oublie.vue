<script setup lang="ts">
definePageMeta({ layout: false })
const api = useApi()
const email = ref('')
const message = ref('')
const erreur = ref('')
const chargement = ref(false)

async function envoyer() {
  erreur.value = ''
  message.value = ''
  chargement.value = true
  try {
    await api('/forgot-password', {
      method: 'POST',
      body: { email: email.value },
    })
    message.value = 'Si cet email existe, un lien de réinitialisation vient d\'être envoyé. Vérifie ta boîte de réception.'
    email.value = ''
  } catch (e: any) {
    erreur.value = e?.data?.message ?? 'Une erreur est survenue.'
  } finally {
    chargement.value = false
  }
}
</script>

<template>
  <main class="login">
    <h1>FloLiv</h1>
    <p class="sous-titre">Mot de passe oublié</p>

    <form class="carte" @submit.prevent="envoyer">
      <p style="color: var(--doux); font-size: 13px; margin: 0 0 8px;">
        Saisis ton adresse email. Tu recevras un lien pour créer un nouveau mot de passe.
      </p>

      <input v-model="email" type="email" placeholder="Adresse email" required />

      <button :disabled="chargement">
        {{ chargement ? 'Envoi en cours...' : 'Envoyer le lien' }}
      </button>

      <p v-if="message" style="color: var(--vert); font-size: 13px; margin: 0;">{{ message }}</p>
      <p v-if="erreur" class="erreur">{{ erreur }}</p>

      <NuxtLink to="/login" style="text-align: center; color: var(--doux); font-size: 13px; text-decoration: none; margin-top: 8px;">
        ← Retour à la connexion
      </NuxtLink>
    </form>
  </main>
</template>