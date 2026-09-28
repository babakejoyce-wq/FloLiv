<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<style>
body { margin: 0; font-family: system-ui, sans-serif; color: #111827; background: #f3f4f6; }
main { max-width: 1000px; margin: 0 auto; padding: 24px; }
h1 { margin-top: 0; }
nav.barre { background: #111827; display: flex; gap: 20px; align-items: center; padding: 12px 24px; }
nav.barre a { color: #d1d5db; text-decoration: none; }
nav.barre a.router-link-exact-active { color: #fff; font-weight: 600; }
nav.barre .titre { font-weight: 700; color: #fff; margin-right: auto; }
.carte { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 16px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
.carte h2 { margin: 0 0 10px; font-size: 16px; }
form.ligne { display: flex; flex-wrap: wrap; gap: 8px; }
input, select, button { padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
button { background: #4f46e5; color: #fff; border: none; cursor: pointer; }
button.secondaire { background: #e5e7eb; color: #111827; }
button.danger { background: #dc2626; }
table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; }
th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
.erreur { color: #dc2626; font-weight: 600; }
.badge { padding: 2px 8px; border-radius: 999px; font-size: 12px; background: #e5e7eb; }
.actions { display: flex; gap: 6px; flex-wrap: wrap; }
.total { font-size: 28px; font-weight: 700; }
</style>