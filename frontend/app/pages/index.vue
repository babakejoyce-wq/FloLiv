<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const api = useApi()

const { data, pending } = useLazyAsyncData(
  'dashboard',
  async () => {
    const jour = new Date().toISOString().slice(0, 10)
    const [livreurs, courses, ca] = await Promise.all([
      api<any[]>('/livreurs'),
      api<any[]>('/courses'),
      api<any>('/rapports/chiffre-affaires', {
        query: { debut: `${jour} 00:00:00`, fin: `${jour} 23:59:59` },
      }),
    ])
    return { livreurs, courses, ca }
  },
  { getCachedData: () => undefined }
)

const livreursActifs = computed(() =>
  (data.value?.livreurs ?? []).filter((l: any) => !l.est_retire)
)

const coursesDuJour = computed(() => {
  const aujourdhui = new Date().toISOString().slice(0, 10)
  return (data.value?.courses ?? []).filter((c: any) => c.created_at?.startsWith(aujourdhui))
})

const coursesEnCours = computed(() =>
  (data.value?.courses ?? []).filter((c: any) => c.statut === 'prise_en_charge')
)

const caDuJour = computed(() => Number(data.value?.ca?.chiffre_affaires ?? 0))

const dernieresCourses = computed(() =>
  (data.value?.courses ?? []).slice().sort((a: any, b: any) => b.id - a.id).slice(0, 5)
)

function formatMontant(n: number) {
  return new Intl.NumberFormat('fr-FR').format(n) + ' FCFA'
}

const libelleStatut: Record<string, string> = {
  en_attente: 'En attente',
  prise_en_charge: 'Prise en charge',
  livree: 'Livrée',
  annulee: 'Annulée',
}

const heure = new Date().getHours()
const salutation = heure < 12 ? 'Bonjour' : heure < 18 ? 'Bon après-midi' : 'Bonsoir'
</script>

