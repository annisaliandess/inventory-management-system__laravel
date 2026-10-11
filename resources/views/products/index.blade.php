@extends('layouts.app')

@section('title', 'Daftar Produk Hijab')

@section('content')
<!-- Ringkasan Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #fce7f3; color: #be185d;">
                <i class="bi bi-box-seam-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Total Koleksi</div>
                <div class="fs-4 fw-extrabold text-dark" style="font-weight: 800;">{{ $totalProducts }} <span class="fs-6 text-muted fw-normal">Item</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #dcfce7; color: #15803d;">
                <i class="bi bi-layers-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Total Stok Fisik</div>
                <div class="fs-4 fw-extrabold text-dark" style="font-weight: 800;">{{ number_format($totalStock, 0, ',', '.') }} <span class="fs-6 text-muted fw-normal">Pcs</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background-color: #fee2e2; color: #b91c1c;">
                <i class="bi bi-exclamation-octagon-fill"></i>
            </div>
            <div>
                <div class="text-muted small fw-bold text-uppercase">Stok Menipis (&le; 5 pcs)</div>
                <div class="fs-4 fw-extrabold text-danger" style="font-weight: 800;">{{ $lowStockCount }} <span class="fs-6 text-muted fw-normal">Item Perlu Restok</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Filter, Pencarian & Sorting -->
<div class="card mb-4 border-0 shadow-none" style="background-color: #fff8f9; border-radius: 14px; border: 1.5px solid var(--border-ui) !important;">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            <!-- Search -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-color: #fbcfe8; border-radius: 10px 0 0 10px;">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" class="form-control border-start-0" 
                           placeholder="Cari nama, SKU, atau bahan..." style="border-radius: 0 10px 10px 0;">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">-- Semua Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sorting Dropdown -->
            <div class="col-md-3">
                <select name="sort" class="form-select">
                    <option value="created_at" {{ $sort == 'created_at' && $direction == 'desc' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                    <option value="price" {{ $sort == 'price' && $direction == 'asc' ? 'selected' : '' }}>Harga: Termurah &rarr; Termahal</option>
                    <option value="name" {{ $sort == 'name' && $direction == 'asc' ? 'selected' : '' }}>Nama: A &rarr; Z</option>
                    <option value="stock" {{ $sort == 'stock' && $direction == 'asc' ? 'selected' : '' }}>Stok: Tersedikit &rarr; Terbanyak</option>
                </select>
            </div>

            <!-- Submit & Reset Button -->
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-custom-primary w-100 justify-content-center">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                @if(!empty($search) || !empty($categoryId) || !empty($supplierId) || $sort !== 'created_at')
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-3 px-3 d-flex align-items-center" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Daftar Produk -->
<div class="table-custom-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">No</th>
                <th>Kode SKU</th>
                <th>Nama Produk Hijab</th>
                <th>Kategori</th>
                <th>Supplier / Konveksi</th>
                <th>Harga Jual</th>
                <th class="text-center">Stok</th>
                <th class="text-center" style="width: 220px;">Aksi Cepat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $index => $item)
                @php
                    $isMenipis = $item->stock <= 5;
                @endphp
                <tr>
                    <td class="text-center text-muted fw-bold">
                        {{ $products->firstItem() + $index }}
                    </td>
                    <td>
                        <span class="badge-sku">{{ $item->sku }}</span>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $item->id) }}" class="text-decoration-none text-dark fw-bold" style="font-size: 14.5px;">
                            {{ $item->name }}
                        </a>
                        @if($item->material)
                            <div class="small text-muted">
                                <i class="bi bi-tag me-1"></i>Bahan: {{ $item->material }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="badge-kategori">{{ $item->category->name ?? '-' }}</span>
                    </td>
                    <td style="color: #4b383c; font-size: 13px;">
                        @if($item->supplier)
                            <span class="badge bg-light text-secondary border px-2 py-1">
                                <i class="bi bi-building me-1"></i>{{ $item->supplier->name }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="harga-text">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stok-badge {{ $isMenipis ? 'stok-menipis' : 'stok-aman' }}">
                            {{ $item->stock }} pcs
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="action-grid">
                            <a href="{{ route('transactions.create', ['product_id' => $item->id, 'type' => 'masuk']) }}" 
                               class="btn-act btn-act-in" title="Catat Stok Masuk">
                                <i class="bi bi-plus-lg me-1"></i> Masuk
                            </a>
                            <a href="{{ route('transactions.create', ['product_id' => $item->id, 'type' => 'keluar']) }}" 
                               class="btn-act btn-act-out" title="Catat Stok Keluar">
                                <i class="bi bi-dash-lg me-1"></i> Keluar
                            </a>
                            <a href="{{ route('products.edit', $item->id) }}" 
                               class="btn-act btn-act-edit" title="Edit Data Produk">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ addslashes($item->name) }}? Data riwayat mutasi terkait juga akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-act btn-act-del w-100" title="Hapus Produk">
                                    <i class="bi bi-trash3 me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <div class="my-3">
                            <i class="bi bi-inbox text-secondary" style="font-size: 3rem;"></i>
                            <div class="fw-bold mt-2" style="font-size: 15px;">Belum ada data produk hijab yang sesuai.</div>
                            <div class="small text-muted mt-1">Silakan tambahkan produk baru atau ubah filter pencarian.</div>
                            <a href="{{ route('products.create') }}" class="btn btn-custom-primary mt-3">
                                <i class="bi bi-plus-circle"></i> Tambah Produk Sekarang
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
@if($products->hasPages())
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-muted small">
            Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
        </div>
        <div>
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
@endsection