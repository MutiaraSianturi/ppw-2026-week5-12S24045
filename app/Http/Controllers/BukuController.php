<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Aturan validasi di satu tempat supaya tidak ditulis dua kali (store & update).
    // $buku hanya terisi saat update.
    private function rules(?Buku $buku = null): array
    {
        return [
            // Saat update, ISBN milik buku itu sendiri jangan dianggap duplikat.
            // ",id" di belakang artinya: abaikan baris dengan id ini.
            'isbn'         => ['required', 'digits:13', 'unique:bukus,isbn' . ($buku ? ',' . $buku->id : '')],
            'judul'        => 'required|string|min:5|max:200',   // minimal 5 karakter (syarat tugas)
            'penulis'      => 'required|string|max:150',
            'penerbit'     => 'required|string|max:150',
            'tahun_terbit' => 'required|integer|between:1900,' . date('Y'),
            'kategori_id'  => 'required|exists:kategoris,id',    // harus ada di tabel kategoris
            'stok'         => 'required|integer|min:0',          // minimal 0 (syarat tugas)
            'sinopsis'     => 'nullable|string',                 // boleh kosong
        ];
    }

    // INDEX: tampilkan daftar buku
    public function index(Request $request)
{
    // Simpan kata kunci pencarian terakhir di SESSION
    if ($request->filled('search')) {
        session(['last_search' => $request->search]);
    }

    $bukus = Buku::with('kategori')            // eager loading kategori (anti N+1)
        ->withCount('peminjamans')             // hitung peminjaman per buku dalam 1 kueri -> $buku->peminjamans_count
        // when() = filter hanya dipasang kalau inputnya ada
        ->when($request->search, function ($q, $s) {
            // Dibungkus function supaya orWhere tidak merusak filter kategori
            $q->where(function ($sub) use ($s) {
                $sub->where('judul', 'like', "%{$s}%")
                    ->orWhere('penulis', 'like', "%{$s}%")
                    ->orWhere('isbn', 'like', "%{$s}%");
            });
        })
        ->when($request->kategori_id, fn ($q, $k) => $q->where('kategori_id', $k))
        ->latest()
        ->paginate(10)
        ->withQueryString();                   // link halaman 2, 3 tetap membawa kata kunci

    // Statistik pakai Query Builder (DB::table): cepat, tanpa Model
    $stats = [
        'total_judul'     => DB::table('bukus')->count(),
        'total_stok'      => (int) DB::table('bukus')->sum('stok'),
        'sedang_dipinjam' => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
        'dikembalikan'    => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
    ];

    $kategoris = Kategori::orderBy('nama_kategori')->get(); // buat dropdown filter

    return view('buku.index', compact('bukus', 'stats', 'kategoris'));
}

    // CREATE: form tambah (butuh daftar kategori untuk dropdown)
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    // STORE: simpan data dari form
    public function store(Request $request)
    {
        // Kalau validasi gagal, Laravel otomatis kembali ke form membawa pesan error
        $validated = $request->validate($this->rules());

        Buku::create($validated); // hanya kolom di $fillable yang masuk

        // with('success') = flash message, hilang setelah halaman dimuat ulang
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // SHOW: detail 1 buku.
    // Route Model Binding: Laravel otomatis mencari Buku berdasarkan id di URL
    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    // EDIT: form edit
    public function edit(Buku $buku)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    // UPDATE: simpan perubahan
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate($this->rules($buku)); // kirim $buku untuk aturan unique
        $buku->update($validated);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    // DESTROY: hapus buku
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}