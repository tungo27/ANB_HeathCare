<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function Getregister()
    {
        return view('users.register');
    }
    public function Register(Request $request)
    {


        $input = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|nullable|regex:/^0\d{9}$/',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        $input['password'] = bcrypt($input['password']);
        unset($input['c_password']); // Bắt buộc phải remove trường c_password trước khi lưu vào database
        User::create($input);

        echo '<script>alert("Đăng ký thành công. Vui lòng đăng nhập.");window.location.assign("login");</script>';
    }
    }


