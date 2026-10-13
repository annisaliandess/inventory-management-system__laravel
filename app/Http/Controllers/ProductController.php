<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with Search, Filter, Sorting & Eager Loading.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $supplierId = $request->query('supplier_id');
        $sort = $request->query('sort', 'created_at');
        $direction = strtolower($request->query('direction', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Whitelisting kolom sorting untuk keamanan
        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'sku'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }

        // Eager Loading 'category' dan 'supplier' untuk mencegah N+1 Query Problem
        $query = Product::with(['category', 'supplier']);

        // Fitur Pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('material', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Kategori
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Fitur Filter Supplier
        if (!empty($supplierId)) {
            $query->where('supplier_id', $supplierId);
        }

        // Fitur Sorting
        $query->orderBy($sort, $direction);

        // Pagination dengan parameter URL dipertahankan (withQueryString)
        $products = $query->paginate(10)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        // Statistik ringkas inventori
        $totalProducts = Product::count();
        $totalStock = (int) Product::sum('stock');
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        // Metrik khusus Admin: Estimasi total nilai aset inventori (stock * price)
        $totalAssetValue = (float) Product::selectRaw('SUM(stock * price) as total_val')->value('total_val') ?? 0;

        // Metrik operasional Staf & Admin: Mutasi hari ini (barang masuk & keluar)
        $todayIn = (int) \App\Models\StockTransaction::whereDate('created_at', today())
            ->where('type', 'masuk')
            ->sum('amount');
        $todayOut = (int) \App\Models\StockTransaction::whereDate('created_at', today())
            ->where('type', 'keluar')
            ->sum('amount');

        return view('products.index', compact(
            'products',
            'categories',
            'suppliers',
            'search',
            'categoryId',
            'supplierId',
            'sort',
            'direction',
            'totalProducts',
            'totalStock',
            'lowStockCount',
            'totalAssetValue',
            'todayIn',
            'todayOut'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sku'         => 'required|string|max:50|unique:products,sku',
            'name'        => 'required|string|max:150',
            'material'    => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ], [
            'sku.unique'           => 'Kode SKU sudah terdaftar, gunakan kode lain.',
            'category_id.required' => 'Pilih kategori produk hijab.',
            'name.required'        => 'Nama produk hijab wajib diisi.',
            'price.required'       => 'Harga produk wajib diisi.',
            'price.min'            => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required'       => 'Jumlah stok awal wajib diisi.',
            'stock.min'            => 'Jumlah stok tidak boleh bernilai negatif.',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', '✨ Koleksi produk hijab baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Eager Loading relasi
        $product->load(['category', 'supplier', 'transactions' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sku'         => 'required|string|max:50|unique:products,sku,' . $product->id,
            'name'        => 'required|string|max:150',
            'material'    => 'nullable|string|max:100',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
        ], [
            'sku.unique'           => 'Kode SKU sudah digunakan oleh produk lain.',
            'category_id.required' => 'Pilih kategori produk hijab.',
            'name.required'        => 'Nama produk hijab wajib diisi.',
            'price.required'       => 'Harga produk wajib diisi.',
            'price.min'            => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required'       => 'Jumlah stok wajib diisi.',
            'stock.min'            => 'Jumlah stok tidak boleh bernilai negatif.',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', '✨ Rincian produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return redirect()->route('products.index')
            ->with('info', "🗑️ Produk '{$productName}' berhasil dihapus dari inventori.");
    }
}
