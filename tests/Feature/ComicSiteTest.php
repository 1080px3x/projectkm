<?php

namespace Tests\Feature;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_public_pages_render(): void
    {
        foreach (['/', '/komik', '/komik?q=shadow&type=project', '/komunitas',
            '/leaderboard', '/bantuan', '/masuk', '/daftar'] as $url) {
            $this->get($url)->assertOk();
        }

        $comic = Comic::with('chapters')->firstOrFail();
        $this->get(route('comics.show', $comic->slug))->assertOk();
        $this->get(route('chapters.show', [$comic->slug, $comic->chapters->first()->number]))->assertOk();
    }

    public function test_guest_is_redirected_from_private_pages(): void
    {
        foreach (['/pustaka', '/profil', '/pengaturan'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_bookmark_toggle_round_trip(): void
    {
        $user = User::factory()->create();
        $comic = Comic::firstOrFail();

        $this->actingAs($user)->post(route('library.store', $comic))->assertRedirect();
        $this->get(route('library.index'))->assertSee($comic->title);

        $this->actingAs($user)->post(route('library.store', $comic))->assertRedirect();
        $this->get(route('library.index'))->assertSee('Pustaka masih kosong');
    }
}
