<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimatedCost extends Model
{
    use HasFactory;

    protected $table = 'estimated_costs';

    protected $fillable = [
        'spt_id',
        'type',
        'amount',
        'description',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the SPT for this estimated cost.
     */
    public function spt()
    {
        return $this->belongsTo(SPT::class);
    }
}
