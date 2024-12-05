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
            $table->string('file_path'); // The file path in the storage
            $table->unsignedInteger('department_id'); // The department ID
            $table->unsignedInteger('category_id'); // The category ID
            $table->unsignedInteger('uploaded_by'); // The user who uploaded the file
            $table->unsignedBigInteger('subcategory_id')->nullable(); 
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
