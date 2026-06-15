<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLog extends Model
{
    protected $fillable = [
        'asset_id', 'action', 'detail', 'location', 'operator', 'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'asset_id');
    }
}
