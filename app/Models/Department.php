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
     * Relationship with Documents.
     * A department has many documents.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function subcategories()
{
    return $this->hasMany(Subcategory::class);
}

}
