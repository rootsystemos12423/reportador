<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('referer')->nullable(); // Adiciona o campo referer como string, permitindo nulo
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('requests', function (Blueprint $table) {
        $table->dropColumn('referer');
    });
}
};
