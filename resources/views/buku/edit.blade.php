<x-layout title="Edit Buku">
    <h2 class="text-del fw-bold mb-3">Edit Buku</h2>
    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('buku.update', $buku) }}" method="POST">
            @csrf
            @method('PUT')   {{-- menyamar jadi PUT supaya masuk ke method update() --}}
            @include('buku._form')
            <div class="mt-4">
                <button class="btn btn-del">Perbarui</button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</x-layout>