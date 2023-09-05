<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flavor extends Model
{
    use HasFactory;
    protected $table = 'flavors';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * The product that belong to the Flavor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(product::class, 'product_size_flavors');
    }
}
