<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanApiController extends Controller
{
    // Tampilkan daftar peminjaman
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Peminjaman::with('buku')->paginate(10)
        ], 200);
    }

    // Proses Peminjaman Buku (Menerapkan ACID Transaction)
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim' => 'required|string',
            'nama_peminjam' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            // Ambil data buku dengan pengaman lock database
            $buku = Buku::lockForUpdate()->find($request->buku_id);

            // Cek apakah stok habis
            if ($buku->stok <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok buku habis'
                ], 422);
            }

            // Kurangi stok buku
            $buku->stok -= 1;
            $buku->save();

            // Buat data peminjaman
            $peminjaman = Peminjaman::create([
                'buku_id' => $request->buku_id,
                'nim' => $request->nim,
                'nama_peminjam' => $request->nama_peminjam,
                'tanggal_pinjam' => now(),
                'status' => 'dipinjam'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dipinjam',
                'data' => $peminjaman
            ], 201);
        });
    }

    // Pengembalian Buku
    public function update(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::find($id);

            if (!$peminjaman || $peminjaman->status === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman tidak valid atau sudah dikembalikan'
                ], 409);
            }

            // Kembalikan status dan tambah stok buku kembali
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_kembali = now();
            $peminjaman->save();

            $buku = Buku::find($peminjaman->buku_id);
            if ($buku) {
                $buku->stok += 1;
                $buku->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dikembalikan',
                'data' => $peminjaman
            ], 200);
        });
    }
}