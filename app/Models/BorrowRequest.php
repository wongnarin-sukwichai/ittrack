<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'borrower_name', 'email', 'department',
        'items', 'purpose', 'borrow_date', 'return_date', 'status',
    ];

    protected $casts = [
        'items'       => 'array',
        'borrow_date' => 'date',
        'return_date' => 'date',
    ];

    public static function generateId(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count();
        return 'REQ-' . $year . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    public function equipments()
    {
        return Equipment::whereIn('id', $this->items ?? [])->get();
    }
}
