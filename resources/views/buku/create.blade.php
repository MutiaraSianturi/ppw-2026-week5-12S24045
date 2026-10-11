<x-layout title="Tambah Buku">
    <h2 class="text-del fw-bold mb-3">Tambah Buku</h2>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('buku.store') }}" method="POST">
            @csrf
            @include('buku._form')   {{-- sisipkan isi form bersama --}}
            <div class="mt-4">
                <button class="btn btn-del">Simpan</button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</x-layout>