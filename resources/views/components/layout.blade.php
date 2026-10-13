<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPUS-Del' }} — Institut Teknologi Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
            <div class="navbar-nav flex-row gap-3 align-items-center">
                <a class="nav-link" href="{{ route('buku.index') }}">Buku</a>
                <a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a>

                {{-- @auth = tampil kalau SUDAH login --}}
                @auth
                    <a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a>
                    <span class="text-white-50 small">
                        {{ Auth::user()->name }}
                        {{-- waktu login diambil dari SESSION --}}
                        @if(session('login_timestamp')) · login {{ session('login_timestamp') }} @endif
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                @endauth

                {{-- @guest = tampil kalau BELUM login --}}
                @guest
                    <a class="btn btn-sm btn-outline-light" href="{{ route('login') }}">Login</a>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        {{-- Flash sukses (hijau) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Flash error (merah): stok habis, login gagal, dan lain-lain --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
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

        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>