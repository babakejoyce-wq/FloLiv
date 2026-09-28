<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<style>
/* ============================================================
   FLOLIV — Artillerie lourde 
   Dark Premium SaaS : Linear + Vercel + Stripe + Raycast
   ============================================================ */

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap');

/* ---------- Variables ---------- */
:root {
  color-scheme: dark;

  /* Fonds */
  --fond: #05060f;
  --fond-2: #0a0c1b;
  --carte: rgba(20, 24, 48, 0.65);
  --carte-hover: rgba(28, 33, 60, 0.75);
  --carte-solide: #0f1429;

  /* Bordures */
  --bord: rgba(255, 255, 255, 0.07);
  --bord-fort: rgba(255, 255, 255, 0.14);
  --bord-glow: rgba(120, 130, 255, 0.35);

  /* Texte */
  --texte: #eef0ff;
  --doux: #a4abd4;
  --tres-doux: #6a719b;

  /* Accents */
  --primary: #6d7cff;
  --primary-2: #a855f7;
  --primary-glow: rgba(109, 124, 255, 0.45);
  --or: #f5c451;
  --or-2: #ff9a3c;
  --vert: #34d399;
  --rouge: #f87171;
  --bleu: #60a5fa;

  /* Dégradés signature */
  --grad-primary: linear-gradient(135deg, #6d7cff 0%, #a855f7 100%);
  --grad-or: linear-gradient(135deg, #f5c451 0%, #ff9a3c 100%);
  --grad-aurora: linear-gradient(135deg, #6d7cff 0%, #a855f7 50%, #f5c451 100%);

  /* Ombres */
  --ombre: 0 20px 60px rgba(0, 0, 0, 0.5);
  --ombre-glow: 0 0 40px rgba(109, 124, 255, 0.25);
  --ombre-or: 0 0 40px rgba(245, 196, 81, 0.3);

  /* Transitions */
  --t: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  --t-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ---------- Reset & base ---------- */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

html, body {
  min-height: 100vh;
}

body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  font-size: 15px;
  line-height: 1.6;
  color: var(--texte);
  background: var(--fond);
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Effet aurora en fond, animé */
body::before {
  content: '';
  position: fixed;
  inset: -50%;
  z-index: -2;
  background:
    radial-gradient(600px 400px at 20% 10%, rgba(109, 124, 255, 0.18), transparent 60%),
    radial-gradient(700px 500px at 80% 30%, rgba(168, 85, 247, 0.15), transparent 60%),
    radial-gradient(500px 400px at 50% 90%, rgba(245, 196, 81, 0.10), transparent 60%);
  animation: aurora 20s ease-in-out infinite alternate;
  pointer-events: none;
}

@keyframes aurora {
  0%   { transform: translate(0, 0) scale(1); }
  50%  { transform: translate(-3%, 2%) scale(1.05); }
  100% { transform: translate(2%, -2%) scale(1.02); }
}

/* Grille subtile en fond */
body::after {
  content: '';
  position: fixed;
  inset: 0;
  z-index: -1;
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
  background-size: 60px 60px;
  mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
  -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
  pointer-events: none;
}

/* ---------- Layout principal ---------- */
main {
  max-width: 1160px;
  margin: 0 auto;
  padding: 48px 28px 100px;
  animation: fadeUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(16px); }
  to { opacity: 1; transform: none; }
}

/* ---------- Titres ---------- */
h1 {
  font-family: 'Inter', sans-serif;
  font-size: 42px;
  font-weight: 800;
  letter-spacing: -0.03em;
  margin: 0 0 32px;
  background: var(--grad-aurora);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  line-height: 1.1;
}

h2 {
  margin: 0 0 18px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  color: var(--doux);
}

/* ============================================================
   BARRE DE NAVIGATION — Glassmorphism
   ============================================================ */
nav.barre {
  position: sticky;
  top: 0;
  z-index: 100;
  display: flex;
  align-items: center;
  gap: 36px;
  padding: 18px 44px;
  background: rgba(5, 6, 15, 0.6);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border-bottom: 1px solid var(--bord);
}

nav.barre .titre {
  font-size: 22px;
  font-weight: 800;
  margin-right: auto;
  letter-spacing: -0.5px;
  background: var(--grad-aurora);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  display: flex;
  align-items: center;
  gap: 10px;
}

nav.barre .titre::before {
  content: '';
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--grad-primary);
  box-shadow: 0 0 16px var(--primary-glow);
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.6; transform: scale(1.3); }
}

nav.barre a {
  position: relative;
  color: var(--doux);
  text-decoration: none;
  font-weight: 500;
  font-size: 14px;
  padding: 8px 0;
  transition: color var(--t);
}

nav.barre a::after {
  content: '';
  position: absolute;
  left: 50%;
  right: 50%;
  bottom: -4px;
  height: 2px;
  background: var(--grad-primary);
  border-radius: 2px;
  transition: left var(--t), right var(--t);
}

nav.barre a:hover {
  color: var(--texte);
}

nav.barre a:hover::after {
  left: 0;
  right: 0;
}

nav.barre a.router-link-exact-active {
  color: var(--texte);
}

nav.barre a.router-link-exact-active::after {
  left: 0;
  right: 0;
  box-shadow: 0 0 12px var(--primary-glow);
}

/* ============================================================
   CARTES — Glassmorphism premium
   ============================================================ */
.carte {
  position: relative;
  background: var(--carte);
  backdrop-filter: blur(24px) saturate(160%);
  -webkit-backdrop-filter: blur(24px) saturate(160%);
  border: 1px solid var(--bord);
  border-radius: 20px;
  padding: 28px;
  margin-bottom: 24px;
  box-shadow: var(--ombre);
  transition: border-color var(--t), transform var(--t), box-shadow var(--t);
  overflow: hidden;
}

/* Ligne lumineuse en haut de la carte */
.carte::before {
  content: '';
  position: absolute;
  top: 0;
  left: 10%;
  right: 10%;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
}

.carte:hover {
  border-color: var(--bord-fort);
  transform: translateY(-2px);
  box-shadow: var(--ombre), 0 0 0 1px rgba(109, 124, 255, 0.15), var(--ombre-glow);
}

/* ============================================================
   FORMULAIRES
   ============================================================ */
form.ligne {
  display: flex;
  flex-wrap: wrap;
  gap: 14px;
  align-items: center;
}

input, select {
  padding: 12px 16px;
  background: rgba(255, 255, 255, 0.03);
  color: var(--texte);
  border: 1px solid var(--bord);
  border-radius: 12px;
  font: inherit;
  font-size: 14px;
  font-weight: 500;
  transition: border-color var(--t), box-shadow var(--t), background var(--t);
  flex: 1;
  min-width: 0;
  outline: none;
}

input::placeholder {
  color: var(--tres-doux);
  font-weight: 400;
}

input:hover, select:hover {
  border-color: var(--bord-fort);
  background: rgba(255, 255, 255, 0.05);
}

input:focus, select:focus {
  border-color: var(--primary);
  background: rgba(109, 124, 255, 0.06);
  box-shadow: 0 0 0 4px rgba(109, 124, 255, 0.15), 0 0 20px rgba(109, 124, 255, 0.2);
}

select option {
  background: var(--carte-solide);
  color: var(--texte);
}

/* ============================================================
   BOUTONS — avec glow premium
   ============================================================ */
button {
  position: relative;
  padding: 12px 22px;
  border: none;
  border-radius: 12px;
  font: inherit;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  color: #ffffff;
  background: var(--grad-primary);
  box-shadow: 0 4px 20px rgba(109, 124, 255, 0.3);
  transition: transform var(--t), box-shadow var(--t), opacity var(--t);
  white-space: nowrap;
  letter-spacing: 0.2px;
  overflow: hidden;
}

/* Effet de brillance qui traverse le bouton au survol */
button::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(105deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
  transform: translateX(-150%);
  transition: transform 0.7s ease;
}

button:hover:not(:disabled)::before {
  transform: translateX(150%);
}

button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(109, 124, 255, 0.5), 0 0 40px rgba(168, 85, 247, 0.3);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  box-shadow: none;
}

