<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_root_redirects_to_catalogue(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('catalogue.index'));
    }

    public function test_catalogue_page_loads(): void
    {
        $response = $this->get('/catalogue');

        $response->assertStatus(200);
    }

    public function test_catalogue_show_page_loads_for_published_memoire(): void
    {
        $memoire = \App\Models\Memoire::visibles()->first();

        if ($memoire) {
            $response = $this->get(route('catalogue.show', $memoire));
            $response->assertStatus(200);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'etudiant@eneam.bj',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_register_page_loads(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_dashboard_with_authenticated_user(): void
    {
        $user = \App\Models\User::first();
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }
}
