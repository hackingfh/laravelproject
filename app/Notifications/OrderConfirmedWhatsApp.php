<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\WhatsAppChannel;

class OrderConfirmedWhatsApp extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $orderNumber = $this->order->order_number;
        $total = number_format((float) $this->order->total, 2);

        $message = "🌟 *Merci pour votre commande Mathey-Tissot !*\n\n";
        $message .= "Bonjour {$notifiable->name},\n\n";
        $message .= "Votre commande *#{$orderNumber}* a été confirmée avec succès.\n";
        $message .= "Montant Total : *{$total} €*\n\n";
        $message .= "Nous préparons vos garde-temps Swiss watches - Mauritania. Vous recevrez un nouveau message dès l'expédition.\n\n";
        $message .= "À bientôt,\nL'équipe Mathey-Tissot ⌚";

        return $message;
    }
}
