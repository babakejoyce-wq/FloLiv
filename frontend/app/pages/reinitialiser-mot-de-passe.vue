<script setup lang="ts">
definePageMeta({ layout: false })
const api = useApi()
const route = useRoute()

const token = ref((route.query.token as string) ?? '')
const email = ref((route.query.email as string) ?? '')
const password = ref('')
const confirmation = ref('')
const message = ref('')
const erreur = ref('')
const chargement = ref(false)

async function reinitialiser() {
  erreur.value = ''
  message.value = ''

  if (password.value !== confirmation.value) {
    erreur.value = 'Les deux mots de passe ne correspondent pas.'
    return
  }

  chargement.value = true
  try {
    await api('/reset-password', {
      method: 'POST',
      body: {
        token: token.value,
        email: email.value,
        password: password.value,
        password_confirmation: confirmation.value,
      },
    })
    message.value = 'Mot de passe réinitialisé ! Tu peux maintenant te connecter.'
    setTimeout(() => navigateTo('/login'), 2000)
  } catch (e: any) {
    erreur.value = e?.data?.message ?? 'Lien invalide ou expiré.'
  } finally {
    chargement.value = false
  }
}
</script>

<template>
  <main class="login">
    <h1>FloLiv</h1>
    <p class="sous-titre">Nouveau mot de passe</p>

    <form class="carte" @submit.prevent="reinitialiser">
      <input v-model="email" type="email" placeholder="Adresse email" required />
      <input v-model="password" type="password" placeholder="Nouveau mot de passe" required minlength="8" />
      <input v-model="confirmation" type="password" placeholder="Confirmer le mot de passe" required minlength="8" />

      <button :disabled="chargement">
        {{ chargement ? 'Réinitialisation...' : 'Réinitialiser' }}
      </button>

      <p v-if="message" style="color: var(--vert); font-size: 13px; margin: 0;">{{ message }}</p>
      <p v-if="erreur" class="erreur">{{ erreur }}</p>

      <NuxtLink to="/login" style="text-align: center; color: var(--doux); font-size: 13px; text-decoration: none; margin-top: 8px;">
        ← Retour à la connexion
      </NuxtLink>
    </form>
  </main>
</template>