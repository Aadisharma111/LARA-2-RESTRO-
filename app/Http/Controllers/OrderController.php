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
        // Check if the order exists
        if (!$order) {
        return redirect()->route('orders.index')->with('error', 'Order not found.');
    }
        return view('orders.show', compact('order'));
    }

    public function getFoodItems($restaurantId)
    {
        // Retrieve food items for the specified restaurantId
          $foodItems = FoodItem::where('restaurant_id', $restaurantId)->get();
    
       // Return the food items as JSON response
    return response()->json($foodItems);
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->restaurant_id = $request->restaurant_id;
        $order->items = $request->items;
        $order->total_price = $request->total_price;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }
         // Function to get data from JSON file
    public function getData()
    {
        // Read the contents of data.json
        $jsonData = file_get_contents(storage_path('app/data.json'));

        // Parse the JSON data
        $data = json_decode($jsonData, true);

        // Do something with the data...

}
}