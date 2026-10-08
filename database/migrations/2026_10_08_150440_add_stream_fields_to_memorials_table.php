<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->boolean('stream_enabled')->default(false);
            $table->boolean('stream_is_live')->default(false);
            $table->boolean('stream_autoplay')->default(true);
            $table->string('stream_provider', 20)->nullable();
            $table->text('stream_url')->nullable();
            $table->string('stream_title')->nullable();
            $table->text('stream_description')->nullable();
            $table->timestamp('stream_starts_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn([
                'stream_enabled',
                'stream_is_live',
                'stream_autoplay',
                'stream_provider',
                'stream_url',
                'stream_title',
                'stream_description',
                'stream_starts_at',
            ]);
        });
    }
};
