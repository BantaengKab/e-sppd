<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPT extends Model
{
    use HasFactory;

    protected $table = 'spts';

    protected $fillable = [
        'user_id',
        'title',
        'purpose',
        'destination',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user who created the SPT.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the estimated costs for the SPT.
     */
    public function estimatedCosts()
    {
        return $this->hasMany(EstimatedCost::class);
    }

    /**
     * Get the SPPD for the SPT.
     */
    public function sppd()
    {
        return $this->hasOne(SPPD::class);
    }

    /**
     * Get the approvals for the SPT.
     */
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    /**
     * Get the total estimated cost.
     */
    public function getTotalEstimatedCostAttribute()
    {
        return $this->estimatedCosts->sum('amount');
    }

    /**
     * Check if SPT is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if SPT is submitted.
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if SPT is draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }
}
