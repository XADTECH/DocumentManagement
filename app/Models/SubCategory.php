<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',          // Subcategory name
        'department_id', // Foreign key linking to a department
    ];

    /**
     * Relationship: A subcategory belongs to a department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
