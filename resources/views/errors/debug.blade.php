<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erreur de débogage - Mathey-Tissot</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1A2B3C 0%, #2C3E50 100%);
            color: #ffffff;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #C5A059;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #6B6B6B;
            font-size: 1.1rem;
        }
        
        .error-section {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .error-title {
            color: #ff6b6b;
            font-size: 1.5rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-icon {
            width: 30px;
            height: 30px;
            background: #ff6b6b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .code-block {
            background: #2d3748;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            overflow-x: auto;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-card {
            background: rgba(197, 160, 89, 0.1);
            border: 1px solid #C5A059;
            border-radius: 8px;
            padding: 20px;
        }
        
        .info-title {
            color: #C5A059;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .info-content {
            color: #e2e8f0;
            line-height: 1.6;
        }
        
        .trace-line {
            display: block;
            padding: 5px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .trace-line:last-child {
            border-bottom: none;
        }
        
        .file-path {
            color: #4299e1;
            font-weight: bold;
        }
        
        .line-number {
            color: #f6ad55;
            background: rgba(246, 173, 85, 0.2);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn {
            background: #C5A059;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: #b8944a;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: transparent;
            border: 2px solid #C5A059;
        }
        
        .btn-secondary:hover {
            background: #C5A059;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .status-error { background: #ff6b6b; }
        .status-warning { background: #f6ad55; }
        .status-info { background: #4299e1; }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .error-section {
                padding: 20px;
            }
            
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Mathey-Tissot Debug</div>
            <div class="subtitle">Mode de débogage - Erreur détectée</div>
        </div>

        <div class="error-section">
            <div class="error-title">
                <div class="error-icon">!</div>
                {{ $error['type'] }}
            </div>
            <div class="code-block">
                <strong>Message:</strong> {{ $error['message'] }}<br>
                <strong>Fichier:</strong> <span class="file-path">{{ $error['file'] }}</span><br>
                <strong>Ligne:</strong> <span class="line-number">{{ $error['line'] }}</span>
            </div>
        </div>

        <div class="grid">
            <div class="info-card">
                <div class="info-title">
                    <span class="status-indicator status-info"></span>
                    Informations de la requête
                </div>
                <div class="info-content">
                    <p><strong>URL:</strong> {{ $request['url'] }}</p>
                    <p><strong>Méthode:</strong> {{ $request['method'] }}</p>
                    <p><strong>IP:</strong> {{ request()->ip() }}</p>
                    <p><strong>Timestamp:</strong> {{ $server['timestamp'] }}</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-title">
                    <span class="status-indicator status-warning"></span>
                    Environnement
                </div>
                <div class="info-content">
                    <p><strong>PHP:</strong> {{ $server['php_version'] }}</p>
                    <p><strong>Laravel:</strong> {{ $server['laravel_version'] }}</p>
                    <p><strong>Environment:</strong> {{ $server['environment'] }}</p>
                    <p><strong>Mémoire:</strong> {{ round($server['memory_usage'] / 1024 / 1024, 2) }} MB</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-title">
                    <span class="status-indicator status-error"></span>
                    Performance
                </div>
                <div class="info-content">
                    <p><strong>Mémoire peak:</strong> {{ round($server['memory_peak'] / 1024 / 1024, 2) }} MB</p>
                    <p><strong>Session ID:</strong> {{ substr(session()->getId(), 0, 8) }}...</p>
                    <p><strong>Debug Mode:</strong> {{ config('app.debug') ? 'ON' : 'OFF' }}</p>
                </div>
            </div>
        </div>

        @if(isset($error['trace']) && count($error['trace']) > 0)
        <div class="error-section">
            <div class="error-title">Stack Trace</div>
            <div class="code-block">
                @foreach($error['trace'] as $index => $trace)
                    @if(isset($trace['file']) && isset($trace['line']))
                        <div class="trace-line">
                            <strong>#{{ $index }}</strong> 
                            {{ $trace['file'] }}:<span class="line-number">{{ $trace['line'] }}</span>
                            @if(isset($trace['function']))
                                → {{ $trace['function'] }}()
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <div class="actions">
            <a href="{{ url('/') }}" class="btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Retour à l'accueil
            </a>
            <button onclick="location.reload()" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Rafraîchir
            </button>
            <button onclick="copyDebugInfo()" class="btn btn-secondary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Copier infos debug
            </button>
        </div>
    </div>

    <script>
        function copyDebugInfo() {
            const debugInfo = {
                error: @json($error),
                request: @json($request),
                server: @json($server),
                timestamp: new Date().toISOString()
            };
            
            navigator.clipboard.writeText(JSON.stringify(debugInfo, null, 2))
                .then(() => {
                    alert('Informations de debug copiées dans le presse-papiers');
                })
                .catch(err => {
                    console.error('Erreur lors de la copie:', err);
                });
        }

        // Auto-refresh en cas d'erreur critique
        setTimeout(() => {
            if (confirm('L\'erreur persiste. Voulez-vous rafraîchir la page?')) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
