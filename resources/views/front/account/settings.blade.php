@extends('layouts.mathey')

@section('title', 'Paramètres du Compte - Mathey-Tissot')

@section('content')
    <div class="bg-mathey-cream py-12 border-b border-mathey-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-serif text-4xl font-bold text-mathey-blue mb-2 text-center">Votre Profil</h1>
            <p class="text-mathey-gray text-center max-w-2xl mx-auto">Gérez vos informations personnelles et la sécurité de
                votre compte.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Navigation latérale -->
            <div class="lg:col-span-1">
                <nav class="space-y-2">
                    <a href="{{ route('account.index', ['locale' => app()->getLocale()]) }}"
                        class="block px-4 py-3 rounded-xl bg-mathey-blue text-white font-medium shadow-sm">
                        Paramètres du Profil
                    </a>
                    <a href="{{ route('orders.index', ['locale' => app()->getLocale()]) }}"
                        class="block px-4 py-3 rounded-xl bg-white border border-mathey-border text-mathey-blue hover:bg-mathey-cream transition font-medium">
                        Mes Commandes
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-3 rounded-xl bg-white border border-mathey-border text-red-600 hover:bg-red-50 transition font-medium">
                            Se déconnecter
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Formulaires -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Informations personnelles -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-mathey-border">
                    <h2 class="text-2xl font-serif font-bold text-mathey-blue mb-6">Informations Personnelles</h2>
                    <form action="{{ route('account.update', ['locale' => app()->getLocale()]) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Nom Complet</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Adresse Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold">
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Téléphone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold"
                                    placeholder="+222 ...">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-mathey-blue text-white px-8 py-3 rounded-xl hover:bg-mathey-blue/90 transition font-medium shadow-md">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Sécurité -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-mathey-border">
                    <h2 class="text-2xl font-serif font-bold text-mathey-blue mb-6">Sécurité du Compte</h2>
                    <form action="{{ route('account.password', ['locale' => app()->getLocale()]) }}" method="POST"
                        class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Mot de passe actuel</label>
                                <input type="password" name="current_password" required
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold">
                                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Nouveau mot de passe</label>
                                <input type="password" name="password" required
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold">
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-mathey-gray mb-2">Confirmer le nouveau mot de
                                    passe</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full rounded-xl border-mathey-border shadow-sm focus:ring-mathey-gold focus:border-mathey-gold">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-mathey-gold text-white px-8 py-3 rounded-xl hover:bg-mathey-gold/90 transition font-medium shadow-md">
                                Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection