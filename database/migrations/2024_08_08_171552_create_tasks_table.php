<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(
                table: 'users',
                indexName: 'users_id'
            );
            $table->foreignId('category_id')->constrained(
                table: 'categories',
                indexName: 'categories_id'
            );
            $table->string('description');
            $table->timestamps();
            $table->unsignedBigInteger('creted_by')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
