@php
    $cartCount = \App\Facades\Cart::count();
@endphp

<a href="{{ route('cart.index', ['locale' => app()->getLocale()]) }}"
    class="relative group p-2 text-mathey-gray hover:text-mathey-gold transition-all duration-300">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
    </svg>

    @if($cartCount > 0)
        <span
            class="absolute -top-1 -right-1 bg-mathey-gold text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
            {{ $cartCount > 99 ? '99+' : $cartCount }}
        </span>
    @endif

    <span
        class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 text-xs text-white bg-mathey-blue rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
        Panier
    </span>
</a>