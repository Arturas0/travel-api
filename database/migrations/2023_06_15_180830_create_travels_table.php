<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->ulid('id')->index();
            $table->boolean('is_public')->default(false);
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->unsignedInteger('number_of_days');
            $table->unsignedInteger('number_of_nights')->virtualAs('number_of_days -1');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel');
    }
};
