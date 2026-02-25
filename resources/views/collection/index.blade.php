@extends('layouts.mathey')

@section('content')
    <div class="pt-32 pb-20 bg-mathey-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-mathey-gold font-bold tracking-[0.2em] uppercase text-sm mb-4 block">Découvrez Notre
                    Univers</span>
                <h1 class="text-4xl md:text-6xl font-serif text-mathey-blue mb-6 italic">Nos Collections</h1>
                <div class="w-24 h-px bg-mathey-gold mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($collections as $collection)
                    <div class="group relative overflow-hidden rounded-lg shadow-xl aspect-[4/5] bg-mathey-blue">
                        <img src="{{ $collection->image ?? '/images/placeholder.jpg' }}" alt="{{ $collection->name }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-70 group-hover:opacity-100">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-mathey-blue via-transparent to-transparent opacity-60">
                        </div>

                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h2 class="text-2xl font-serif font-bold text-white mb-2">{{ $collection->name }}</h2>
                            <p class="text-white/80 text-sm mb-6 line-clamp-2">{{ $collection->description }}</p>
                            <a href="{{ route('collections.show', ['locale' => app()->getLocale(), 'slug' => $collection->slug]) }}"
                                class="inline-block self-start border border-white text-white px-6 py-2 rounded hover:bg-white hover:text-mathey-blue transition-all duration-300">
                                Explorer
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $collections->links() }}
            </div>
        </div>
    </div>
@endsection