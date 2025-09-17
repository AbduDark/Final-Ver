<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    use HasFactory;

    protected $fillable = ['store_id', 'current_balance', 'last_transaction_type', 'last_transaction_amount', 'last_transaction_date'];
    protected $casts = ['last_transaction_date' => 'datetime'];

    public function store() { return $this->belongsTo(Store::class); }
    public function transactions() { return $this->hasMany(TreasuryTransaction::class); }
}
