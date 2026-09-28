# FloLiv - Gestion de flotte de livraison

Application web de gestion d'une flotte de livraison de proximité : livreurs, courses, affectation, suivi des statuts et chiffre d'affaires par période.

## Stack technique
- **Backend** : Laravel 12 (API REST), Sanctum (authentification par token), MySQL
- **Frontend** : Nuxt.js 4

## Organisation du dépôt
- Branche `backend` : le dossier `backend/` (API Laravel)
- Branche `frontend` : le dossier `frontend/` (application Nuxt.js)
- Branche `main` : documentation

## Fonctionnalités
- Gestion des livreurs (ajout, modification, retrait) avec leur zone et leur véhicule
- Création, affectation et réaffectation des courses (avec alerte en cas de conflit)
- Suivi des statuts : en attente, prise en charge, livrée, annulée (avec motif)
- Chiffre d'affaires par période et par livreur
- Export des courses en tableur
- Accès réservé au personnel (connexion par token)

## Règles de gestion appliquées
- RG1 : une course suit l'ordre en attente, prise en charge, livrée
- RG2 : un livreur ayant une course non livrée ne peut pas être retiré
- RG3 : le montant d'une course livrée ne peut plus être modifié
- RG4 : une course annulée n'entre pas dans le chiffre d'affaires
- RG5 : le chiffre d'affaires d'une période compte les courses livrées durant cette période

## Installation

### Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```
Renseigner dans `.env` la base de données (`DB_DATABASE=floliv`) et le SMTP (`MAIL_*`), puis :
```bash
php artisan migrate
php artisan serve
```
Créer un utilisateur de test avec `php artisan tinker` :
```php
App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('mot_de_passe')]);
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```
L'application est disponible sur `http://localhost:3000` (l'API sur `http://127.0.0.1:8000`).

## Auteur
BABAKE Essossimna Joyce - Stagiaire en développement d'applications
