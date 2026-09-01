@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <h1 class="text-xl font-semibold mb-4">Tambah Kategori</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="bg-white rounded-lg shadow-sm p-6 max-w-md space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-md border-gray-300 shadow-sm text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <button class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-md text-sm font-medium">Simpan</button>
        <a href="{{ route('categories.index') }}" class="ml-2 text-sm text-gray-500">Batal</a>
    </form>
@endsection
