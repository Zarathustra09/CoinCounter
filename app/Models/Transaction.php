<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'item_id',
        'quantity',
        'total_price',
        'purchased_at',
    ];

    // Relationships
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
