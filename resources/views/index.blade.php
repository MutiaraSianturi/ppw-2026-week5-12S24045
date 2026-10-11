<x-layout title="Daftar Kategori">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-del fw-bold">Daftar Kategori</h2>
        <a href="{{ route('kategori.create') }}" class="btn btn-del">+ Tambah Kategori</a>
    </div>

    <div class="card shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Kode</th><th>Nama Kategori</th><th>Jumlah Buku</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($kategoris as $kategori)
                    <tr>
                        <td>{{ $kategoris->firstItem() + $loop->index }}</td>
                        <td>{{ $kategori->kode_kategori }}</td>
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td>{{ $kategori->bukus_count }}</td> {{-- berasal dari withCount('bukus') --}}
                        <td class="text-end">
                            <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Menghapus kategori juga menghapus semua bukunya. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $kategoris->links() }}</div>
</x-layout>