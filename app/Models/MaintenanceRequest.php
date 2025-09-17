<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number', 'device_name', 'device_type', 'problem_description',
        'status', 'priority', 'cost', 'store_id', 'customer_name', 'customer_phone', 'technician_id'
    ];

    public function store() { return $this->belongsTo(Store::class); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
}
