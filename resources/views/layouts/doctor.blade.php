@extends('layouts.app')

@section('main_content')
    <div class="min-vh-100 d-flex">

        <div class="d-flex flex-column flex-grow-1">

            <main class="main-content flex-grow-1 p-4">
                @yield('content')
            </main>

        </div>
    </div>
@endsection
