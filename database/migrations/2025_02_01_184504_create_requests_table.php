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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->index(); // IP do usuário
            $table->string('continent')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('timezone')->nullable();
            $table->string('isp')->nullable();
            $table->string('org')->nullable();
            $table->string('asn')->nullable();
            $table->string('reverse_dns')->nullable();
            $table->string('language')->nullable();
            $table->string('device')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('allowed')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