<template>
  <main>
    <!-- En-tête -->
    <header class="entete">
      <div>
        <p class="salutation">{{ salutation }} 👋</p>
        <h1>Tableau de bord</h1>
        <p class="sous-titre">Voici l'activité de FloLiv en temps réel</p>
      </div>
      <div class="date-jour">
        {{ new Date().toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' }) }}
      </div>
    </header>

    <!-- Cartes statistiques -->
    <div class="stats">
      <div class="stat-card">
        <div class="stat-icon">🚚</div>
        <div class="stat-valeur">{{ livreursActifs.length }}</div>
        <div class="stat-label">Livreurs actifs</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-valeur">{{ coursesDuJour.length }}</div>
        <div class="stat-label">Courses aujourd'hui</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-valeur">{{ coursesEnCours.length }}</div>
        <div class="stat-label">En cours</div>
      </div>

      <div class="stat-card accent">
        <div class="stat-icon">💰</div>
        <div class="stat-valeur">{{ formatMontant(caDuJour) }}</div>
        <div class="stat-label">CA du jour</div>
      </div>
    </div>

    <!-- Deux colonnes -->
    <div class="grille-2">
      <!-- Dernières courses -->
      <div class="carte">
        <div class="carte-entete">
          <h2>Dernières courses</h2>
          <NuxtLink to="/courses" class="lien">Voir tout →</NuxtLink>
        </div>
        <div v-if="dernieresCourses.length === 0" class="vide">
          Aucune course pour le moment.
        </div>
        <ul v-else class="liste-courses">
          <li v-for="c in dernieresCourses" :key="c.id">
            <div class="course-id">#{{ c.id }}</div>
            <div class="course-trajet">
              {{ c.adresse_depart }} <span class="flech">→</span> {{ c.adresse_arrivee }}
            </div>
            <div class="course-montant">{{ formatMontant(Number(c.montant)) }}</div>
            <span class="badge" :class="c.statut">{{ libelleStatut[c.statut] }}</span>
          </li>
        </ul>
      </div>

      <!-- Courses en cours -->
      <div class="carte">
        <div class="carte-entete">
          <h2>Courses en cours</h2>
          <span class="pastille">{{ coursesEnCours.length }}</span>
        </div>
        <div v-if="coursesEnCours.length === 0" class="vide">
          Aucune course en cours actuellement.
        </div>
        <ul v-else class="liste-courses">
          <li v-for="c in coursesEnCours" :key="c.id">
            <div class="course-id">#{{ c.id }}</div>
            <div class="course-trajet">
              {{ c.adresse_depart }} <span class="flech">→</span> {{ c.adresse_arrivee }}
            </div>
            <div class="course-livreur">
              {{ c.livreur ? c.livreur.nom + ' ' + c.livreur.prenom : '—' }}
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Actions rapides -->
    <div class="carte">
      <h2>Actions rapides</h2>
      <div class="actions-rapides">
        <NuxtLink to="/courses" class="action-btn">
          <span class="action-icon">＋</span>
          <span>Nouvelle course</span>
        </NuxtLink>
        <NuxtLink to="/livreurs" class="action-btn">
          <span class="action-icon">👤</span>
          <span>Nouveau livreur</span>
        </NuxtLink>
        <NuxtLink to="/chiffre-affaires" class="action-btn">
          <span class="action-icon">💰</span>
          <span>Chiffre d'affaires</span>
        </NuxtLink>
      </div>
    </div>
  </main>
</template>

<style scoped>
/* ---------- En-tête ---------- */
.entete {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 24px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.salutation {
  color: var(--doux);
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 6px;
  letter-spacing: 0.3px;
}

.date-jour {
  color: var(--doux);
  font-size: 13px;
  text-transform: capitalize;
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid var(--bord);
  border-radius: 12px;
}

.sous-titre {
  color: var(--doux);
  font-size: 14px;
  margin-top: 4px;
}

/* ---------- Stats ---------- */
.stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 32px;
}

.stat-card {
  background: var(--carte);
  backdrop-filter: blur(20px);
  border: 1px solid var(--bord);
  border-radius: 20px;
  padding: 24px;
  position: relative;
  overflow: hidden;
  transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 15%;
  right: 15%;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
}

.stat-card:hover {
  transform: translateY(-3px);
  border-color: var(--bord-fort);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(109, 124, 255, 0.15);
}

.stat-card.accent {
  background: linear-gradient(135deg, rgba(245, 196, 81, 0.08), rgba(255, 154, 60, 0.05));
  border-color: rgba(245, 196, 81, 0.25);
}

.stat-card.accent .stat-valeur {
  background: var(--grad-or);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}

.stat-icon {
  font-size: 24px;
  margin-bottom: 12px;
  opacity: 0.9;
}

.stat-valeur {
  font-size: 32px;
  font-weight: 800;
  letter-spacing: -0.03em;
  color: var(--texte);
  margin-bottom: 4px;
  line-height: 1;
}

.stat-label {
  font-size: 12px;
  color: var(--doux);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* ---------- Grille 2 colonnes ---------- */
.grille-2 {
  display: grid;
  grid-template-columns: 1.4fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.carte-entete {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 18px;
}

.carte-entete h2 {
  margin: 0;
}

.lien {
  color: var(--primary);
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  transition: color 0.2s ease;
}

.lien:hover {
  color: var(--primary-2);
}

.pastille {
  background: var(--grad-primary);
  color: #fff;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 700;
}

/* ---------- Liste des courses ---------- */
.liste-courses {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.liste-courses li {
  display: grid;
  grid-template-columns: 50px 1fr auto auto;
  gap: 14px;
  align-items: center;
  padding: 12px 14px;
  border-radius: 10px;
  transition: background 0.2s ease;
  font-size: 13.5px;
}

.liste-courses li:hover {
  background: rgba(255, 255, 255, 0.03);
}

.course-id {
  color: var(--doux);
  font-weight: 600;
  font-size: 12px;
  font-family: 'JetBrains Mono', monospace;
}

.course-trajet {
  color: var(--texte);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.flech {
  color: var(--primary);
  margin: 0 4px;
}

.course-montant {
  font-weight: 600;
  color: var(--texte);
  font-size: 13px;
  white-space: nowrap;
}

.course-livreur {
  color: var(--doux);
  font-size: 12.5px;
  white-space: nowrap;
}

.vide {
  color: var(--tres-doux);
  font-size: 13.5px;
  text-align: center;
  padding: 24px 0;
  font-style: italic;
}

/* ---------- Actions rapides ---------- */
.actions-rapides {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px 20px;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid var(--bord);
  border-radius: 14px;
  color: var(--texte);
  text-decoration: none;
  font-weight: 500;
  font-size: 14px;
  transition: all 0.25s ease;
}

.action-btn:hover {
  background: rgba(109, 124, 255, 0.08);
  border-color: var(--primary);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(109, 124, 255, 0.2);
}

.action-icon {
  font-size: 20px;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--grad-primary);
  border-radius: 10px;
  color: #fff;
  font-weight: 700;
  flex-shrink: 0;
}

/* ---------- Responsive ---------- */
@media (max-width: 900px) {
  .stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .grille-2 {
    grid-template-columns: 1fr;
  }

  .actions-rapides {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .stats {
    grid-template-columns: 1fr;
  }

  .stat-valeur {
    font-size: 26px;
  }

  .liste-courses li {
    grid-template-columns: 1fr;
    gap: 6px;
  }

  .entete {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>