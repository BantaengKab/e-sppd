<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPPD extends Model
{
    use HasFactory;

    protected $table = 'sppds';

    protected $fillable = [
        'spt_id',
        'number',
        'issue_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Get the SPT for this SPPD.
     */
    public function spt()
    {
        return $this->belongsTo(SPT::class);
    }

    /**
     * Get the realizations for this SPPD.
     */
    public function realizations()
    {
        return $this->hasMany(Realization::class);
    }

    /**
     * Get the total realized cost.
     */
    public function getTotalRealizedCostAttribute()
    {
        return $this->realizations->sum('amount');
    }

    /**
     * Check if SPPD is issued.
     */
    public function isIssued()
    {
        return $this->status === 'issued';
    }

    /**
     * Check if SPPD is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
