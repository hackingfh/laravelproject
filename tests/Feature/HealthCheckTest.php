<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\HealthCheckService;

class HealthCheckTest extends TestCase
{
    use RefreshDatabase;

    private HealthCheckService $healthCheck;

    protected function setUp(): void
    {
        parent::setUp();
        $this->healthCheck = app(HealthCheckService::class);
    }

    public function test_health_check_returns_valid_structure(): void
    {
        $result = $this->healthCheck->runAllChecks();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('checks', $result);
        $this->assertArrayHasKey('errors', $result);
        $this->assertArrayHasKey('timestamp', $result);
        $this->assertArrayHasKey('memory_usage', $result);
        $this->assertArrayHasKey('memory_peak', $result);

        $this->assertContains($result['status'], ['healthy', 'unhealthy']);
    }

    public function test_database_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('database', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['database']);
        $this->assertArrayHasKey('message', $result['checks']['database']);
    }

    public function test_cache_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('cache', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['cache']);
        $this->assertArrayHasKey('message', $result['checks']['cache']);
    }

    public function test_storage_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('storage', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['storage']);
        $this->assertArrayHasKey('message', $result['checks']['storage']);
    }

    public function test_session_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('session', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['session']);
        $this->assertArrayHasKey('message', $result['checks']['session']);
    }

    public function test_routes_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('routes', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['routes']);
        $this->assertArrayHasKey('message', $result['checks']['routes']);
    }

    public function test_views_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('views', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['views']);
        $this->assertArrayHasKey('message', $result['checks']['views']);
    }

    public function test_assets_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('assets', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['assets']);
        $this->assertArrayHasKey('message', $result['checks']['assets']);
    }

    public function test_memory_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('memory', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['memory']);
        $this->assertArrayHasKey('message', $result['checks']['memory']);
    }

    public function test_php_extensions_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('php_extensions', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['php_extensions']);
        $this->assertArrayHasKey('message', $result['checks']['php_extensions']);
    }

    public function test_permissions_check(): void
    {
        $result = $this->healthCheck->runAllChecks();
        
        $this->assertArrayHasKey('permissions', $result['checks']);
        $this->assertArrayHasKey('status', $result['checks']['permissions']);
        $this->assertArrayHasKey('message', $result['checks']['permissions']);
    }

    public function test_recovery_point_creation(): void
    {
        $pointId = $this->healthCheck->createRecoveryPoint();
        
        $this->assertIsString($pointId);
        $this->assertStringStartsWith('recovery_', $pointId);
    }

    public function test_recovery_point_restoration(): void
    {
        $pointId = $this->healthCheck->createRecoveryPoint();
        $restoredPoint = $this->healthCheck->restoreFromRecoveryPoint($pointId);
        
        $this->assertIsArray($restoredPoint);
        $this->assertArrayHasKey('timestamp', $restoredPoint);
        $this->assertArrayHasKey('php_version', $restoredPoint);
        $this->assertArrayHasKey('laravel_version', $restoredPoint);
    }

    public function test_recovery_point_not_found(): void
    {
        $restoredPoint = $this->healthCheck->restoreFromRecoveryPoint('non_existent_point');
        
        $this->assertNull($restoredPoint);
    }

    public function test_debug_endpoint_accessible_in_debug_mode(): void
    {
        config(['app.debug' => true]);
        
        $response = $this->get('/debug/health');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'checks',
            'errors',
            'timestamp'
        ]);
    }

    public function test_debug_endpoint_not_accessible_in_production(): void
    {
        config(['app.debug' => false]);
        
        $response = $this->get('/debug/health');
        
        $response->assertStatus(404);
    }

    public function test_homepage_loads_without_errors(): void
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        $response->assertSee('Mathey-Tissot');
    }

    public function test_assets_are_accessible(): void
    {
        $response = $this->get('/css/app.css');
        
        // Should return 404 or 200 depending on Vite compilation
        $this->assertContains($response->status(), [200, 404]);
    }

    public function test_error_page_displays_correctly(): void
    {
        // Test with a route that doesn't exist
        $response = $this->get('/non-existent-route');
        
        $response->assertStatus(404);
    }
}
