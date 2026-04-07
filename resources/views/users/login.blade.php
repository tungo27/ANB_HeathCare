@extends('master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h3 class="text-center fw-bold mb-4" style="color: #333;">ĐĂNG NHẬP</h3>
                    
                    @if($errors->any())
                        <div class="alert alert-danger py-2" style="font-size: 0.85rem;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="/login" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email address*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control form-control-lg border-start-0 ps-0" 
                                       placeholder="Nhập email của bạn" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control form-control-lg border-start-0 ps-0" 
                                       placeholder="Nhập mật khẩu" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm" 
                                    style="border-radius: 10px; font-weight: 600;">Đăng nhập ngay</button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0" style="font-size: 0.9rem;">Chưa có tài khoản? 
                                <a href="/register" class="text-decoration-none fw-bold">Đăng ký tại đây</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection