<x-layout title="Login Pustakawan">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm"><div class="card-body p-4">
                <h3 class="text-del fw-bold mb-3">Login Pustakawan</h3>
                <form action="{{ route('login.attempt') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        {{-- Kalau tidak ada old(), isi dari COOKIE email terakhir --}}
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', request()->cookie('del_last_user_email')) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                        <label for="remember" class="form-check-label">Ingat saya</label>
                    </div>
                    <button class="btn btn-del w-100">Masuk</button>
                </form>
                <p class="text-muted small mt-3 mb-0">Akun uji: pustakawan@del.ac.id / password123</p>
            </div></div>
        </div>
    </div>
</x-layout>