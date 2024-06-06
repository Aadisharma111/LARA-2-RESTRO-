<?php
namespace App\Http\Controllers;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        // Validate only the 'name' and 'address' fields
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
        ]);
    
             // Create a new restaurant with the validated data
        Restaurant::create([
            'name'    => $request->name,
            'address' => $request->address,
            'contact' => $request->contact,
        ]);
       
            // Redirect to a desired route with a success message
        return redirect()->route('restaurants.index')
                         ->with('success', 'Restaurant created successfully.');
    }

        public function show(Restaurant $restaurant)
         {
           return view('restaurants.show', compact('restaurant'));
        }
 
    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:restaurants,name,' . $restaurant->id,
            'address' => 'required|string|max:255',
        ]);
 
        $restaurant->update($validatedData);

        return redirect()->route('restaurants.index')->with('success', 'Restaurant updated successfully!');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('restaurants.index')->with('success', 'Restaurant deleted successfully!');
    }    
}
