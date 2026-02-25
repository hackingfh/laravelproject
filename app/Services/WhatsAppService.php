<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class WhatsAppService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendOrderConfirmation(Order $order): bool
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($order->user->phone);
            
            if (!$phoneNumber) {
                Log::warning('Numéro de téléphone invalide pour WhatsApp', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'phone' => $order->user->phone
                ]);
                return false;
            }

            $message = $this->buildOrderConfirmationMessage($order);
            
            $this->client->messages->create(
                $phoneNumber,
                [
                    'from' => 'whatsapp:' . config('services.twilio.whatsapp_from'),
                    'body' => $message
                ]
            );

            Log::info('Message WhatsApp envoyé avec succès', [
                'order_id' => $order->id,
                'phone_number' => $phoneNumber
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message WhatsApp', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function buildOrderConfirmationMessage(Order $order): string
    {
        $message = "🎉 *Confirmation de commande*\n\n";
        $message .= "Bonjour {$order->user->name},\n\n";
        $message .= "Votre commande *{$order->order_number}* a été confirmée !\n\n";
        
        $message .= "*Détails de la commande:*\n";
        $message .= "📦 Numéro: {$order->order_number}\n";
        $message .= "💰 Total: " . number_format($order->total, 2, ',', ' ') . " €\n";
        $message .= "📍 Statut: " . $this->translateStatus($order->status) . "\n";
        $message .= "💳 Paiement: " . $this->translatePaymentStatus($order->payment_status) . "\n\n";

        $message .= "*Articles commandés:*\n";
        foreach ($order->items as $index => $item) {
            $message .= ($index + 1) . ". {$item->product_snapshot['name']}\n";
            $message .= "   Quantité: {$item->quantity} | ";
            $message .= "Prix: " . number_format($item->price_at_purchase, 2, ',', ' ') . " €\n";
        }

        if ($order->tracking_number) {
            $message .= "\n🚚 *Suivi colis:* {$order->tracking_number}\n";
        }

        $message .= "\nMerci pour votre confiance ! 🛍️";
        $message .= "\n\n*L'équipe de votre boutique*";

        return $message;
    }

    private function formatPhoneNumber(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Supprimer tous les caractères non numériques
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ajouter le préfixe international si nécessaire
        if (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            $phone = '33' . substr($phone, 1); // France
        } elseif (strlen($phone) === 9 && !str_starts_with($phone, '0')) {
            $phone = '33' . $phone; // France sans le 0 initial
        }

        return 'whatsapp:' . $phone;
    }

    private function translateStatus(string $status): string
    {
        return match($status) {
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($status)
        };
    }

    private function translatePaymentStatus(string $status): string
    {
        return match($status) {
            'pending' => 'En attente',
            'paid' => 'Payée',
            'failed' => 'Échouée',
            'refunded' => 'Remboursée',
            default => ucfirst($status)
        };
    }
}
