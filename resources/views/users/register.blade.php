@extends('master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h3 class="text-center fw-bold mb-4" style="color: #333;">ĐĂNG KÝ TÀI KHOẢN</h3>
                    
                    <form action="/register" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="full_name" class="form-control form-control-lg border-start-0 ps-0" placeholder="Nhập tên" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email address*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control form-control-lg border-start-0 ps-0" placeholder="Nhập email" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" name="phone" class="form-control form-control-lg border-start-0 ps-0" placeholder="Nhập số điện thoại" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control form-control-lg border-start-0 ps-0" placeholder="Nhập mật khẩu" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Re-password*</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0">
                                    <i class="fas fa-unlock-alt"></i>
                                </span>
                                <input type="password" name="c_password" class="form-control form-control-lg border-start-0 ps-0" placeholder="Nhập lại mật khẩu" required style="font-size: 0.9rem;">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 10px; font-weight: 600;">Đăng ký ngay</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection