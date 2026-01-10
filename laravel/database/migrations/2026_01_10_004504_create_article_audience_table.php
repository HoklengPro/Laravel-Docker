<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_audience', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->foreignId('audience_id')->constrained()->onDelete('cascade');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->primary(['article_id', 'audience_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_audience');
    }
};

