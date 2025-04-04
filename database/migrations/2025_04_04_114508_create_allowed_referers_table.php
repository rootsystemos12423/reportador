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
        Schema::create('allowed_referers', function (Blueprint $table) {
            $table->id();
            $table->string('referer'); // Ex: "google.com/search", "android-app://com.google.android.googlequicksearchbox"
            $table->string('campaign_type'); // Ex: "organic", "paid", "social", "email", "direct"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('allowed_referers');
    }
};
