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
        Schema::create('backup_links', function (Blueprint $table) {
            $table->id(); // ID do link de backup
            $table->foreignId('landing_page_id')->constrained('landing_pages')->onDelete('cascade'); // FK para landing_pages
            $table->string('url'); // URL do link de backup
            $table->timestamps(); // Timestamps padrão
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_links');
    }
};
