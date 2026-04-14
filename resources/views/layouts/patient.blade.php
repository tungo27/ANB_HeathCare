@extends('layouts.app')

@section('main_content')
@include('components.header-patient')

    <main class="flex-grow-1 py-10">
            <div class="container mx-auto px-4">    
                    @yield('content')
            </div>
        </main>

@endsection