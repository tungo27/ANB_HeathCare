<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public function Showlogin()
    {
        return view('users.login');
    }
    public function login(Request $request)
    {
        // Lấy email và password từ form bạn vừa làm
        $credentials = $request->only('email', 'password');

        // Kiểm tra xem tài khoản có tồn tại và mật khẩu có khớp không
        if (Auth::attempt($credentials)) {
            
            // Đăng nhập đúng rồi! Bây giờ mới xem người này là ai:
            $user = Auth::user(); 

            // Kiểm tra cột 'role' trong database để chuyển hướng (Redirect)
            if ($user->role == 'admin') {
                return redirect('/admin/dashboard'); // Trang chủ của Admin
            } 
            
            if ($user->role == 'doctor') {
                return redirect('/doctor/dashboard'); // Trang chủ của Bác sĩ
            }

            // Nếu không phải 2 cái trên thì chắc chắn là Patient (Bệnh nhân)
            return redirect('/patient/home'); // Trang chủ của Bệnh nhân
        }

        // Nếu sai email hoặc mật khẩu thì quay lại trang login và báo lỗi
        return back()->withErrors(['msg' => 'Sai tài khoản hoặc mật khẩu rồi bạn ơi!']);
    }

}