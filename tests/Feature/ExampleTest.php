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

        $response
            ->assertStatus(200)
            ->assertSee('Karnival Sukan BTMKN 2026')
            ->assertSee('Daftar Acara')
            ->assertSee('Senarai Peserta')
            ->assertSee('Keputusan Live')
            ->assertSee('Jadual');
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
            ->assertSee('data-login-theme="carnival"', false)
            ->assertSee('Log Masuk ke Dashboard')
            ->assertSee('action="https://sukan.example.com/login"', false);
    }

    public function test_the_public_can_view_the_match_schedule_without_login(): void
    {
        $this->get(route('schedule.index'))
            ->assertOk()
            ->assertSee('Jadual Perlawanan')
            ->assertSeeInOrder([
                'Congkak',
                '17 Julai 2026',
                'E-Sukan',
                '21 Julai 2026',
                'Dart',
                '27 Julai 2026',
                'Karom',
                '30 Julai 2026',
                'Boling',
                '31 Julai 2026',
                'Pickleball',
                '1 Ogos 2026',
            ]);
    }
}
