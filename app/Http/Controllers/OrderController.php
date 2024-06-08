<?php
namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('orders.index', compact('restaurants'));
    }     
       
    public function create()
    {
        $restaurants = Restaurant::all();
        return view('orders.create', compact('restaurants'));
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }
        return view('orders.show', compact('order'));
    }

    public function getFoodItems($restaurantId)
    {
        // Fetch food items for the given restaurant ID
        $foodItems = FoodItem::where('restaurant_id', $restaurantId)->get();
        return response()->json($foodItems);
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->restaurant_id = $request->restaurant_id;
        $order->items = json_encode($request->items); // Ensure items are encoded as JSON
        $order->total_price = $request->total_price;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }
}
