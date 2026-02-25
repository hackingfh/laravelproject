<?php

namespace App\Http\Controllers;

use App\Services\HealthCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    public function __construct(
        private HealthCheckService $healthCheck
    ) {}

    public function index()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $health = $this->healthCheck->runAllChecks();
        
        return response()->json($health);
    }

    public function createRecoveryPoint(Request $request)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $pointId = $this->healthCheck->createRecoveryPoint();
        
        return response()->json([
            'success' => true,
            'point_id' => $pointId,
            'message' => 'Recovery point created successfully'
        ]);
    }

    public function restoreRecoveryPoint(Request $request, string $pointId)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $point = $this->healthCheck->restoreFromRecoveryPoint($pointId);
        
        if (!$point) {
            return response()->json([
                'success' => false,
                'message' => 'Recovery point not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'point' => $point,
            'message' => 'Recovery point restored successfully'
        ]);
    }

    public function logs(Request $request)
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return response()->json(['logs' => []]);
        }

        $lines = $request->get('lines', 100);
        $content = file_get_contents($logFile);
        $logLines = explode("\n", $content);
        
        $recentLines = array_slice($logLines, -$lines);
        
        $parsedLogs = [];
        foreach ($recentLines as $line) {
            if (empty(trim($line))) continue;
            
            $parsedLogs[] = [
                'raw' => $line,
                'timestamp' => $this->extractTimestamp($line),
                'level' => $this->extractLogLevel($line),
                'message' => $this->extractLogMessage($line)
            ];
        }

        return response()->json([
            'logs' => array_reverse($parsedLogs),
            'total_lines' => count($logLines),
            'showing' => count($parsedLogs)
        ]);
    }

    public function clearLogs()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }

        return response()->json([
            'success' => true,
            'message' => 'Logs cleared successfully'
        ]);
    }

    public function systemInfo()
    {
        if (!config('app.debug')) {
            abort(404);
        }

        $info = [
            'php' => [
                'version' => PHP_VERSION,
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'extensions' => get_loaded_extensions(),
            ],
            'laravel' => [
                'version' => app()->version(),
                'environment' => config('app.env'),
                'debug' => config('app.debug'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
            ],
            'server' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
                'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'Unknown',
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
                'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
                'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            ],
            'database' => [
                'default' => config('database.default'),
                'connections' => array_keys(config('database.connections')),
            ],
            'cache' => [
                'default' => config('cache.default'),
                'stores' => array_keys(config('cache.stores')),
            ],
            'session' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
                'files' => config('session.files'),
            ],
            'filesystems' => [
                'default' => config('filesystems.default'),
                'cloud' => config('filesystems.cloud'),
            ],
            'memory' => [
                'current_usage' => memory_get_usage(true),
                'peak_usage' => memory_get_peak_usage(true),
                'current_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak_usage_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            ]
        ];

        return response()->json($info);
    }

    private function extractTimestamp(string $line): ?string
    {
        if (preg_match('/^\[(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}\+\d{2}:\d{2})\]/', $line, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function extractLogLevel(string $line): ?string
    {
        if (preg_match('/\.(ERROR|WARNING|INFO|DEBUG|CRITICAL|ALERT|EMERGENCY)/', $line, $matches)) {
            return strtolower($matches[1]);
        }
        return null;
    }

    private function extractLogMessage(string $line): string
    {
        if (preg_match('/\.(ERROR|WARNING|INFO|DEBUG|CRITICAL|ALERT|EMERGENCY):\s*(.*)/', $line, $matches)) {
            return $matches[1];
        }
        return $line;
    }
}
