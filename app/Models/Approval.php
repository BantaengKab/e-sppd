<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approvals';

    protected $fillable = [
        'spt_id',
        'approved_by',
        'stage',
        'status',
        'comment',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the SPT for this approval.
     */
    public function spt()
    {
        return $this->belongsTo(SPT::class);
    }

    /**
     * Get the user who made the approval.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if approval is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if approval is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if approval is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
