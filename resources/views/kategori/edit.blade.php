<x-layout title="Edit Kategori">
    <h2 class="text-del fw-bold mb-3">Edit Kategori</h2>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('kategori.update', $kategori) }}" method="POST">
            @csrf
            @method('PUT')
            @include('kategori._form')
            <div class="mt-4">
                <button class="btn btn-del">Perbarui</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</x-layout>