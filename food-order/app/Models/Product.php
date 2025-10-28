<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
protected $fillable = ['name','description','price','image','available'];

public function vendor(){
return $this->belongsTo(Vendor::class);
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}

}

