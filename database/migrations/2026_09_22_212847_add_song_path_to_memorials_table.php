<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->string('song_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn('song_path');
        });
    }
};
