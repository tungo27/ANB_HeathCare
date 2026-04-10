@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h2>Xin chào {{ Auth::user()->name }}</h2>

    <div class="appt-list">
       hello patient
    </div>

@endsection