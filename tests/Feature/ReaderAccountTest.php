<?php

namespace Tests\Feature;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ReaderAccountTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_reader_can_register(): void
    {
        $this->post('/daftar', [
            'name' => 'Pembaca Baru',
            'email' => 'pembaca@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'pembaca@example.com']);
    }

    public function test_reader_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'reader@example.com', 'password' => 'secret123']);

        $this->post('/masuk', ['email' => 'reader@example.com', 'password' => 'secret123'])
            ->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

        $this->post('/keluar')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_reader_can_rate_and_history_is_recorded(): void
    {
        $user = User::factory()->create();
        $comic = Comic::with('chapters')->firstOrFail();
        $chapter = $comic->chapters->first();

        $this->actingAs($user)->post(route('comics.rate', $comic), ['rating' => 9])
            ->assertRedirect();
        $this->assertDatabaseHas('ratings', ['user_id' => $user->id, 'comic_id' => $comic->id]);

        $this->actingAs($user)->get(route('chapters.show', [$comic->slug, $chapter->number]))->assertOk();
        $this->assertDatabaseHas('reading_histories', [
            'user_id' => $user->id,
            'comic_id' => $comic->id,
            'chapter_id' => $chapter->id,
        ]);
    }

    public function test_reader_can_update_profile_and_password(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nama Baru', 'email' => 'baru@example.com']);

        $this->actingAs($user)->put(route('settings.password'), [
            'current_password' => 'password',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertRedirect();
        $this->assertTrue(Hash::check('passwordbaru', $user->fresh()->password));
    }
}
