<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\FoodItem;
use Illuminate\Support\Facades\DB; // Make sure to import the DB facade

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('restaurant', 'items')->get();
        $restaurants = Restaurant::all();
        return view('orders.index', compact('orders', 'restaurants'));
    }

    public function create()
    {
        $restaurants = Restaurant::all();
        return view('orders.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
       // Validate the request
       $request->validate([
        'restaurant_id' => 'required|exists:restaurants,id',
        'items' => 'required|array',
        'items.*.id' => 'required|exists:food_items,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();

        try {
            $order = Order::create([
                'restaurant_id' => $request->restaurant_id,
                'total_price' => 0, // Temporary value, will be updated later
            ]);
    
            $totalPrice = 0;
    
            foreach ($request->items as $item) {
                $foodItem = FoodItem::findOrFail($item['id']);
                $orderItem = $order->items()->create([
                    'order_id' => $order->id,
                    'food_item_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
    
                $totalPrice += $orderItem->price * $orderItem->quantity;
            }
    
            $order->total_price = $totalPrice;
            $order->save();
            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while creating the order. Please try again.']);
        }
     }
     public function fetchFoodItems($restaurantId)
    {
        // Eager load the foodItems relationship
        $restaurant = Restaurant::with('foodItems')->find($restaurantId);

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        // Return the food items as JSON
        return response()->json($restaurant->foodItems);
    }
}
