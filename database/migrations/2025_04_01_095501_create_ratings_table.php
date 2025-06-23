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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); // Автоматически добавляет поле id как первичный ключ
            $table->tinyInteger('rating')->unsigned(); // Рейтинг от 1 до 5
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade'); // Рецепт
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Пользователь
            $table->timestamps(); // Время создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
