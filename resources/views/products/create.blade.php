@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Produk</h1>

    <form method="POST" action="{{ route('products.store') }}" class="bg-white rounded-lg shadow-sm p-6 max-w-2xl space-y-4">
        @csrf
        @include('products._form', ['product' => null])
        <div class="pt-2">
            <button class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-md text-sm font-medium">Simpan</button>
            <a href="{{ route('products.index') }}" class="ml-2 text-sm text-gray-500">Batal</a>
        </div>
    </form>
@endsection
