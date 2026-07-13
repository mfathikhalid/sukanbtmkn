<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_cloudflare_forwarded_https_is_used_for_generated_form_urls(): void
    {
        $response = $this
            ->withHeaders([
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-Host' => 'sukan.example.com',
            ])
            ->get('http://localhost/login');

        $response
            ->assertOk()
            ->assertSee('action="https://sukan.example.com/login"', false);
    }
}
