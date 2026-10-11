<x-layout title="Daftar Buku">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-del fw-bold">Daftar Buku</h2>
        <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>ISBN</th><th>Judul</th><th>Penulis</th>
                        <th>Kategori</th><th>Stok</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @forelse = foreach + penanganan data kosong (@empty) --}}
                    @forelse($bukus as $buku)
                        <tr>
                            {{-- nomor urut tetap lanjut di halaman 2, 3, dst --}}
                            <td>{{ $bukus->firstItem() + $loop->index }}</td>
                            <td>{{ $buku->isbn }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            {{-- kategori sudah di-load lewat with(), jadi tidak memicu kueri baru --}}
                            <td><span class="badge bg-secondary">{{ $buku->kategori->nama_kategori }}</span></td>
                            <td>{{ $buku->stok }}</td>
                            <td class="text-end">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf              {{-- token anti CSRF, wajib di setiap form --}}
                                    @method('DELETE')  {{-- HTML hanya GET/POST, ini "menyamar" jadi DELETE --}}
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- tampil kalau tidak ada data --}}
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- tombol halaman 1,2,3... otomatis dari paginate() --}}
    <div class="mt-3">{{ $bukus->links() }}</div>
</x-layout>