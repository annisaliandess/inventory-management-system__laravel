@extends('layouts.app')

@section('title', 'Riwayat & Log Mutasi Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--primary);">
            <i class="bi bi-clock-history me-2"></i> Log Mutasi Barang Masuk & Keluar
        </h4>
        <p class="text-muted small mb-0">Catatan kronologis perubahan dan pergerakan stok barang di inventori</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.create') }}" class="btn btn-custom-mutation">
            <i class="bi bi-plus-lg me-1"></i> Catat Transaksi Baru
        </a>
    </div>
</div>

<!-- Filter Log -->
<div class="card mb-4 border-0 shadow-none" style="background-color: #fff8f9; border-radius: 14px; border: 1.5px solid var(--border-ui) !important;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <select name="product_id" class="form-select">
                    <option value="">-- Semua Produk Hijab --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected' : '' }}>
                            [{{ $p->sku }}] {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">-- Semua Jenis Mutasi --</option>
                    <option value="masuk" {{ $type === 'masuk' ? 'selected' : '' }}>🟢 Barang Masuk (Restok)</option>
                    <option value="keluar" {{ $type === 'keluar' ? 'selected' : '' }}>🔴 Barang Keluar (Terjual/Retur)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-custom-primary w-100 justify-content-center">
                    <i class="bi bi-funnel"></i> Filter Log
                </button>
                @if(!empty($type) || !empty($productId))
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary rounded-3 px-3 d-flex align-items-center" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Log Mutasi -->
<div class="table-custom-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th>Waktu & Tanggal</th>
                <th>Kode SKU</th>
                <th>Nama Produk Hijab</th>
                <th>Tipe Mutasi</th>
                <th class="text-center">Kuantitas</th>
                <th class="text-center">Stok (Sebelum &rarr; Sesudah)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $item)
                <tr>
                    <td class="text-center text-muted fw-bold">
                        {{ $transactions->firstItem() + $index }}
                    </td>
                    <td class="small text-muted fw-semibold" style="white-space: nowrap;">
                        <i class="bi bi-calendar3 me-1"></i> {{ $item->created_at->format('d/m/Y') }}<br>
                        <i class="bi bi-clock me-1"></i> {{ $item->created_at->format('H:i') }} WIB
                    </td>
                    <td>
                        <span class="badge-sku">{{ $item->product->sku ?? '-' }}</span>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $item->product_id) }}" class="text-decoration-none fw-bold text-dark">
                            {{ $item->product->name ?? 'Produk Dihapus' }}
                        </a>
                    </td>
                    <td>
                        @if($item->type === 'masuk')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-bold">
                                <i class="bi bi-arrow-down-left"></i> Masuk (+{{ $item->amount }})
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold">
                                <i class="bi bi-arrow-up-right"></i> Keluar (-{{ $item->amount }})
                            </span>
                        @endif
                    </td>
                    <td class="text-center fw-extrabold fs-6" style="font-weight: 800; color: {{ $item->type === 'masuk' ? '#15803d' : '#b91c1c' }};">
                        {{ $item->type === 'masuk' ? '+' : '-' }}{{ $item->amount }} pcs
                    </td>
                    <td class="text-center">
                        <span class="text-muted">{{ $item->stock_before }}</span>
                        <i class="bi bi-arrow-right mx-1 text-secondary"></i>
                        <span class="fw-bold text-dark">{{ $item->stock_after }}</span> pcs
                    </td>
                    <td class="text-secondary small">
                        {{ $item->note ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <div class="my-3">
                            <i class="bi bi-journal-text text-secondary" style="font-size: 3rem;"></i>
                            <div class="fw-bold mt-2" style="font-size: 15px;">Belum ada riwayat mutasi barang.</div>
                            <div class="small text-muted mt-1">Gunakan tombol "Catat Transaksi Baru" untuk menambah atau mengurangi stok barang.</div>
                            <a href="{{ route('transactions.create') }}" class="btn btn-custom-mutation mt-3">
                                <i class="bi bi-plus-lg"></i> Catat Transaksi Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
@if($transactions->hasPages())
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} riwayat
        </div>
        <div>
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
@endsection
