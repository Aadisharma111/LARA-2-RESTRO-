<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
       
        $credentials = $request->only('email', 'password');
            
        if (Auth::attempt($credentials)) {
            // Authentication successful
            return redirect()->route('restaurants.index');
        }

        // Authentication failed
        return back()->withErrors(['email' => 'Invalid email or password']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
