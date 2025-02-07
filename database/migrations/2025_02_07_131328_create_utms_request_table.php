<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('utms_request', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id'); // FK para a tabela requests

            // Campos para armazenar os UTMs
            $table->string('cwr')->nullable();
            $table->string('twr')->nullable();
            $table->string('gwr')->nullable();
            $table->string('domain')->nullable();
            $table->string('cr')->nullable();
            $table->string('plc')->nullable();
            $table->string('mtx')->nullable();
            $table->string('rdn')->nullable();
            $table->string('kw')->nullable();
            $table->string('cpc')->nullable();
            $table->string('disp')->nullable();
            $table->string('int')->nullable();
            $table->string('loc')->nullable();
            $table->string('net')->nullable();
            $table->string('pos')->nullable();
            $table->string('dev')->nullable();
            $table->string('gclid')->nullable();
            $table->string('wbraid')->nullable();
            $table->string('gbraid')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('xid')->nullable();

            // Chave estrangeira para requests
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utms_request');
    }
};
