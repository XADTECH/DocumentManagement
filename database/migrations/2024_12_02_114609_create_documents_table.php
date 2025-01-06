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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // The original file name
            $table->json('file_paths'); // Store multiple file paths as a JSON array
            $table->unsignedInteger('department_id'); // The department ID
            $table->unsignedBigInteger('subcategory_id')->nullable(); 
            $table->unsignedInteger('document_type_id'); // The document type ID
            $table->unsignedInteger('uploaded_by'); // The user who uploaded the file
            $table->boolean('ceo_approval')->default(false); // Flag for CEO approval requirement
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending'); // Approval status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
