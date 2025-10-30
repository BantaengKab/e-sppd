<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realization extends Model
{
    use HasFactory;

    protected $table = 'realizations';

    protected $fillable = [
        'sppd_id',
        'type',
        'amount',
        'description',
        'file_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the SPPD for this realization.
     */
    public function sppd()
    {
        return $this->belongsTo(SPPD::class);
    }
}
