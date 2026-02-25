# Intégration WhatsApp pour les confirmations de commande

## Configuration

### 1. Variables d'environnement

Ajoutez les variables suivantes à votre fichier `.env` :

```env
# Twilio WhatsApp
TWILIO_SID=votre_sid_twilio
TWILIO_TOKEN=votre_token_twilio
TWILIO_WHATSAPP_FROM=+14155238886  # Numéro WhatsApp Twilio de test
```

### 2. Obtenir les identifiants Twilio

1. Créez un compte sur [Twilio](https://www.twilio.com/)
2. Allez dans votre Console Twilio
3. Récupérez votre **Account SID** et **Auth Token**
4. Activez le sandbox WhatsApp pour les tests

### 3. Configuration du numéro WhatsApp

Pour la production, vous devrez :
- Demander un numéro WhatsApp Business
- Faire approuver vos templates de messages
- Configurer votre webhook

## Fonctionnalités

### Service WhatsApp

Le `WhatsAppService` gère :
- L'envoi de messages de confirmation de commande
- Le formatage des numéros de téléphone
- La gestion des erreurs
- Les templates de messages

### Message de confirmation

Le message inclut :
- 🎉 Titre de confirmation
- Numéro de commande
- Montant total
- Statut de la commande et du paiement
- Liste des articles commandés
- Numéro de suivi (si disponible)

## Utilisation

### Dans le contrôleur

```php
use App\Services\WhatsAppService;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly WhatsAppService $whatsapp
    ) {}

    public function store(StoreOrderRequest $request)
    {
        // ... création de la commande
        
        // Envoi WhatsApp
        $this->whatsapp->sendOrderConfirmation($order);
        
        return response()->json(['order' => $order]);
    }
}
```

### Format des numéros

Le service gère automatiquement :
- Les numéros français (06/07 → +336/+337)
- Les formats internationaux
- La validation des numéros

## Tests

### Test avec le sandbox Twilio

1. Activez le sandbox WhatsApp dans votre console Twilio
2. Envoyez "join" au numéro de sandbox depuis votre WhatsApp
3. Testez avec des commandes réelles

### Logs

Les messages sont loggés :
- Succès : `INFO` - Message envoyé avec succès
- Erreurs : `ERROR` - Problèmes d'envoi
- Avertissements : `WARNING` - Numéros invalides

## Dépannage

### Problèmes courants

1. **Numéro invalide** : Vérifiez le format du numéro dans la base de données
2. **Token Twilio expiré** : Régénérez votre Auth Token
3. **Sandbox non activé** : Activez le sandbox WhatsApp pour les tests
4. **Template non approuvé** : Pour la production, faites approuver vos templates

### Erreurs types

- `21614` : Numéro WhatsApp non valide
- `21612` : Sandbox non joint
- `30001` : Message envoyé avec succès
- `30002` : Message en cours d'envoi

## Production

Pour passer en production :

1. Obtenez un numéro WhatsApp Business approuvé
2. Faites approuver vos templates de messages
3. Configurez votre webhook pour les réponses
4. Mettez à jour les variables d'environnement
5. Testez avec des vrais numéros

## Sécurité

- Ne jamais exposer vos tokens Twilio
- Validez les numéros avant l'envoi
- Utilisez HTTPS pour votre webhook
- Limitez les tentatives d'envoi
