@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 18px; border: 2px solid var(--border-ui) !important;">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--border-ui) !important;">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--btn-edit);">
                            <i class="bi bi-pencil-square me-2"></i> Edit Data Produk Hijab
                        </h4>
                        <p class="text-muted small mb-0">Perbarui spesifikasi, kategori, supplier, harga, atau stok produk</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label class="form-label">Kategori / Model Hijab <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Model Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-6">
                            <label class="form-label">Supplier / Konveksi Pemasok</label>
                            <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">-- Pilih Supplier (Opsional) --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>
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
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                                   class="form-control text-uppercase @error('sku') is-invalid @enderror" 
                                   placeholder="Contoh: HJB-PSH-001" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="col-md-6">
                            <label class="form-label">Nama Produk Hijab <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Contoh: Pashmina Silk Premium Lilac" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bahan Material -->
                        <div class="col-md-4">
                            <label class="form-label">Bahan / Material</label>
                            <input type="text" name="material" value="{{ old('material', $product->material) }}" 
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
                                <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" min="0" step="500" 
                                       class="form-control border-start-0 @error('price') is-invalid @enderror" 
                                       placeholder="45000" style="border-radius: 0 10px 10px 0;" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div class="col-md-4">
                            <label class="form-label">Stok Fisik Saat Ini (Pcs) <span class="text-danger">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   placeholder="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12">
                            <label class="form-label">Keterangan / Deskripsi Produk</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" 
                                      placeholder="Keterangan ukuran, warna khusus, atau karakteristik produk...">{{ old('description', $product->description) }}</textarea>
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
                        <button type="submit" class="btn btn-warning text-white rounded-3 px-4 fw-bold" style="background-color: var(--btn-edit); border-color: var(--btn-edit);">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
