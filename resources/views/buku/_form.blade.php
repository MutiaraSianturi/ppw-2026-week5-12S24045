{{-- Potongan form, dipakai create & edit lewat @include supaya tidak menulis dua kali.
     old('isbn', ...) = isi ulang input kalau validasi gagal.
     $buku->isbn ?? '' = di halaman edit pakai data buku, di create kosong. --}}
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">ISBN (13 digit)</label>
        <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
               value="{{ old('isbn', $buku->isbn ?? '') }}">
        {{-- @error = tampilkan pesan error khusus kolom ini --}}
        @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
               value="{{ old('judul', $buku->judul ?? '') }}">
        @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Penulis</label>
        <input type="text" name="penulis" class="form-control @error('penulis') is-invalid @enderror"
               value="{{ old('penulis', $buku->penulis ?? '') }}">
        @error('penulis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Penerbit</label>
        <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
               value="{{ old('penerbit', $buku->penerbit ?? '') }}">
        @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
               value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}">
        @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach($kategoris as $k)
                {{-- @selected = beri atribut "selected" kalau id-nya cocok --}}
                <option value="{{ $k->id }}" @selected(old('kategori_id', $buku->kategori_id ?? '') == $k->id)>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" min="0" class="form-control @error('stok') is-invalid @enderror"
               value="{{ old('stok', $buku->stok ?? 0) }}">
        @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Sinopsis</label>
        <textarea name="sinopsis" rows="4" class="form-control @error('sinopsis') is-invalid @enderror">{{ old('sinopsis', $buku->sinopsis ?? '') }}</textarea>
        @error('sinopsis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>