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
        'name',           // The original file name
        'file_path',      // File path in the storage
        'department_id',  // Foreign key to departments
        'subcategory_id', // Foreign key to subcategories
        'category_id',    // Foreign key to categories
        'uploaded_by',    // User ID of the uploader
        'ceo_approval',   // Indicates if CEO approval is required
        'approval_status' // Approval status of the document
    ];

    /**
     * Relationship with Department.
     * A document belongs to a department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship with Subcategory.
     * A document belongs to a subcategory.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Relationship with Category.
     * A document belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
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
