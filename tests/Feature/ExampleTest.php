<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_redirects_to_login_when_not_authenticated(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_laporan_export_requires_authentication(): void
    {
        $response = $this->get(route('laporan.export', ['from' => now()->format('Y-m-d'), 'to' => now()->format('Y-m-d')]));

        $response->assertRedirect(route('login'));
    }
}
