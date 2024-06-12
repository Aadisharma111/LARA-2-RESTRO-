<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Controller
{
    protected $redirectTo = 'restaurants.index'; // Use the route name without a slash

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the login data
        $this->validateLogin($request);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                // Redirect to the intended route or fallback to $redirectTo
                return redirect()->intended(route($this->redirectTo));
            } else {
                // Incorrect password
                return redirect()->back()->withErrors([
                    'password' => 'The provided password is incorrect.',
                ])->withInput($request->only('email'));
            }
        } else {
            // Email does not exist
            return redirect()->back()->withErrors([
                'email' => 'The provided email does not match our records.',
            ])->withInput($request->only('email'));
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
