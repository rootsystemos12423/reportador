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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome da campanha
            $table->foreignId('domain_id')->constrained('domains'); // Relacionamento com a tabela de domínios
            $table->string('language'); // Idioma da campanha
            $table->string('traffic_source'); // Fonte de tráfego
            $table->text('safe_page'); // Arquivo de página segura
            $table->string('method_safe'); // Método da página segura
            $table->string('method_offer'); // Método da oferta
            $table->json('offer_pages'); // Links das páginas de oferta (armazenados como JSON)
            $table->json('target_countries')->nullable(); // Países alvo (armazenados como JSON)
            $table->json('target_devices')->nullable(); // Dispositivos alvo (armazenados como JSON)
            $table->string('hash'); // Hash Da Campanha
            $table->timestamps(); // Timestamps (created_at e updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
