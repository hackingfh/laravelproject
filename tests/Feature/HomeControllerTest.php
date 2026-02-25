<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /**
     * Test that the home page loads successfully.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the home page returns JSON response.
     */
    public function test_home_page_returns_json_response(): void
    {
        $response = $this->get('/');

        $response->assertJson([
            'status' => 'success',
            'message' => 'Mathey-Tissot API is working'
        ]);
    }

    /**
     * Test that the home page contains required fields.
     */
    public function test_home_page_contains_required_fields(): void
    {
        $response = $this->get('/');

        $response->assertJsonStructure([
            'status',
            'message',
            'timestamp',
            'version',
            'environment',
            'debug'
        ]);
    }

    /**
     * Test that the API response is valid JSON.
     */
    public function test_home_page_returns_valid_json(): void
    {
        $response = $this->get('/');

        $response->assertHeader('content-type', 'application/json');
        $data = $response->json();
        
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Mathey-Tissot API is working', $data['message']);
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertArrayHasKey('environment', $data);
        $this->assertArrayHasKey('debug', $data);
    }
}
