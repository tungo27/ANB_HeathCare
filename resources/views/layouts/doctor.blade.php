@extends('layouts.app')

@section('main_content')
@include('components.header-doctor')
    <main class="main-content flex-grow-1 p-4">
        @yield('content')
    </main>
@endsection
