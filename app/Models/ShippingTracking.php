<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingTracking extends Model
{
    use HasFactory;
    protected $table = 'shipping_tracking';
    protected $fillable = ['order_id', 'status', 'delivery_person_id'];

    /**
     * Get all of the locations for the ShippingTracking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany(Location::class, 'shipping_tracking_id');
    }
}
