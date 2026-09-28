<script setup lang="ts">
const api = useApi()
const token = useCookie<string | null>('token')
const email = ref('')
const password = ref('')
const erreur = ref('')
const chargement = ref(false)

async function seConnecter() {
  erreur.value = ''
  chargement.value = true
  try {
    const res = await api<{ token: string }>('/login', {
      method: 'POST',
      body: { email: email.value, password: password.value },
    })
    token.value = res.token
    await navigateTo('/')
  } catch (e: any) {
    erreur.value = e?.data?.message ?? 'Connexion impossible.'
  } finally {
    chargement.value = false
  }
}
</script>

<template>
  <main>
    <h1>FloLiv</h1>
    <form @submit.prevent="seConnecter">
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="password" type="password" placeholder="Mot de passe" required />
      <button :disabled="chargement">Se connecter</button>
      <p v-if="erreur">{{ erreur }}</p>
    </form>
  </main>
</template>