<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * Allow mass assignment for all attributes.
     */
    protected $guarded = [];

    /**
     * Define the table explicitly (optional if naming convention is followed).
     */
    protected $table = 'departments';

    /**
     * Relationship with Subcategories.
     * A department has many subcategories.
     */
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Relationship with Categories.
     * A department has many categories.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relationship with Documents.
     * A department has many documents.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
