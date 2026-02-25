@extends('layouts.mathey')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-2xl font-semibold mb-6">Votre panier</h1>
        @include('components.cart-panel')
    </div>
@endsection