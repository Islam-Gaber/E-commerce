<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table ='products';

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }
    public function Cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