button.secondaire {
  background: rgba(255, 255, 255, 0.04);
  color: var(--texte);
  border: 1px solid var(--bord-fort);
  box-shadow: none;
}

button.secondaire:hover:not(:disabled) {
  background: rgba(255, 255, 255, 0.08);
  border-color: var(--primary);
  color: var(--texte);
  box-shadow: 0 0 24px rgba(109, 124, 255, 0.25);
  transform: translateY(-1px);
}

button.danger {
  background: rgba(248, 113, 113, 0.08);
  color: var(--rouge);
  border: 1px solid rgba(248, 113, 113, 0.3);
  box-shadow: none;
}

button.danger:hover:not(:disabled) {
  background: rgba(248, 113, 113, 0.15);
  border-color: var(--rouge);
  box-shadow: 0 0 24px rgba(248, 113, 113, 0.3);
  transform: translateY(-1px);
}

/* ============================================================
   TABLEAUX — style premium
   ============================================================ */
table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: var(--carte);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid var(--bord);
  border-radius: 20px;
  overflow: hidden;
  margin-bottom: 24px;
  box-shadow: var(--ombre);
}

th {
  text-align: left;
  padding: 18px 20px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: var(--doux);
  background: rgba(255, 255, 255, 0.025);
  border-bottom: 1px solid var(--bord);
}

