# Guide de Déploiement sur Render - Mathey-Tissot Mauritania

Ce projet est configuré pour être déployé sur **Render** en utilisant **Docker**, ce qui garantit que l'environnement PHP, Apache et les extensions (SQLite, GD, etc.) sont parfaitement installés.

## Méthode 1 : Utilisation du Blueprint (Recommandé)

Render utilise le fichier `render.yaml` déjà présent à la racine du projet pour tout configurer automatiquement.

1. Connectez-vous à [Render Dashboard](https://dashboard.render.com/).
2. Cliquez sur **"New +"** (bouton bleu en haut à droite).
3. Choisissez **"Blueprint"**.
4. Connectez votre compte GitHub et sélectionnez le dépôt : `Swiss-watches---Mauritania`.
5. Donnez un nom au groupe (ex: `mathey-tissot`).
6. Cliquez sur **"Apply"**.

Render va alors :
- Détecter le `Dockerfile`.
- Télécharger les dépendances (Composer & NPM).
- Compiler les assets.
- Lancer le serveur Apache.

---

## Méthode 2 : Création d'un Web Service (Manuel)

Si vous préférez créer le service manuellement :

1. Cliquez sur **"New +"** -> **"Web Service"**.
2. Connectez votre dépôt GitHub.
3. Configuration :
   - **Language** : Docker
   - **Name** : `mathey-tissot-mauritania`
   - **Region** : Choisissez la plus proche (ex: Frankfurt).
   - **Branch** : `main`
4. Ajoutez les **Environment Variables** (Section "Advanced") :
   - `APP_KEY` : (Cliquez sur "Generate" sur Render)
   - `APP_ENV` : `production`
   - `APP_DEBUG` : `false`
   - `DB_CONNECTION` : `sqlite`
   - `DB_DATABASE` : `/var/www/html/database/database.sqlite`
5. Cliquez sur **"Create Web Service"**.

---

## ⚠️ Notes Importantes (Persistance)

### Base de données SQLite
Sur le plan **Free** de Render, les fichiers (comme la base de données `database.sqlite`) sont effacés à chaque redémarrage du serveur.
- **Utilisation temporaire** : Parfait pour les démos.
- **Utilisation réelle** : Pour conserver vos données (produits, commandes), vous devrez :
  1. Passer à un plan payant (Starter) et ajouter un **Render Disk** (monté sur `/var/www/html/database`).
  2. OU utiliser une base de données managée comme **Render PostgreSQL** (nécessite une petite modification de config dans `.env`).

### Temps de build
Le premier déploiement peut prendre 3 à 5 minutes car Docker doit installer tous les outils (PHP, Node.js, etc.). Les déploiements suivants seront plus rapides.

---
*Développé avec précision pour Mathey-Tissot - Mauritania ⌚*
