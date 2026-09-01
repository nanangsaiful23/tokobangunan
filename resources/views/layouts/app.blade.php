<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Toko Bangunan') }} @hasSection('title') - @yield('title') @endif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-slate-800 text-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-14 items-center">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('dashboard') }}" class="font-semibold text-lg tracking-tight">
                            🧱 {{ config('app.name', 'Toko Bangunan') }}
                        </a>
                        <div class="hidden md:flex gap-1 text-sm">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                            <x-nav-link :href="route('sales.create')" :active="request()->routeIs('sales.create')">Kasir (POS)</x-nav-link>
                            <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*') && !request()->routeIs('sales.create')">Penjualan</x-nav-link>
                            <x-nav-link :href="route('stock.index')" :active="request()->routeIs('stock.*')">Stok</x-nav-link>
                            @if (auth()->user()?->isOwner())
                                <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">Produk</x-nav-link>
                                <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">Kategori</x-nav-link>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-slate-300">{{ auth()->user()?->name }} <span class="text-slate-500">({{ auth()->user()?->role }})</span></span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-slate-300 hover:text-white">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-4 py-6">
                @if (session('status'))
                    <div class="mb-4 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
