<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminLog
{
    /**
     * Log an admin action.
     */
    public static function log(string $action, ?string $modelType = null, ?int $modelId = null, ?array $payload = null): void
    {
        if (!Auth::check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'payload' => $payload,
            'ip_address' => Request::ip(),
        ]);
    }
}
