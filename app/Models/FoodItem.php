<?php
// app/Models/FoodItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'restaurant_id'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
        public function orders()
       {
        return $this->belongsToMany(Order::class, 'order_food_item')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}