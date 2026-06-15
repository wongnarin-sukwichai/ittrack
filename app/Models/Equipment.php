<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $table = 'equipments';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'category', 'serial', 'description',
        'status', 'icon', 'current_location', 'lifecycle_state', 'image',
    ];

    public function assetLogs(): HasMany
    {
        return $this->hasMany(AssetLog::class, 'asset_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return "https://picsum.photos/seed/{$this->id}/400/220";
        }
        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'data:')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    protected $appends = ['image_url'];
}
