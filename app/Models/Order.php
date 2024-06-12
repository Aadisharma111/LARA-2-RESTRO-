<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['restaurant_id', 'total_price'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function items()
    {
        return $this->belongsToMany(FoodItem::class, 'order_food_item')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    public function manualItems()
    {
        return $this->hasMany(ManualItem::class);
    }
}
