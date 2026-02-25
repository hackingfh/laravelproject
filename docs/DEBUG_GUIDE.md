# Guide de Débogage - Mathey-Tissot

## 🚨 Problème : Page blanche

Une page blanche indique généralement une erreur PHP critique, un problème de configuration ou une ressource manquante.

## 🔍 Étapes de Diagnostic Rapide

### 1. Vérifier l'état de santé du système
```bash
curl http://127.0.0.1:8000/debug/health
```

### 2. Consulter les logs Laravel
```bash
tail -f storage/logs/laravel.log
```

### 3. Vérifier les erreurs PHP
```bash
php -l app/Http/Controllers/Front/HomeController.php
```

### 4. Tester les routes essentielles
```bash
curl -I http://127.0.0.1:8000/
curl -I http://127.0.0.1:8000/debug/system
```

## 🛠️ Outils de Débogage Disponibles

### Health Check (`/debug/health`)
Vérifie tous les composants du système :
- ✅ Connexion base de données
- ✅ Système de cache
- ✅ Stockage de fichiers
- ✅ Sessions
- ✅ Routes
- ✅ Vues
- ✅ Assets
- ✅ Extensions PHP
- ✅ Permissions

### Logs (`/debug/logs`)
Consulte les logs Laravel avec parsing et filtrage :
- Dernières lignes avec timestamps
- Niveaux de log (ERROR, WARNING, INFO)
- Messages formatés

### System Info (`/debug/system`)
Informations complètes sur l'environnement :
- Version PHP et extensions
- Configuration Laravel
- Variables serveur
- Utilisation mémoire

### Recovery Points
Créez et restaurez des points de récupération :
```bash
# Créer un point de récupération
curl http://127.0.0.1:8000/debug/recovery/create

# Restaurer depuis un point
curl http://127.0.0.1:8000/debug/recovery/restore/{pointId}
```

## 📋 Checklist de Résolution

### ✅ Configuration Base
- [ ] `APP_DEBUG=true` dans `.env`
- [ ] `APP_KEY` généré (`php artisan key:generate`)
- [ ] Permissions correctes sur `storage/` et `bootstrap/cache/`
- [ ] Base de données configurée

### ✅ Ressources Frontend
- [ ] Vite en cours d'exécution (`npm run dev`)
- [ ] Assets compilés (`npm run build`)
- [ ] Fichiers CSS/JS accessibles dans `public/`
- [ ] Images présentes dans `public/images/`

### ✅ Routes et Contrôleurs
- [ ] Routes définies correctement
- [ ] Contrôleurs existants et syntaxe valide
- [ ] Middleware correctement configuré
- [ ] Vues existantes et syntaxe Blade valide

### ✅ Dépendances
- [ ] `composer install` exécuté
- [ ] `npm install` exécuté
- [ ] Autoloader régénéré (`composer dump-autoload`)

## 🐛 Problèmes Courants et Solutions

### 1. Erreur 500 - Page blanche
**Cause** : Erreur PHP non capturée
**Solution** :
```bash
# Activer l'affichage des erreurs
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Vérifier les logs
tail storage/logs/laravel.log
```

### 2. Assets non chargés (CSS/JS)
**Cause** : Vite non démarré ou compilation échouée
**Solution** :
```bash
# Redémarrer Vite
npm run dev

# Recompiler les assets
npm run build

# Vider le cache Vite
rm -rf node_modules/.vite
```

### 3. Base de données inaccessible
**Cause** : Driver manquant ou mauvaise configuration
**Solution** :
```bash
# Vérifier les extensions PHP
php -m | grep -i sqlite
php -m | grep -i mysql

# Tester la connexion
php artisan tinker
>>> DB::connection()->getPdo();
```

### 4. Permissions incorrectes
**Cause** : Droits d'écriture manquants
**Solution** :
```bash
# Linux/Mac
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# Windows (en administrateur)
icacls storage /grant Everyone:F /t
```

## 🔄 Processus de Récupération Automatique

### 1. Création d'un Point de Récupération
```php
// Dans un contrôleur ou middleware
$healthCheck = app(HealthCheckService::class);
$pointId = $healthCheck->createRecoveryPoint();
```

### 2. Restauration depuis un Point
```php
$point = $healthCheck->restoreFromRecoveryPoint($pointId);
if ($point) {
    // Restaurer la configuration
    config(['app.debug' => $point['debug_mode']]);
}
```

## 📊 Monitoring en Temps Réel

### Headers de Debug ajoutés automatiquement :
- `X-Debug-Time`: Temps d'exécution
- `X-Debug-Memory`: Utilisation mémoire peak

### Logs structurés :
```json
{
  "timestamp": "2026-02-25T01:00:00.000Z",
  "level": "info",
  "message": "Request completed",
  "context": {
    "url": "http://127.0.0.1:8000/",
    "method": "GET",
    "status": 200,
    "execution_time": "45.23ms",
    "memory_peak": "8.5MB"
  }
}
```

## 🧪 Tests Automatisés

Exécutez les tests pour vérifier l'intégrité :
```bash
# Tests de santé
php artisan test tests/Feature/HealthCheckTest.php

# Tests complets
php artisan test

# Tests avec couverture
php artisan test --coverage
```

## 🚨 Alertes et Notifications

Le système génère des alertes pour :
- Utilisation mémoire > 80%
- Erreurs de base de données
- Échec de chargement des assets
- Permissions incorrectes

## 📞 Support et Dépannage

### Informations à collecter pour le support :
1. URL exacte de l'erreur
2. Timestamp de l'erreur
3. Navigateur et version
4. Résultat du health check (`/debug/health`)
5. Dernières lignes des logs (`/debug/logs`)
6. Configuration système (`/debug/system`)

### Commande de diagnostic complet :
```bash
curl -s http://127.0.0.1:8000/debug/health | jq . > health_report.json
curl -s http://127.0.0.1:8000/debug/system | jq . > system_info.json
curl -s http://127.0.0.1:8000/debug/logs?lines=50 | jq . > recent_logs.json
```

## 🔧 Maintenance Préventive

### Tâches quotidiennes :
- [ ] Vérifier l'espace disque
- [ ] Surveiller l'utilisation mémoire
- [ ] Analyser les logs d'erreurs
- [ ] Vérifier les performances

### Tâches hebdomadaires :
- [ ] Mettre à jour les dépendances
- [ ] Nettoyer les anciens logs
- [ ] Vérifier les backups
- [ ] Tester les points de récupération

---

**Note** : Ce guide est évolutif. Contribuez à l'améliorer en signalant les problèmes rencontrés et les solutions trouvées.
