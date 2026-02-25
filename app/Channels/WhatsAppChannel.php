<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $to = $notifiable->routeNotificationFor('whatsapp');

        if (!$to) {
            return;
        }

        // INTEGRATION LOGIC HERE
        // Example: Call Twilio, Hypersender, or a local service
        Log::info("WhatsApp message sent to {$to}: {$message}");

        // In a real scenario, you'd use something like:
        // Http::post(config('services.whatsapp.url'), [
        //     'to' => $to,
        //     'message' => $message,
        //     'apikey' => config('services.whatsapp.key')
        // ]);
    }
}
