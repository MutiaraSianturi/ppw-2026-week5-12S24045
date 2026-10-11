<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    private function rules(?Kategori $kategori = null): array
    {
        return [
            'kode_kategori' => ['required', 'string', 'max:10',
                'unique:kategoris,kode_kategori' . ($kategori ? ',' . $kategori->id : '')],
            'nama_kategori' => 'required|string|min:3|max:100',
        ];
    }

    public function index()
    {
        // withCount('bukus') = hitung jumlah buku tiap kategori dalam 1 kueri.
        // Hasilnya dipanggil lewat $kategori->bukus_count
        $kategoris = Kategori::withCount('bukus')->latest()->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        Kategori::create($request->validate($this->rules()));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function show(Kategori $kategori)
    {
        // buku milik kategori ini, dengan pagination
        $bukus = $kategori->bukus()->latest()->paginate(10);
        return view('kategori.show', compact('kategori', 'bukus'));
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->validate($this->rules($kategori)));
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete(); // buku di kategori ini ikut terhapus (cascadeOnDelete di migration)
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}