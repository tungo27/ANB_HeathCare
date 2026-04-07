<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register()
    {
        return view('users.register');
    }
}
//     public function register(Request $request)
//     {
//         $validatedData = $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'phone' => 'required|string|max:20',
//             'password' => 'required|string|min:8|confirmed',
//         ]);

//         // Create the user
//         $user = User::create([
//             'name' => $validatedData['name'],
//             'email' => $validatedData['email'],
//             'phone' => $validatedData['phone'],
//             'password' => bcrypt($validatedData['password']),
//         ]);

//         // Log the user in
//         auth()->login($user);

//         // Redirect to a desired page after registration
//         return redirect()->route('home')->with('success', 'Registration successful!');
//     }
// }
