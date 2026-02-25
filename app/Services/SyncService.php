<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SyncService
{
    public static function touch()
    {
        $data = [
            'timestamp' => now()->timestamp,
            'action' => 'updated'
        ];

        Storage::disk('public')->put('sync.json', json_encode($data));
    }

    public static function getLastSync()
    {
        if (Storage::disk('public')->exists('sync.json')) {
            $content = Storage::disk('public')->get('sync.json');
            return json_decode($content, true);
        }

        return null;
    }
}
