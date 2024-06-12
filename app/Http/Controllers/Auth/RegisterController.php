<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    protected $redirectTo = 'restaurants.index';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            // Redirect back with validation errors
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // Create the user
        $user = $this->create($request->all());

        // Log user registration
        Log::info('User registered: ' . $user->email);

        // Log the user in
        Auth::login($user);

        // Redirect to the desired path
        return route('restaurants.index');
    }
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}

