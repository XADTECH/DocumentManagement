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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('xad_id');
      $table->string('first_name');
      $table->string('last_name');
      $table->string('email');
      $table->string('nationality')->nullable();
      $table->string('organization_unit')->nullable();
      $table->string('phone_number')->nullable();
      $table->string('password'); // Password field for authentication
      $table->string('role')->default('User'); // e.g., 'User', 'Admin', etc.
      $table->json('permissions')->nullable(); // JSON field to store module access permissions
      $table->string('profile_image')->nullable();
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
