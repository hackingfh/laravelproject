@extends('layouts.mathey')

@section('title', 'Connexion | Mathey-Tissot')

@section('content')
    <div class="min-h-screen pt-32 pb-20 flex items-center justify-center bg-mathey-blue/5">
        <div class="max-w-md w-full px-6 opacity-0 translate-y-10" id="auth-container">
            <!-- Logo & Title -->
            <div class="text-center mb-10">
                <h2 class="font-serif text-3xl text-mathey-blue mb-2">Bienvenue</h2>
                <p class="text-mathey-gray">Connectez-vous à votre compte Mathey-Tissot</p>
            </div>

            <div class="bg-white rounded-2xl shadow-luxury-lg p-8 border border-mathey-gold/10">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-mathey-blue mb-1">Email</label>
                        <input id="email"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mathey-gold focus:ring-2 focus:ring-mathey-gold/20 transition-all duration-300 outline-none"
                            type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" />
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-mathey-blue">Mot de passe</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-mathey-gray hover:text-mathey-gold transition-colors"
                                    href="{{ route('password.request') }}">
                                    Oublié ?
                                </a>
                            @endif
                        </div>
                        <input id="password"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-mathey-gold focus:ring-2 focus:ring-mathey-gold/20 transition-all duration-300 outline-none"
                            type="password" name="password" required autocomplete="current-password" />
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-mathey-gold shadow-sm focus:ring-mathey-gold"
                                name="remember">
                            <span class="ms-2 text-sm text-mathey-gray">Se souvenir de moi</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full btn-primary py-4 rounded-xl shadow-lg shadow-mathey-gold/20 flex items-center justify-center gap-2 group overflow-hidden relative">
                            <span class="relative z-10">Se connecter</span>
                            <svg class="w-4 h-4 relative z-10 transition-transform duration-300 group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-sm">
                    <span class="text-mathey-gray">Pas encore de compte ?</span>
                    <a href="{{ route('register') }}"
                        class="ml-1 font-medium text-mathey-gold hover:text-mathey-blue transition-colors underline underline-offset-4 decoration-mathey-gold/30">
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to("#auth-container", {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    delay: 0.5,
                    ease: "power4.out"
                });
            }
        });
    </script>
@endsection