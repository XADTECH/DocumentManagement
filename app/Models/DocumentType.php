<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',           // Name of the document type
        'subcategory_id', // Foreign key linking to a subcategory
        'department_id'
    ];

    /**
     * Relationship: A DocumentType belongs to a Subcategory.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
