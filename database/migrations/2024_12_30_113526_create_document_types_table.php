<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Name of the document type
            $table->foreignId('department_id') // Foreign key linking to departments
                  ->constrained('departments')
                  ->onDelete('cascade');
            $table->foreignId('subcategory_id') // Foreign key linking to subcategories
                  ->constrained('subcategories')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
}
