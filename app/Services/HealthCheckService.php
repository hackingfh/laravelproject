<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class HealthCheckService
{
    private array $checks = [];
    private array $errors = [];

    public function runAllChecks(): array
    {
        $this->checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'session' => $this->checkSession(),
            'routes' => $this->checkRoutes(),
            'views' => $this->checkViews(),
            'assets' => $this->checkAssets(),
            'memory' => $this->checkMemory(),
            'php_extensions' => $this->checkPhpExtensions(),
            'permissions' => $this->checkPermissions(),
        ];

        return [
            'status' => empty($this->errors) ? 'healthy' : 'unhealthy',
            'checks' => $this->checks,
            'errors' => $this->errors,
            'timestamp' => now()->toISOString(),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ];
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'ok', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            $this->errors[] = 'Database connection failed: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()];
        }
    }

    private function checkCache(): array
    {
        try {
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);

            if ($retrieved === 'test') {
                return ['status' => 'ok', 'message' => 'Cache system working'];
            } else {
                $this->errors[] = 'Cache write/read test failed';
                return ['status' => 'error', 'message' => 'Cache write/read test failed'];
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Cache error: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::put($testFile, 'test');
            $exists = Storage::exists($testFile);
            $content = Storage::get($testFile);
            Storage::delete($testFile);

            if ($exists && $content === 'test') {
                return ['status' => 'ok', 'message' => 'Storage system working'];
            } else {
                $this->errors[] = 'Storage write/read test failed';
                return ['status' => 'error', 'message' => 'Storage write/read test failed'];
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Storage error: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkSession(): array
    {
        try {
            session()->put('health_check', 'test');
            $retrieved = session()->get('health_check');
            session()->forget('health_check');

            if ($retrieved === 'test') {
                return ['status' => 'ok', 'message' => 'Session system working'];
            } else {
                $this->errors[] = 'Session test failed';
                return ['status' => 'error', 'message' => 'Session test failed'];
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Session error: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkRoutes(): array
    {
        try {
            $routes = app('router')->getRoutes();
            $count = count($routes);

            if ($count > 0) {
                return ['status' => 'ok', 'message' => "Routes loaded: {$count} routes found"];
            } else {
                $this->errors[] = 'No routes found';
                return ['status' => 'error', 'message' => 'No routes found'];
            }
        } catch (\Exception $e) {
            $this->errors[] = 'Routes error: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkViews(): array
    {
        try {
            $view = view('errors.debug');
            $rendered = $view->render();

            if (strlen($rendered) > 0) {
                return ['status' => 'ok', 'message' => 'View system working'];
            } else {
                $this->errors[] = 'View rendering failed';
                return ['status' => 'error', 'message' => 'View rendering failed'];
            }
        } catch (\Exception $e) {
            $this->errors[] = 'View error: ' . $e->getMessage();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkAssets(): array
    {
        $publicPath = public_path();
        $checks = [];

        // Vérifier les dossiers essentiels
        $essentialDirs = ['css', 'js', 'images'];
        foreach ($essentialDirs as $dir) {
            $dirPath = $publicPath . '/' . $dir;
            if (is_dir($dirPath)) {
                $checks[] = "$dir: exists";
            } else {
                $checks[] = "$dir: missing";
                $this->errors[] = "Missing assets directory: $dir";
            }
        }

        if (count(array_filter($checks, fn($check) => str_contains($check, 'missing')))) {
            return ['status' => 'error', 'message' => 'Missing asset directories', 'details' => $checks];
        } else {
            return ['status' => 'ok', 'message' => 'Asset directories present', 'details' => $checks];
        }
    }

    private function checkMemory(): array
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        $usagePercent = ($memoryUsage / $memoryLimit) * 100;

        if ($usagePercent < 80) {
            return ['status' => 'ok', 'message' => "Memory usage: " . round($usagePercent, 2) . "%"];
        } else {
            $this->errors[] = 'High memory usage: ' . round($usagePercent, 2) . '%';
            return ['status' => 'warning', 'message' => "High memory usage: " . round($usagePercent, 2) . "%"];
        }
    }

    private function checkPhpExtensions(): array
    {
        $required = ['mbstring', 'openssl', 'pdo', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo'];
        $missing = [];

        foreach ($required as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        if (empty($missing)) {
            return ['status' => 'ok', 'message' => 'All required PHP extensions are loaded'];
        } else {
            $this->errors[] = 'Missing PHP extensions: ' . implode(', ', $missing);
            return ['status' => 'error', 'message' => 'Missing extensions: ' . implode(', ', $missing)];
        }
    }

    private function checkPermissions(): array
    {
        $paths = [
            storage_path(),
            base_path('bootstrap/cache'),
            public_path(),
        ];

        $issues = [];
        foreach ($paths as $path) {
            if (!is_writable($path)) {
                $issues[] = $path;
            }
        }

        if (empty($issues)) {
            return ['status' => 'ok', 'message' => 'All critical paths are writable'];
        } else {
            $this->errors[] = 'Permission issues with: ' . implode(', ', $issues);
            return ['status' => 'error', 'message' => 'Permission issues', 'paths' => $issues];
        }
    }

    private function parseMemoryLimit(string $limit): int
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    public function createRecoveryPoint(): string
    {
        $point = [
            'timestamp' => now()->toISOString(),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => config('app.env'),
            'debug_mode' => config('app.debug'),
            'database_config' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
            ],
            'cache_config' => [
                'default' => config('cache.default'),
                'store' => config('cache.stores.' . config('cache.default')),
            ],
            'loaded_extensions' => get_loaded_extensions(),
            'server_vars' => [
                'REQUEST_TIME' => $_SERVER['REQUEST_TIME'] ?? null,
                'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? null,
                'SCRIPT_FILENAME' => $_SERVER['SCRIPT_FILENAME'] ?? null,
            ]
        ];

        $pointId = 'recovery_' . time();
        Cache::put($pointId, $point, 3600);

        return $pointId;
    }

    public function restoreFromRecoveryPoint(string $pointId): ?array
    {
        $point = Cache::get($pointId);
        if (!$point) {
            return null;
        }

        Log::info('Restoring from recovery point', ['point_id' => $pointId, 'point' => $point]);

        return $point;
    }
}
