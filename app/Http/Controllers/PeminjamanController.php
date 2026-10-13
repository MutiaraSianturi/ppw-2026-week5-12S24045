<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PeminjamanController extends Controller
{
    // Daftar semua peminjaman
    public function index()
    {
        // with('buku') = eager loading, biar tidak N+1
        $peminjamans = Peminjaman::with('buku')->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjamans'));
    }

    // Form pinjam untuk 1 buku
    public function create(int $id)
    {
        $buku = Buku::findOrFail($id);
        return view('peminjaman.create', compact('buku'));
    }

    // PROSES PINJAM (pakai transaksi + lock)
    public function store(Request $request, int $id)
    {
        // Validasi di luar transaksi: kalau input salah, tidak perlu buka transaksi
        $validated = $request->validate([
            'nim_peminjam'  => 'required|string|min:6|max:15',
            'nama_peminjam' => 'required|string|min:3|max:150',
        ]);

        try {
            // DB::transaction = semua langkah di dalam harus sukses semua.
            // Kalau ada exception, SEMUA perubahan dibatalkan (rollback) otomatis.
            // Angka 3 = kalau kena deadlock, coba ulang maksimal 3 kali.
            DB::transaction(function () use ($validated, $id) {

                // lockForUpdate = KUNCI baris buku ini (SELECT ... FOR UPDATE).
                // Orang lain yang mau pinjam buku yang sama harus antre dulu.
                $buku = Buku::where('id', $id)->lockForUpdate()->firstOrFail();

                // Cek stok. Kalau habis, lempar error -> transaksi dibatalkan
                if ($buku->stok < 1) {
                    throw new \RuntimeException("Stok buku \"{$buku->judul}\" habis, tidak bisa dipinjam!");
                }

                $nim = strtoupper(trim($validated['nim_peminjam']));

                // Cegah NIM yang sama meminjam buku yang sama selama belum dikembalikan
                $sudahPinjam = Peminjaman::where('buku_id', $buku->id)
                    ->where('nim_peminjam', $nim)
                    ->where('status', 'dipinjam')
                    ->exists();

                if ($sudahPinjam) {
                    throw new \RuntimeException("NIM {$nim} masih meminjam buku ini dan belum mengembalikannya!");
                }

                // Langkah 1: kurangi stok
                $buku->decrement('stok');

                // Langkah 2: catat peminjaman (jatuh tempo 7 hari)
                Peminjaman::create([
                    'kode_pinjam'         => 'PJM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
                    'buku_id'             => $buku->id,
                    'nim_peminjam'        => $nim,
                    'nama_peminjam'       => $validated['nama_peminjam'],
                    'tanggal_pinjam'      => now()->toDateString(),
                    'tanggal_jatuh_tempo' => now()->addDays(7)->toDateString(),
                    'status'              => 'dipinjam',
                ]);
            }, 3);

            // Simpan peminjam terakhir di SESSION (dipakai buat mengisi otomatis form berikutnya)
            session(['peminjam_terakhir' => [
                'nim'  => strtoupper(trim($validated['nim_peminjam'])),
                'nama' => $validated['nama_peminjam'],
            ]]);

            return redirect()->route('peminjaman.index')
                ->with('success', 'Peminjaman berhasil dicatat, stok buku sudah dikurangi.');

        } catch (\RuntimeException $e) {
            // Error aturan bisnis (stok habis / pinjam ganda): tampilkan pesannya ke user
            return back()->withInput()->with('error', $e->getMessage());

        } catch (QueryException $e) {
            // Error dari database: catat ke log, user cukup lihat pesan umum
            Log::error('[DB EXCEPTION] Pinjam buku: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan database, peminjaman dibatalkan.');
        }
    }

    // PROSES KEMBALIKAN (pakai transaksi + lock)
    public function kembali(int $id)
    {
        try {
            DB::transaction(function () use ($id) {

                // Kunci baris peminjaman
                $pinjam = Peminjaman::where('id', $id)->lockForUpdate()->firstOrFail();

                // Cegah dikembalikan dua kali (kalau tidak, stok bisa bertambah dobel)
                if ($pinjam->status === 'dikembalikan') {
                    throw new \RuntimeException('Buku ini sudah dikembalikan sebelumnya.');
                }

                // Kunci juga baris bukunya sebelum stok ditambah
                $buku = Buku::where('id', $pinjam->buku_id)->lockForUpdate()->firstOrFail();

                // Langkah 1: ubah status dan isi tanggal kembali
                $pinjam->update([
                    'status'          => 'dikembalikan',
                    'tanggal_kembali' => now()->toDateString(),
                ]);

                // Langkah 2: kembalikan stok
                $buku->increment('stok');
            }, 3);

            return back()->with('success', 'Buku berhasil dikembalikan, stok bertambah.');

        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());

        } catch (QueryException $e) {
            Log::error('[DB EXCEPTION] Kembalikan buku: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan database, pengembalian dibatalkan.');
        }
    }
}