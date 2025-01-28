<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',                // The document name
        'unique_id',
        'file_paths',          // JSON array of file paths
        'department_id',       // Department ID
        'subcategory_id',      // Subcategory ID
        'document_type_id',    // Document Type ID
        'uploaded_by',         // User who uploaded the file
        'ceo_approval',        // Flag for CEO approval requirement
        'approval_status',     // Approval status
    ];

    /**
     * Cast the JSON column to an array.
     *
     * @var array
     */
    protected $casts = [
        'file_paths' => 'array', // Cast JSON column as an array
    ];


    // Define the user relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Relationship with Category.
     * A document belongs to a category.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Relationship with User (Uploader).
     * A document is uploaded by a user.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    
}
