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
        Schema::create('shopify_index', function (Blueprint $table) {
            $table->id(); // ID único para a tabela
            $table->foreignId('backup_link_id') // Chave estrangeira
                  ->constrained('backup_links') // Referencia a tabela `backup_links`
                  ->onDelete('cascade'); // Deleta registros associados ao deletar `backup_links`
            $table->string('index_file_path')->nullable(); // Caminho do arquivo index.html
            $table->timestamps(); // Timestamps de criação e atualização
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_index');
    }
};
