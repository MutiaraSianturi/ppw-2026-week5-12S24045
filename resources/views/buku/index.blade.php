<x-layout title="Daftar Buku">
    {{-- ===== Kartu statistik (angkanya dari DB::table di controller) ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Judul Buku</div>
            <div class="fs-3 fw-bold text-del">{{ $stats['total_judul'] }}</div>
        </div></div></div>
        <div class="col-6 col-md-3"><div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Total Stok Tersedia</div>
            <div class="fs-3 fw-bold text-del">{{ $stats['total_stok'] }}</div>
        </div></div></div>
        <div class="col-6 col-md-3"><div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Sedang Dipinjam</div>
            <div class="fs-3 fw-bold text-del">{{ $stats['sedang_dipinjam'] }}</div>
        </div></div></div>
        <div class="col-6 col-md-3"><div class="card shadow-sm"><div class="card-body">
            <div class="text-muted small">Sudah Dikembalikan</div>
            <div class="fs-3 fw-bold text-del">{{ $stats['dikembalikan'] }}</div>
        </div></div></div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-del fw-bold mb-0">Daftar Buku</h2>
        {{-- @auth = hanya tampil kalau sudah login --}}
        @auth
            <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
        @endauth
    </div>

    {{-- ===== Form pencarian dan filter (method GET supaya URL bisa dibagikan) ===== --}}
    <form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari judul / penulis / ISBN" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="kategori_id" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-del">Cari</button>
            <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    {{-- Info pencarian terakhir yang tersimpan di SESSION --}}
    @if(session('last_search') && !request('search'))
        <div class="small text-muted mb-2">
            Pencarian terakhir Anda:
            <a href="{{ route('buku.index', ['search' => session('last_search')]) }}">{{ session('last_search') }}</a>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>ISBN</th><th>Judul</th><th>Penulis</th>
                        <th>Kategori</th><th>Stok</th><th>Dipinjam</th><th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td>{{ $bukus->firstItem() + $loop->index }}</td>
                            <td>{{ $buku->isbn }}</td>
                            <td>{{ $buku->judul }}</td>
                            <td>{{ $buku->penulis }}</td>
                            <td><span class="badge bg-secondary">{{ $buku->kategori->nama_kategori }}</span></td>
                            {{-- Stok 0 = merah, ada = hijau --}}
                            <td><span class="badge {{ $buku->stok > 0 ? 'bg-success' : 'bg-danger' }}">{{ $buku->stok }}</span></td>
                            {{-- peminjamans_count berasal dari withCount('peminjamans') --}}
                            <td>{{ $buku->peminjamans_count }}x</td>
                            <td class="text-end">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                {{-- Tombol di bawah hanya tampil kalau sudah login --}}
                                @auth
                                    <a href="{{ route('buku.pinjam.form', $buku->id) }}" class="btn btn-sm btn-del">Pinjam</a>
                                    <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $bukus->links() }}</div>
</x-layout>