<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const api = useApi()
const filtre = ref('')
const erreur = ref('')
const nouvelle = reactive({ adresse_depart: '', adresse_arrivee: '', montant: '', livreur_id: '' })

const { data, refresh } = await useAsyncData('courses-page', async () => {
  const [courses, livreurs] = await Promise.all([
    api<any[]>('/courses'),
    api<any[]>('/livreurs'),
  ])
  return { courses, livreurs }
})

const livreursActifs = computed(() => (data.value?.livreurs ?? []).filter((l: any) => !l.est_retire))
const courses = computed(() =>
  (data.value?.courses ?? [])
    .filter((c: any) => !filtre.value || c.statut === filtre.value)
    .slice()
    .sort((a: any, b: any) => b.id - a.id)
)

const libelleStatut: Record<string, string> = {
  en_attente: 'En attente',
  prise_en_charge: 'Prise en charge',
  livree: 'Livrée',
  annulee: 'Annulée',
}
const suivant: Record<string, string> = { en_attente: 'prise_en_charge', prise_en_charge: 'livree' }

function messageErreur(e: any) {
  const errs = e?.data?.errors
  return errs ? Object.values(errs).flat().join(' ') : (e?.data?.message ?? 'Une erreur est survenue.')
}

async function envoyer(path: string, method: string, body: any = {}) {
  erreur.value = ''
  try {
    await api(path, { method, body })
  } catch (e: any) {
    if (e?.statusCode === 409 || e?.status === 409) {
      if (confirm('Ce livreur a déjà une course active. Affecter quand même ?')) {
        try {
          await api(path, { method, body: { ...body, force: true } })
        } catch (e2: any) {
          erreur.value = messageErreur(e2)
        }
      }
    } else {
      erreur.value = messageErreur(e)
    }
  }
  await refresh()
}

async function creer() {
  const body: any = {
    adresse_depart: nouvelle.adresse_depart,
    adresse_arrivee: nouvelle.adresse_arrivee,
    montant: nouvelle.montant,
  }
  if (nouvelle.livreur_id) body.livreur_id = nouvelle.livreur_id
  await envoyer('/courses', 'POST', body)
  if (!erreur.value) Object.assign(nouvelle, { adresse_depart: '', adresse_arrivee: '', montant: '', livreur_id: '' })
}

const avancer = (c: any) => envoyer(`/courses/${c.id}/statut`, 'PATCH', { statut: suivant[c.statut] })

function annuler(c: any) {
  const motif = prompt("Motif de l'annulation ?")
  if (!motif) return
  return envoyer(`/courses/${c.id}/annuler`, 'PATCH', { motif_annulation: motif })
}

function modifierMontant(c: any) {
  const m = prompt('Nouveau montant', c.montant)
  if (m === null) return
  return envoyer(`/courses/${c.id}`, 'PUT', { montant: m })
}

function affecter(c: any, livreurId: string) {
  if (!livreurId) return
  return envoyer(`/courses/${c.id}/affecter`, 'PATCH', { livreur_id: livreurId })
}
</script>

<template>
  <main>
    <h1>Courses</h1>
    <p v-if="erreur" class="erreur">{{ erreur }}</p>

    <div class="carte">
      <h2>Nouvelle course</h2>
      <form class="ligne" @submit.prevent="creer">
        <input v-model="nouvelle.adresse_depart" placeholder="Adresse de départ" required />
        <input v-model="nouvelle.adresse_arrivee" placeholder="Adresse d'arrivée" required />
        <input v-model="nouvelle.montant" type="number" min="0" placeholder="Montant" required />
        <select v-model="nouvelle.livreur_id">
          <option value="">Non affectée</option>
          <option v-for="l in livreursActifs" :key="l.id" :value="l.id">{{ l.nom }} {{ l.prenom }}</option>
        </select>
        <button>Créer la course</button>
      </form>
    </div>

    <div class="carte">
      <select v-model="filtre">
        <option value="">Tous les statuts</option>
        <option v-for="(label, cle) in libelleStatut" :key="cle" :value="cle">{{ label }}</option>
      </select>
    </div>

    <table>
      <thead>
        <tr><th>#</th><th>Trajet</th><th>Montant</th><th>Livreur</th><th>Statut</th><th></th></tr>
      </thead>
      <tbody>
        <tr v-for="c in courses" :key="c.id">
          <td>{{ c.id }}</td>
          <td>{{ c.adresse_depart }} → {{ c.adresse_arrivee }}</td>
          <td>{{ c.montant }}</td>
          <td>
            <template v-if="c.statut === 'en_attente'">
              <select :value="c.livreur_id ?? ''" @change="affecter(c, ($event.target as HTMLSelectElement).value)">
                <option value="">Non affectée</option>
                <option v-for="l in livreursActifs" :key="l.id" :value="l.id">{{ l.nom }} {{ l.prenom }}</option>
              </select>
            </template>
            <template v-else>{{ c.livreur ? c.livreur.nom + ' ' + c.livreur.prenom : '—' }}</template>
          </td>
          <td>
            <span class="badge" :class="c.statut">{{ libelleStatut[c.statut] }}</span>
            <div v-if="c.motif_annulation">{{ c.motif_annulation }}</div>
          </td>
          <td class="actions">
            <button v-if="suivant[c.statut]" @click="avancer(c)">
              {{ c.statut === 'en_attente' ? 'Prendre en charge' : 'Marquer livrée' }}
            </button>
            <button class="secondaire" @click="modifierMontant(c)">Montant</button>
            <button v-if="c.statut !== 'livree' && c.statut !== 'annulee'" class="danger" @click="annuler(c)">Annuler</button>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</template>