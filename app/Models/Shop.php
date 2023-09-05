<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'address', 'decription', 'url'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
