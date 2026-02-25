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
        $options = [];

        // On Windows local dev, SSL certificates are often missing
        // This is a workaround for the 'SSL certificate problem' error
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
            $httpClient = new \Twilio\Http\CurlClient($curlOptions);
            $options['httpClient'] = $httpClient;
        }

        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token'),
            null, // accountSid null to use sid
            null, // region
            $options['httpClient'] ?? null
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

            $from = config('services.twilio.whatsapp_from');
            if (!str_starts_with($from, 'whatsapp:')) {
                $from = 'whatsapp:' . $from;
            }

            $this->client->messages->create(
                $phoneNumber,
                [
                    'from' => $from,
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

        // Remove all characters except digits and the plus sign
        $phone = preg_replace('/[^+0-9]/', '', $phone);

        // Handle 00 prefix (convert to +)
        if (str_starts_with($phone, '00')) {
            $phone = '+' . substr($phone, 2);
        }

        // If it already starts with +, we assume it's a valid international number
        if (str_starts_with($phone, '+')) {
            return (strlen($phone) > 5) ? 'whatsapp:' . $phone : null;
        }

        // Default country code (Mauritania = 222)
        $defaultPrefix = config('services.twilio.default_country_code', '222');

        if (str_starts_with($phone, '0')) {
            // If it starts with 0, it's likely a local format (e.g., 06... in FR, or 0... in other countries)
            // We strip the 0 and add the default country prefix
            $phone = '+' . $defaultPrefix . substr($phone, 1);
        } else {
            // Check if it already starts with the default prefix but lacks the +
            if (str_starts_with($phone, $defaultPrefix)) {
                $phone = '+' . $phone;
            } else {
                // It's likely a local number without a prefix
                $phone = '+' . $defaultPrefix . $phone;
            }
        }

        return 'whatsapp:' . $phone;
    }

    private function translateStatus(string $status): string
    {
        return match ($status) {
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
        return match ($status) {
            'pending' => 'En attente',
            'paid' => 'Payée',
            'failed' => 'Échouée',
            'refunded' => 'Remboursée',
            default => ucfirst($status)
        };
    }
}
