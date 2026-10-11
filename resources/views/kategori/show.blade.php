<x-layout title="Detail Kategori">
    <h2 class="text-del fw-bold">{{ $kategori->nama_kategori }}</h2>
    <p class="text-muted">Kode: {{ $kategori->kode_kategori }}</p>

    <div class="card shadow-sm">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>ISBN</th><th>Judul</th><th>Penulis</th><th>Stok</th></tr></thead>
            <tbody>
                @forelse($bukus as $buku)
                    <tr>
                        <td>{{ $buku->isbn }}</td>
                        <td><a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a></td>
                        <td>{{ $buku->penulis }}</td>
                        <td>{{ $buku->stok }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada buku di kategori ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $bukus->links() }}</div>
    <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary mt-3">&larr; Kembali</a>
</x-layout>