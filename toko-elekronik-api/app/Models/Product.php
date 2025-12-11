<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'category_id',
        'stock',
        'price',
        'description',
        'product_code',
        'waranty_period',
    ];
    protected $cast = [
        'price' => 'integer',
        'stock' => 'integer',
    ];
    public function category()
    {
        return $this->belongsTo(Categories::class,'category_id','id');
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransactions::class, 'product_id', 'id');
    }
}
