<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id')->unique()->index();
            $table->string('url')->nullable();
            $table->string('title');
            $table->text('resumen')->nullable();
            $table->longText('texto_descriptivo')->nullable();
            $table->longText('texto_descriptivo_sin_html')->nullable();
            $table->string('regional')->nullable();
            $table->json('temas')->nullable();
            $table->json('categorias')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
