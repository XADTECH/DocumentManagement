<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the subcategory
            $table->string('name'); // Name of the subcategory
            $table->unsignedBigInteger('department_id'); 
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
