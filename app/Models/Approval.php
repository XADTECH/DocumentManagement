<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id',
        'approved_by',
        'status',
        'remarks',
    ];

    /**
     * Relationship with Document.
     * An approval belongs to a document.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relationship with User (Approver).
     * An approval is made by a user (e.g., CEO or other authorized person).
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
