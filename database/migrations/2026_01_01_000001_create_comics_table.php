<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['manhwa', 'manhua', 'manga', 'project'])->default('manhwa')->index();
            $table->string('status')->default('Ongoing');
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('cover');
            $table->text('synopsis');
            $table->string('genres');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comics');
    }
};
