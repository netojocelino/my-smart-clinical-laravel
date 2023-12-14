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
        Schema::create('todo_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('todo_item_id');
            $table->string('event');
            $table->json('changes')->default('{}');

            $table->foreign('todo_item_id')->references('id')->on('todo_items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_histories');
    }
};
