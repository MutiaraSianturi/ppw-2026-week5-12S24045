<x-layout title="Detail Buku">
    <h2 class="text-del fw-bold mb-3">{{ $buku->judul }}</h2>
    <div class="card shadow-sm"><div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">ISBN</dt><dd class="col-sm-9">{{ $buku->isbn }}</dd>
            <dt class="col-sm-3">Penulis</dt><dd class="col-sm-9">{{ $buku->penulis }}</dd>
            <dt class="col-sm-3">Penerbit</dt><dd class="col-sm-9">{{ $buku->penerbit }}</dd>
            <dt class="col-sm-3">Tahun Terbit</dt><dd class="col-sm-9">{{ $buku->tahun_terbit }}</dd>
            <dt class="col-sm-3">Kategori</dt><dd class="col-sm-9">{{ $buku->kategori->nama_kategori }}</dd>
            <dt class="col-sm-3">Stok</dt><dd class="col-sm-9">{{ $buku->stok }}</dd>
            {{-- ?: '-' artinya kalau sinopsis kosong, tampilkan tanda strip --}}
            <dt class="col-sm-3">Sinopsis</dt><dd class="col-sm-9">{{ $buku->sinopsis ?: '-' }}</dd>
        </dl>
    </div></div>
    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary mt-3">&larr; Kembali</a>
</x-layout>