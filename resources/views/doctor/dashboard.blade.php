@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>Xin chào BS. {{ Auth::user()->name }}</h2>

    <div class="stat-grid">
        <div class="stat-card">
            <p>Lịch hẹn hôm nay</p>
        </div>
        <div class="stat-card">
            <p>Bệnh nhân đang theo dõi</p>
        </div>
    </div>

@endsection