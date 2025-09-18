<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'super_admin_id',
        'status',
    ];

    // Relationships
    public function superAdmin() { return $this->belongsTo(User::class, 'super_admin_id'); }
    public function users() { return $this->hasMany(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function returns() { return $this->hasMany(ProductReturn::class); }
    public function transfers() { return $this->hasMany(Transfer::class); }
    public function packages() { return $this->hasMany(Package::class); }
    public function treasury() { return $this->hasOne(Treasury::class); }
    public function maintenanceRequests() { return $this->hasMany(MaintenanceRequest::class); }
}