<?php

namespace Tests\Feature;

use App\Filament\Resources\Chapters\Pages\EditChapter;
use App\Filament\Resources\Chapters\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\Comics\Pages\CreateComic;
use App\Filament\Resources\Comics\Pages\ListComics;
use App\Filament\Widgets\RecentChapters;
use App\Filament\Widgets\StatsOverview;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function admin(): User
    {
        return User::where('email', 'admin@shinigami.test')->firstOrFail();
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_cannot_access_panel(): void
    {
        $reader = User::factory()->create(['is_admin' => false]);

        $this->actingAs($reader)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_browse_resources(): void
    {
        $this->actingAs($this->admin());

        foreach (['/admin', '/admin/comics', '/admin/comics/create', '/admin/genres', '/admin/threads', '/admin/users', '/admin/chapters'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_create_comic_with_uploaded_cover(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin());

        Livewire::test(CreateComic::class)
            ->fillForm([
                'title' => 'Uji Upload Cover',
                'type' => 'manhwa',
                'status' => 'Ongoing',
                'rating' => 8.4,
                'genres' => 'Action, Fantasy',
                'synopsis' => 'Sinopsis uji.',
                'cover_file' => UploadedFile::fake()->image('cover.jpg', 400, 600),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $comic = Comic::where('title', 'Uji Upload Cover')->firstOrFail();

        Storage::disk('public')->assertExists($comic->cover);
        $this->assertStringContainsString('/storage/', $comic->coverUrl());
    }

    public function test_admin_can_create_comic_with_cover_url(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(CreateComic::class)
            ->fillForm([
                'title' => 'Uji Cover URL',
                'type' => 'manga',
                'status' => 'Ongoing',
                'rating' => 7.9,
                'genres' => 'Action',
                'synopsis' => 'Sinopsis uji.',
                'cover_url' => 'https://example.com/cover.jpg',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $comic = Comic::where('title', 'Uji Cover URL')->firstOrFail();

        $this->assertSame('https://example.com/cover.jpg', $comic->coverUrl());
        $this->assertSame('', $comic->coverStyle());
    }

    public function test_admin_can_upload_chapter_images(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin());
        $comic = Comic::with('chapters')->firstOrFail();
        $chapter = $comic->chapters->first();

        Livewire::test(ImagesRelationManager::class, [
            'ownerRecord' => $chapter,
            'pageClass' => EditChapter::class,
        ])
            ->callTableAction('create', data: [
                'image_path' => UploadedFile::fake()->image('p1.jpg', 800, 1200),
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseCount('chapter_images', 1);
    }

    public function test_chapter_images_accept_urls(): void
    {
        $this->actingAs($this->admin());
        $chapter = Chapter::firstOrFail();

        Livewire::test(ImagesRelationManager::class, [
            'ownerRecord' => $chapter,
            'pageClass' => EditChapter::class,
        ])
            ->callTableAction('addUrl', data: [
                'image_url' => 'https://example.com/page-01.jpg',
            ])
            ->assertHasNoActionErrors();

        $image = $chapter->images()->latest('id')->firstOrFail();

        $this->assertSame('https://example.com/page-01.jpg', $image->image_path);
        $this->assertSame('https://example.com/page-01.jpg', $image->url());
    }

    public function test_dashboard_shows_chapter_widgets(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(StatsOverview::class)
            ->assertSee('Total Chapter');

        Livewire::test(RecentChapters::class)
            ->assertSee('Chapter Terbaru')
            ->assertCanSeeTableRecords(Chapter::orderByDesc('released_at')->orderByDesc('id')->limit(5)->get());
    }

    public function test_comics_list_shows_records(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ListComics::class)
            ->assertCanSeeTableRecords(Comic::whereKey(Comic::min('id'))->get());
    }
}
