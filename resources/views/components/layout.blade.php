{{-- Kerangka semua halaman. Dipanggil dengan <x-layout> --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- $title dikirim dari halaman; kalau tidak ada pakai 'SIPUS-Del' --}}
    <title>{{ $title ?? 'SIPUS-Del' }} — Institut Teknologi Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Tema Del Royal Purple */
        .navbar-del { background-color: #4C1D95; }
        .btn-del { background-color: #4C1D95; color: #fff; }
        .btn-del:hover { background-color: #3B0764; color: #fff; }
        .text-del { color: #4C1D95; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-del shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('buku.index') }}">SIPUS-Del Perpustakaan</a>
            <div class="navbar-nav flex-row gap-3">
                <a class="nav-link" href="{{ route('buku.index') }}">Buku</a>
                <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        {{-- Flash message: muncul kalau controller memakai ->with('success', ...) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Daftar error validasi --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Terdapat kesalahan input:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- $slot = isi halaman yang memakai layout ini --}}
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>