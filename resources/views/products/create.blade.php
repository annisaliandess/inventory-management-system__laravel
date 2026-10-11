@extends('layouts.app')

@section('title', 'Tambah Produk Hijab Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 18px; border: 2px solid var(--border-ui) !important;">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--border-ui) !important;">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--primary);">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Produk Hijab Baru
                        </h4>
                        <p class="text-muted small mb-0">Isi formulir berikut untuk mendaftarkan koleksi kerudung baru ke inventori</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="row g-3">
                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label class="form-label">Kategori / Model Hijab <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Model Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supplier / Konveksi -->
                        <div class="col-md-6">
                            <label class="form-label">Supplier / Konveksi Pemasok</label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier (Opsional) --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SKU -->
                        <div class="col-md-6">
                            <label class="form-label">Kode SKU Produk <span class="text-danger">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku') }}" 
                                   class="form-control text-uppercase @error('sku') is-invalid @enderror" 
                                   placeholder="Contoh: HJB-PSH-001" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk Hijab <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Contoh: Pashmina Silk Premium Lilac" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bahan Material -->
                        <div class="col-md-4">
                            <label class="form-label">Bahan / Material</label>
                            <input type="text" name="material" value="{{ old('material') }}" 
                                   class="form-control @error('material') is-invalid @enderror" 
                                   placeholder="Contoh: Ceruty Babydoll, Voal, Jersey">
                            @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div class="col-md-4">
                            <label class="form-label">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-color: #fbcfe8; border-radius: 10px 0 0 10px;">Rp</span>
                                <input type="number" name="price" value="{{ old('price') }}" min="0" step="500" 
                                       class="form-control border-start-0 @error('price') is-invalid @enderror" 
                                       placeholder="45000" style="border-radius: 0 10px 10px 0;" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok Awal -->
                        <div class="col-md-4">
                            <label class="form-label">Stok Awal (Pcs) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi / Catatan Tambahan -->
                        <div class="col-12">
                            <label class="form-label">Keterangan / Deskripsi Produk</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Keterangan ukuran (misal: 180x75 cm), warna khusus, atau karakteristik bahan...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4" style="border-color: var(--border-ui);">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-light rounded-3 px-4 border text-muted fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-custom-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection