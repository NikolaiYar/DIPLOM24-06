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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Используйте id вместо UniqueID
            $table->string('password');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('registration_date')->useCurrent();
            $table->string('avatar')->nullable();
            $table->foreignId('role_id')->constrained('roles')->default(2); // Ссылается на id в таблице roles
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
