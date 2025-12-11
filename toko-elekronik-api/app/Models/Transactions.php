<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'user_id',
        'total_price',
        'date',
    ];
    protected $casts = [
        'total_price' => 'integer'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function detailTransactions()
    {
        return $this->hasMany(DetailTransactions::class, 'transaction_id', 'id');
    }
}
