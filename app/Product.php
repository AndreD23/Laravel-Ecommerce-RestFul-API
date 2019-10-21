<?php

namespace App;

use App\Review;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'detail',
        'price',
        'stock',
        'discount'
    ];

    public function reviews(){
        return $this->hasMany(Review::class);
    }

}
