<?php

namespace App;

use App\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    
    use SoftDeletes;

    protected $table = 'reviews';

    protected $fillable = [
        'product_id',
        'customer',
        'review',
        'star'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
