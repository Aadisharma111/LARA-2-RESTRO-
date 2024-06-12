<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Restaurant extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'address'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }
}
