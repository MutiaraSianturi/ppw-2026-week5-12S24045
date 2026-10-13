<x-layout title="Pinjam Buku">
    <h2 class="text-del fw-bold mb-3">Pinjam Buku</h2>

    {{-- Info buku yang mau dipinjam --}}
    <div class="card shadow-sm mb-3"><div class="card-body">
        <h5 class="mb-1">{{ $buku->judul }}</h5>
        <div class="text-muted">{{ $buku->penulis }} · ISBN {{ $buku->isbn }}</div>
        {{-- Badge merah kalau stok 0, hijau kalau ada --}}
        <div class="mt-2">Stok tersedia:
            <span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">{{ $buku->stok }}</span>
        </div>
    </div></div>

    <div class="card shadow-sm"><div class="card-body">
        <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">NIM Peminjam</label>
                    {{-- Default diisi dari SESSION peminjam terakhir --}}
                    <input type="text" name="nim_peminjam" class="form-control @error('nim_peminjam') is-invalid @enderror"
                           value="{{ old('nim_peminjam', session('peminjam_terakhir.nim')) }}">
                    @error('nim_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror"
                           value="{{ old('nama_peminjam', session('peminjam_terakhir.nama')) }}">
                    @error('nama_peminjam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-del">Pinjam Buku</button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</x-layout>