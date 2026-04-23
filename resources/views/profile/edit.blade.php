@extends('layouts.app') {{-- Hoặc file layout Bootstrap của bạn --}}

@section('main_content')
    <div class="container py-5">
        <h2 class="mb-4 text-dark font-weight-bold">Hồ sơ cá nhân</h2>

        <div class="row g-4">

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            {{-- Phần đổi mật khẩu --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            {{-- Phần xóa tài khoản --}}
            <div class="col-12">
                <div class="card border-danger shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