td {
  padding: 18px 20px;
  font-size: 14px;
  border-top: 1px solid var(--bord);
  vertical-align: middle;
  color: var(--texte);
}

tbody tr {
  transition: background var(--t);
}

tbody tr:hover {
  background: rgba(109, 124, 255, 0.04);
}

tbody tr:last-child td {
  border-bottom: none;
}

/* Actions dans les tableaux */
.actions {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.actions button {
  padding: 8px 14px;
  font-size: 12.5px;
}

/* ============================================================
   MESSAGES, BADGES, TOTAL
   ============================================================ */
.erreur {
  position: relative;
  color: #fca5a5;
  background: rgba(248, 113, 113, 0.08);
  border: 1px solid rgba(248, 113, 113, 0.25);
  padding: 14px 18px;
  border-radius: 12px;
  font-weight: 500;
  font-size: 14px;
  margin-bottom: 24px;
  backdrop-filter: blur(10px);
}

/* Badges avec effet glow */
.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 12px;
  border-radius: 999px;
  font-size: 11.5px;
  font-weight: 600;
  letter-spacing: 0.3px;
  background: rgba(255, 255, 255, 0.06);
  color: var(--doux);
  border: 1px solid transparent;
}

.badge::before {
  content: '';
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
  box-shadow: 0 0 8px currentColor;
}

.badge.en_attente {
  background: rgba(245, 196, 81, 0.1);
  color: var(--or);
  border-color: rgba(245, 196, 81, 0.25);
}

.badge.prise_en_charge {
  background: rgba(96, 165, 250, 0.1);
  color: var(--bleu);
  border-color: rgba(96, 165, 250, 0.25);
}

.badge.livree {
  background: rgba(52, 211, 153, 0.1);
  color: var(--vert);
  border-color: rgba(52, 211, 153, 0.25);
}

.badge.annulee {
  background: rgba(248, 113, 113, 0.1);
  color: var(--rouge);
  border-color: rgba(248, 113, 113, 0.25);
}

/* Total CA — effet néon doré */
.total {
  font-family: 'Inter', sans-serif;
  font-size: 72px;
  font-weight: 900;
  letter-spacing: -0.04em;
  line-height: 1;
  margin-bottom: 12px;
  background: var(--grad-or);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  filter: drop-shadow(0 0 30px rgba(245, 196, 81, 0.4));
}

/* ============================================================
   PAGE DE CONNEXION — effet premium
   ============================================================ */
main.login {
  max-width: 460px;
  margin-top: 14vh;
  text-align: center;
  padding: 0 24px;
}

main.login h1 {
  font-size: 68px;
  margin-bottom: 12px;
  text-align: center;
  font-weight: 900;
}

main.login .sous-titre {
  color: var(--doux);
  margin: 0 0 40px;
  font-size: 15px;
  font-weight: 400;
  letter-spacing: 0.5px;
}

main.login form {
  display: flex;
  flex-direction: column;
  gap: 16px;
  text-align: left;
}

main.login form.carte {
  padding: 36px;
}

main.login .erreur {
  margin: 0;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
  main {
    padding: 28px 18px 70px;
  }

  h1 {
    font-size: 32px;
  }

  .total {
    font-size: 52px;
  }

  nav.barre {
    padding: 14px 18px;
    gap: 18px;
    overflow-x: auto;
  }

  nav.barre .titre {
    font-size: 18px;
  }

  nav.barre a {
    font-size: 13px;
    white-space: nowrap;
  }

  form.ligne {
    flex-direction: column;
    align-items: stretch;
  }

  form.ligne > * {
    width: 100%;
  }

  table {
    font-size: 13px;
  }

  th, td {
    padding: 14px 12px;
  }

  .actions {
    flex-direction: column;
  }

  main.login h1 {
    font-size: 52px;
  }
}
</style>