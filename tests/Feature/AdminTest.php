<?php

namespace Tests\Feature;

use App\Models\Comic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_non_admin_cannot_log_in_to_panel(): void
    {
        User::factory()->create(['email' => 'reader@example.com', 'password' => 'secret123']);

        $this->post('/admin/login', ['email' => 'reader@example.com', 'password' => 'secret123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_log_in_and_browse_resources(): void
    {
        $this->post('/admin/login', ['email' => 'admin@shinigami.test', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();

        foreach (['/admin', '/admin/comics', '/admin/comics/create', '/admin/genres', '/admin/threads'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_create_comic_with_uploaded_cover(): void
    {
        Storage::fake('public');

        $this->actingAs(User::where('email', 'admin@shinigami.test')->firstOrFail());

        $this->post('/admin/comics', [
            'title' => 'Uji Upload Cover',
            'type' => 'manhwa',
            'status' => 'Ongoing',
            'rating' => 8.4,
            'genres' => 'Action, Fantasy',
            'synopsis' => 'Sinopsis uji.',
            'cover_file' => UploadedFile::fake()->image('cover.jpg', 400, 600),
        ])->assertRedirect(route('admin.comics.index'));

        $comic = Comic::where('title', 'Uji Upload Cover')->firstOrFail();

        Storage::disk('public')->assertExists($comic->cover);
        $this->assertStringContainsString('/storage/', $comic->coverUrl());
    }

    public function test_admin_can_create_comic_with_cover_url(): void
    {
        $this->actingAs(User::where('email', 'admin@shinigami.test')->firstOrFail());

        $this->post('/admin/comics', [
            'title' => 'Uji Cover URL',
            'type' => 'manga',
            'status' => 'Ongoing',
            'rating' => 7.9,
            'genres' => 'Action',
            'synopsis' => 'Sinopsis uji.',
            'cover_url' => 'https://example.com/cover.jpg',
        ])->assertRedirect(route('admin.comics.index'));

        $comic = Comic::where('title', 'Uji Cover URL')->firstOrFail();

        $this->assertSame('https://example.com/cover.jpg', $comic->coverUrl());
        $this->assertSame('', $comic->coverStyle());
    }

    public function test_admin_can_add_and_delete_chapter(): void
    {
        $this->actingAs(User::where('email', 'admin@shinigami.test')->firstOrFail());
        $comic = Comic::firstOrFail();

        $this->post("/admin/comics/{$comic->id}/chapters", [
            'number' => 99,
            'title' => 'Chapter Uji',
            'released_at' => now()->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $chapter = $comic->chapters()->where('number', 99)->firstOrFail();

        $this->delete("/admin/chapters/{$chapter->id}")->assertRedirect();
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }

    public function test_admin_can_upload_chapter_images(): void
    {
        Storage::fake('public');

        $this->actingAs(User::where('email', 'admin@shinigami.test')->firstOrFail());
        $comic = Comic::with('chapters')->firstOrFail();
        $chapter = $comic->chapters->first();

        $this->post(route('admin.chapters.images.store', $chapter), [
            'images' => [
                UploadedFile::fake()->image('p1.jpg', 800, 1200),
                UploadedFile::fake()->image('p2.jpg', 800, 1200),
            ],
        ])->assertRedirect();

        $this->assertDatabaseCount('chapter_images', 2);
        $this->assertSame(2, $chapter->images()->count());
        $this->get(route('admin.chapters.images', $chapter))->assertOk();
    }
}
