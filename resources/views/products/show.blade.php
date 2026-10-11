@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Detail Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 btn-sm mb-2">
                    <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                </a>
                <h3 class="fw-bold mb-0" style="color: var(--primary);">
                    {{ $product->name }}
                </h3>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('transactions.create', ['product_id' => $product->id, 'type' => 'masuk']) }}" class="btn btn-act-in py-2 px-3">
                    <i class="bi bi-plus-lg me-1"></i> Stok Masuk
                </a>
                <a href="{{ route('transactions.create', ['product_id' => $product->id, 'type' => 'keluar']) }}" class="btn btn-act-out py-2 px-3">
                    <i class="bi bi-dash-lg me-1"></i> Stok Keluar
                </a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-act-edit py-2 px-3">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>
            </div>
        </div>

        <!-- Spesifikasi Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px; border: 2px solid var(--border-ui) !important;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3 text-secondary"><i class="bi bi-info-circle me-1"></i> Spesifikasi Produk</h5>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Kode SKU</div>
                        <div class="mt-1"><span class="badge-sku">{{ $product->sku }}</span></div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Kategori</div>
                        <div class="mt-1"><span class="badge-kategori">{{ $product->category->name ?? '-' }}</span></div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Supplier / Konveksi</div>
                        <div class="mt-1 fw-bold text-dark">{{ $product->supplier->name ?? 'Belum Ditentukan' }}</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Bahan / Kain</div>
                        <div class="mt-1 fw-bold text-dark">{{ $product->material ?: '-' }}</div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Harga Jual</div>
                        <div class="mt-1"><span class="harga-text">Rp {{ number_format($product->price, 0, ',', '.') }}</span></div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-muted small fw-semibold">Stok Saat Ini</div>
                        <div class="mt-1">
                            <span class="stok-badge {{ $product->stock <= 5 ? 'stok-menipis' : 'stok-aman' }}">
                                {{ $product->stock }} pcs
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="text-muted small fw-semibold">Deskripsi / Catatan</div>
                        <div class="mt-1 text-secondary">{{ $product->description ?: 'Tidak ada deskripsi khusus.' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Mutasi Terakhir Produk Ini -->
        <div class="card border-0 shadow-sm" style="border-radius: 18px; border: 2px solid var(--border-ui) !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-secondary">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Mutasi Stok Terakhir
                    </h5>
                    <a href="{{ route('transactions.index', ['product_id' => $product->id]) }}" class="small fw-bold text-decoration-none" style="color: var(--primary);">
                        Lihat Semua Mutasi &rarr;
                    </a>
                </div>

                <div class="table-custom-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe Mutasi</th>
                                <th>Jumlah</th>
                                <th>Sebelum &rarr; Sesudah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->transactions as $tx)
                                <tr>
                                    <td>{{ $tx->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        @if($tx->type === 'masuk')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-bold">
                                                <i class="bi bi-arrow-down-left"></i> Masuk (+{{ $tx->amount }})
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold">
                                                <i class="bi bi-arrow-up-right"></i> Keluar (-{{ $tx->amount }})
                                            </span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $tx->amount }} pcs</td>
                                    <td class="text-muted">{{ $tx->stock_before }} &rarr; <span class="fw-bold text-dark">{{ $tx->stock_after }}</span></td>
                                    <td>{{ $tx->note ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat mutasi stok untuk produk ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
