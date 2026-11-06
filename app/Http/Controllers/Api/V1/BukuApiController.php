<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuApiController extends Controller
{
    // 1. GET: Menampilkan daftar seluruh buku
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Daftar seluruh buku',
            'data' => Buku::with('kategori')->paginate(10)
        ], 200);
    }

    // 2. POST: Menambahkan buku baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'isbn' => 'required|string|unique:bukus',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
        ]);

        $buku = Buku::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    // 3. GET: Menampilkan detail satu buku berdasarkan ID
    public function show($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail buku',
            'data' => $buku
        ], 200);
    }

    // 4. PUT/PATCH: Memperbarui data buku
    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $buku->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    // 5. DELETE: Menghapus buku
    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}