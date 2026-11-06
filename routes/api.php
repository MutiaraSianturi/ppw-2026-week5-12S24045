<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanApiController extends Controller
{
    // GET: Tampilkan daftar peminjaman
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Peminjaman::with('buku')->paginate(10)
        ], 200);
    }

    // POST: Proses Peminjaman Buku (Menerapkan Transaksi ACID)
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'nim' => 'required|string',
            'nama_peminjam' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) {
            $buku = Buku::lockForUpdate()->find($request->buku_id);

            if ($buku->stok <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok buku habis'
                ], 422);
            }

            $buku->stok -= 1;
            $buku->save();

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

    // PATCH: Pengembalian Buku
    public function kembalikan(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::find($id);

            if (!$peminjaman || $peminjaman->status === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjaman tidak valid atau sudah dikembalikan'
                ], 409);
            }

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