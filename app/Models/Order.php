<?php
//This model's role is To manage the orders placed by the customers

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'phone_number',
        'total_price',
        'status',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function OrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }

    public function getTotalAttribute()
    {
        return $this->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }
}
