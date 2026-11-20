<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Genre;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@shinigami.test'],
            ['name' => 'Admin', 'password' => 'password', 'is_admin' => true]
        );

        $genres = ['Action', 'Adventure', 'Fantasy', 'Martial Arts', 'Drama', 'Supernatural', 'Romance', 'Comedy', 'Sci-Fi', 'Mystery'];

        foreach ($genres as $name) {
            Genre::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);
        }

        $gradients = [
            'linear-gradient(135deg,#4c1d95,#be123c)',
            'linear-gradient(135deg,#0f766e,#0ea5e9)',
            'linear-gradient(135deg,#7c2d12,#f59e0b)',
            'linear-gradient(135deg,#1e3a8a,#9333ea)',
            'linear-gradient(135deg,#7f1d1d,#f43f5e)',
            'linear-gradient(135deg,#065f46,#84cc16)',
            'linear-gradient(135deg,#3b0764,#db2777)',
            'linear-gradient(135deg,#1f2937,#6b7280)',
        ];

        $data = [
            ['Shadow Slave', 'project', 9.7, 'Action, Fantasy, Supernatural', 'Seorang pemuda terjebak dalam mimpi buruk yang menjadi kenyataan dan harus bertahan hidup di dunia bayangan.'],
            ['Solo Leveling: Ragnarok', 'project', 9.3, 'Action, Adventure, Fantasy', 'Kisah lanjutan para hunter setelah gerbang terakhir terbuka dan ancaman baru muncul dari dunia lain.'],
            ['SSS-Class Suicide Hunter', 'project', 8.9, 'Action, Drama, Fantasy', 'Pemburu tingkat terendah mendapat kemampuan kembali setelah kematian dan menantang menara yang tak berujung.'],
            ["The Heavenly Demon Can't Live A Normal Life", 'project', 8.6, 'Action, Martial Arts, Drama', 'Iblis surgawi bereinkarnasi di dunia modern dan berusaha hidup biasa tanpa mengeluarkan kekuatannya.'],
            ['Reincarnation Of The Fist King', 'project', 8.5, 'Action, Martial Arts, Fantasy', 'Raja petinju legendaris terlahir kembali di dunia persilatan dan mengejar puncak kekuatan baru.'],
            ['The World After The Fall', 'project', 8.8, 'Action, Adventure, Mystery', 'Setelah menara runtuh, seorang penyelamat bangkit untuk mencari kebenaran di balik kehancuran dunia.'],
            ['Pick Me Up', 'project', 9.1, 'Action, Comedy, Fantasy', 'Seorang pemain game terlempar ke dalam permainan dan harus bertahan sebagai karakter yang ia pilih.'],
            ["The Academy's Weapon Replicator", 'project', 8.2, 'Action, School, Fantasy', 'Siswa akademi dengan kemampuan meniru senjata legendaris berjuang menutupi identitas aslinya.'],
            ['The Return of The Disaster Class Hero', 'project', 8.4, 'Action, Adventure, Supernatural', 'Pahlawan kelas bencana kembali ke bumi setelah mengalahkan iblis dan harus menghadapi masa lalu kelamnya.'],
            ['Absolute Dominion', 'project', 9.0, 'Action, Martial Arts, Drama', 'Pemuda tanpa nama menguasai seni bela diri kuno dan menantang seluruh aliansi besar.'],
            ['Nano Machine', 'manhwa', 9.4, 'Action, Martial Arts, Sci-Fi', 'Pangeran terbuang menerima implan nano dari masa depan dan mengubah takdirnya.'],
            ['Return of the Blossoming Blade', 'manhwa', 8.7, 'Action, Martial Arts, Adventure', 'Pendekar pedang terkuat kembali ke masa mudanya untuk menyelamatkan sektenya.'],
            ['Magic Emperor', 'manhua', 8.3, 'Action, Fantasy, Drama', 'Kaisar iblis terkuat memulai hidup baru sebagai pelayan rendahan untuk membalas dendam.'],
            ['Apotheosis', 'manhua', 8.1, 'Action, Adventure, Fantasy', 'Pemuda dari klan kecil menapaki jalan menuju kekekalan dengan bakat terpendamnya.'],
            ['One Piece', 'manga', 9.9, 'Adventure, Comedy, Fantasy', 'Perjalanan bajak laut topi jerami mencari harta karun legendaris bernama One Piece.'],
            ['Jujutsu Kaisen', 'manga', 9.2, 'Action, Supernatural, School', 'Seorang siswa menelan jari kutukan dan bergabung dengan sekolah penyihir jujutsu.'],
            ['The Beginning After The End', 'project', 8.9, 'Adventure, Fantasy, Drama', 'Raja yang bereinkarnasi menjadi bayi di dunia sihir kembali meniti takdir barunya.'],
            ["Swordmaster's Youngest Son", 'project', 8.0, 'Action, Martial Arts, Fantasy', 'Putra bungsu keluarga pedang terlahir kembali untuk mengubah masa depan yang hancur.'],
            ['Villain to Kill', 'manhwa', 8.1, 'Action, Supernatural, Drama', 'Pahlawan yang dikhianati bereinkarnasi sebagai penjahat dan mencari keadilan.'],
            ['Tomb Raider King', 'project', 8.6, 'Action, Adventure, Fantasy', 'Penjarah makam terbaik kembali ke masa lalu setelah dikhianati untuk balas dendam.'],
        ];

        $chapterTimes = [now()->subHours(3), now()->subWeek(), now()->subWeeks(2)];

        foreach ($data as $index => [$title, $type, $rating, $genre, $synopsis]) {
            $comic = Comic::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'type' => $type,
                    'status' => 'Ongoing',
                    'rating' => $rating,
                    'cover' => $gradients[$index % count($gradients)],
                    'synopsis' => $synopsis,
                    'genres' => $genre,
                ]
            );

            $base = 18 + ($index % 5);
            foreach ([0, 1, 2] as $offset) {
                Chapter::updateOrCreate(
                    ['comic_id' => $comic->id, 'number' => $base - $offset],
                    [
                        'title' => 'Pertarungan di '.($offset === 0 ? 'Menara Bayangan' : 'Kota Terlarang'),
                        'released_at' => $chapterTimes[$offset],
                    ]
                );
            }

        }

        $threads = [
            ['Rekomendasi manhwa action terbaik 2026', 'Lagi cari manhwa action dengan art bagus dan plot kuat. Ada rekomendasi?', 'AuraKnight'],
            ['Shadow Slave vs Solo Leveling', 'Menurut kalian mana yang lebih seru untuk dibaca ulang?', 'MataBiru'],
            ['Diskusi Chapter terbaru Pick Me Up', 'Spoiler di dalam. Bagaimana pendapat kalian tentang plot twist terakhir?', 'HanSolo'],
            ['Tips mengatur waktu baca harian', 'Bagaimana cara kalian menyeimbangkan baca komik dan kerjaan?', 'NerdBaca'],
        ];

        foreach ($threads as [$title, $body, $author]) {
            Thread::firstOrCreate(['title' => $title], ['body' => $body, 'author_name' => $author]);
        }
    }
}
