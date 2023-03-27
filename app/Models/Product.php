<?php
//This model's role is To manage the products with their descriptions, images, and prices

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; //??

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
