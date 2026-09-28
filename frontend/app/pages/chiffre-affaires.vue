<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const api = useApi()

const aujourdhui = new Date().toISOString().slice(0, 10)
const debut = ref(aujourdhui.slice(0, 8) + '01')
const fin = ref(aujourdhui)
const livreurId = ref('')
const resultat = ref<any>(null)
const erreur = ref('')

const { data: livreurs } = useLazyAsyncData('p-ca-livreurs', () => api<any[]>('/livreurs'))

const formatMontant = (n: number | string) => new Intl.NumberFormat('fr-FR').format(Number(n)) + ' FCFA'
const bornes = () => ({ debut: `${debut.value} 00:00:00`, fin: `${fin.value} 23:59:59` })

async function calculer() {
  erreur.value = ''
  try {
    const params: any = bornes()
    if (livreurId.value) params.livreur_id = livreurId.value
    resultat.value = await api('/rapports/chiffre-affaires', { query: params })
  } catch (e: any) {
    erreur.value = e?.data?.message ?? 'Erreur de calcul.'
  }
}

async function exporter() {
  erreur.value = ''
  try {
    const blob = await api<Blob>('/rapports/export', { query: bornes(), responseType: 'blob' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `courses_${debut.value}_${fin.value}.xlsx`
    a.click()
    URL.revokeObjectURL(url)
  } catch (e) {
    erreur.value = "L'export a échoué."
  }
}
</script>

<template>
  <main>
    <h1>Chiffre d'affaires</h1>
    <p v-if="erreur" class="erreur">{{ erreur }}</p>

    <div class="carte">
      <form class="ligne" @submit.prevent="calculer">
        <input v-model="debut" type="date" required />
        <input v-model="fin" type="date" required />
        <select v-model="livreurId">
          <option value="">Tous les livreurs</option>
          <option v-for="l in livreurs" :key="l.id" :value="l.id">{{ l.nom }} {{ l.prenom }}</option>
        </select>
        <button>Calculer</button>
        <button type="button" class="secondaire" @click="exporter">Exporter en tableur</button>
      </form>
    </div>

    <div v-if="resultat" class="carte">
      <div class="total">{{ formatMontant(resultat.chiffre_affaires) }}</div>
      <p>{{ resultat.nombre_courses }} course(s) livrée(s) sur la période</p>
    </div>
  </main>
</template>