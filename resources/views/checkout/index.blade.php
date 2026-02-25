@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-2xl font-semibold mb-6">Checkout</h1>
    <form method="post" action="{{ url('/fr/checkout') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="payment_method" value="card">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="name">Nom</label>
                <input id="name" name="shipping_address[name]" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input id="email" name="email" type="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1" for="street">Adresse</label>
                <input id="street" name="shipping_address[street]" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="zip">NPA</label>
                <input id="zip" name="shipping_address[zip]" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="city">Ville</label>
                <input id="city" name="shipping_address[city]" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="country">Pays</label>
                <input id="country" name="shipping_address[country]" value="Suisse" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="phone">Téléphone</label>
                <input id="phone" name="shipping_address[phone]" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>
        <div>
            <button class="inline-flex items-center px-4 py-2 rounded bg-swiss-red text-white">Payer</button>
        </div>
    </form>
</div>
@endsection
