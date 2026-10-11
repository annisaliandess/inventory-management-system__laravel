@extends('layouts.app')

@section('title', 'Catat Mutasi Stok Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius: 18px; border: 2px solid var(--border-ui) !important;">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--border-ui) !important;">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: #059669;">
                            <i class="bi bi-arrow-left-right me-2"></i> Transaksi Mutasi Barang
                        </h4>
                        <p class="text-muted small mb-0">Catat penambahan (barang masuk/kulakan) atau pengurangan (terjual/rusak)</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('transactions.store') }}" method="POST" autocomplete="off">
                    @csrf

                    <!-- Pilih Produk -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Produk Hijab <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_select" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" 
                                        data-stock="{{ $p->stock }}" 
                                        data-sku="{{ $p->sku }}"
                                        {{ old('product_id', $selectedId) == $p->id ? 'selected' : '' }}>
                                    [{{ $p->sku }}] {{ $p->name }} &mdash; (Stok Sekarang: {{ $p->stock }} pcs)
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Mutasi -->
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Transaksi <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check p-3 border rounded-3 flex-fill" style="background: #f0fdf4; border-color: #86efac !important;">
                                <input class="form-check-input ms-0 me-2" type="radio" name="type" id="type_in" value="masuk" 
                                       {{ old('type', $defaultType) === 'masuk' ? 'checked' : '' }} required>
                                <label class="form-check-label fw-bold text-success cursor-pointer" for="type_in">
                                    <i class="bi bi-box-arrow-in-down me-1"></i> Barang Masuk (Restok)
                                </label>
                            </div>
                            <div class="form-check p-3 border rounded-3 flex-fill" style="background: #fef2f2; border-color: #fca5a5 !important;">
                                <input class="form-check-input ms-0 me-2" type="radio" name="type" id="type_out" value="keluar" 
                                       {{ old('type', $defaultType) === 'keluar' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-danger cursor-pointer" for="type_out">
                                    <i class="bi bi-box-arrow-up me-1"></i> Barang Keluar (Terjual/Retur)
                                </label>
                            </div>
                        </div>
                        @error('type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah / Kuantitas -->
                    <div class="mb-3">
                        <label class="form-label">Jumlah / Kuantitas (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', 1) }}" min="1" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               placeholder="Jumlah barang..." required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Catatan / Keterangan -->
                    <div class="mb-4">
                        <label class="form-label">Keterangan / Alasan Mutasi</label>
                        <input type="text" name="note" value="{{ old('note') }}" 
                               class="form-control @error('note') is-invalid @enderror" 
                               placeholder="Contoh: Pasokan konveksi baru, Penjualan toko fisik, Pesanan online Shopee, dll.">
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-light rounded-3 px-4 border text-muted fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-custom-mutation px-4">
                            <i class="bi bi-check-circle me-1"></i> Proses Transaksi Mutasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
