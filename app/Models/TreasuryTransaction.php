<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasuryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'treasury_id', 'type', 'amount', 'description', 'reference_type', 'reference_id', 'user_id'
    ];

    public function treasury() { return $this->belongsTo(Treasury::class); }
    public function user() { return $this->belongsTo(User::class); }
}
