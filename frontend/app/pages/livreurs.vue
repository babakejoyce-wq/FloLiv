<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const api = useApi()

const { data: livreurs, refresh: rafraichirLivreurs, pending } = useLazyAsyncData('p-livreurs', () => api<any[]>('/livreurs'))
const { data: zones, refresh: rafraichirZones } = useLazyAsyncData('p-zones', () => api<any[]>('/zones'))
const { data: vehicules, refresh: rafraichirVehicules } = useLazyAsyncData('p-vehicules', () => api<any[]>('/vehicules'))

const livreursActifs = computed(() => (livreurs.value ?? []).filter((l: any) => !l.est_retire))
const vehiculesLibres = computed(() => {
  const pris = new Set((livreurs.value ?? []).map((l: any) => l.vehicule_id))
  return (vehicules.value ?? []).filter((v: any) => !pris.has(v.id))
})

const erreur = ref('')
const zoneNom = ref('')
const veh = reactive({ type: '', immatriculation: '' })
const liv = reactive({ nom: '', prenom: '', telephone: '', zone_id: '', vehicule_id: '' })

function messageErreur(e: any) {
  const errs = e?.data?.errors
  return errs ? Object.values(errs).flat().join(' ') : (e?.data?.message ?? 'Une erreur est survenue.')
}

async function action(fn: () => Promise<any>, rafraichir: () => Promise<any>) {
  erreur.value = ''
  try {
    await fn()
    await rafraichir()
  } catch (e: any) {
    erreur.value = messageErreur(e)
  }
}

const ajouterZone = () => action(async () => {
  await api('/zones', { method: 'POST', body: { nom: zoneNom.value } })
  zoneNom.value = ''
}, rafraichirZones)

const ajouterVehicule = () => action(async () => {
  await api('/vehicules', { method: 'POST', body: { type: veh.type, immatriculation: veh.immatriculation || null } })
  veh.type = ''
  veh.immatriculation = ''
}, rafraichirVehicules)

const ajouterLivreur = () => action(async () => {
  await api('/livreurs', { method: 'POST', body: { ...liv } })
  Object.assign(liv, { nom: '', prenom: '', telephone: '', zone_id: '', vehicule_id: '' })
}, rafraichirLivreurs)

function modifier(l: any) {
  const nom = prompt('Nom', l.nom)
  if (nom === null) return
  const prenom = prompt('Prénom', l.prenom)
  if (prenom === null) return
  const telephone = prompt('Téléphone', l.telephone)
  if (telephone === null) return
  return action(() => api(`/livreurs/${l.id}`, { method: 'PUT', body: { nom, prenom, telephone } }), rafraichirLivreurs)
}

function retirer(l: any) {
  if (!confirm(`Retirer ${l.nom} ${l.prenom} ?`)) return
  return action(() => api(`/livreurs/${l.id}`, { method: 'DELETE' }), rafraichirLivreurs)
}
</script>

<template>
  <main>
    <h1>Livreurs</h1>
    <p v-if="erreur" class="erreur">{{ erreur }}</p>

    <div class="carte">
      <h2>Nouvelle zone</h2>
      <form class="ligne" @submit.prevent="ajouterZone">
        <input v-model="zoneNom" placeholder="Nom de la zone" required />
        <button>Ajouter la zone</button>
      </form>
    </div>

    <div class="carte">
      <h2>Nouveau véhicule</h2>
      <form class="ligne" @submit.prevent="ajouterVehicule">
        <input v-model="veh.type" placeholder="Type (Moto, Voiture...)" required />
        <input v-model="veh.immatriculation" placeholder="Immatriculation" />
        <button>Ajouter le véhicule</button>
      </form>
    </div>

    <div class="carte">
      <h2>Nouveau livreur</h2>
      <form class="ligne" @submit.prevent="ajouterLivreur">
        <input v-model="liv.nom" placeholder="Nom" required />
        <input v-model="liv.prenom" placeholder="Prénom" required />
        <input v-model="liv.telephone" placeholder="Téléphone" required />
        <select v-model="liv.zone_id" required>
          <option value="" disabled>Zone</option>
          <option v-for="z in zones" :key="z.id" :value="z.id">{{ z.nom }}</option>
        </select>
        <select v-model="liv.vehicule_id" required>
          <option value="" disabled>Véhicule libre</option>
          <option v-for="v in vehiculesLibres" :key="v.id" :value="v.id">{{ v.type }} {{ v.immatriculation }}</option>
        </select>
        <button>Enregistrer le livreur</button>
      </form>
    </div>

    <p v-if="pending">Chargement…</p>
    <table>
      <thead>
        <tr><th>Nom</th><th>Téléphone</th><th>Zone</th><th>Véhicule</th><th></th></tr>
      </thead>
      <tbody>
        <tr v-for="l in livreursActifs" :key="l.id">
          <td>{{ l.nom }} {{ l.prenom }}</td>
          <td>{{ l.telephone }}</td>
          <td>{{ l.zone?.nom }}</td>
          <td>{{ l.vehicule?.type }} {{ l.vehicule?.immatriculation }}</td>
          <td class="actions">
            <button class="secondaire" @click="modifier(l)">Modifier</button>
            <button class="danger" @click="retirer(l)">Retirer</button>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</template>