<?php

use App\Models\Admin;

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

    $this->admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
});

test('removed customer management routes return 404', function () {
    // Attempting to visit user index
    $response = $this->actingAs($this->admin, 'admin')
        ->get('/admin/users');
    $response->assertStatus(404);

    // Attempting to visit user create
    $responseCreate = $this->actingAs($this->admin, 'admin')
        ->get('/admin/users/create');
    $responseCreate->assertStatus(404);
});

test('removed blog routes return 404', function () {
    // Frontend blog list
    $responseFrontend = $this->get('/blog');
    $responseFrontend->assertStatus(404);

    // Backend blog posts index
    $responseBackend = $this->actingAs($this->admin, 'admin')
        ->get('/admin/blog-posts');
    $responseBackend->assertStatus(404);
});
