<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    /**
     * Tampilkan form transaksi mutasi barang (masuk / keluar)
     */
    public function create(Request $request)
    {
        $selectedId = $request->query('product_id', 0);
        $defaultType = in_array($request->query('type'), ['masuk', 'keluar']) ? $request->query('type') : 'masuk';

        $products = Product::orderBy('name')->get();

        return view('transactions.create', compact('products', 'selectedId', 'defaultType'));
    }

    /**
     * Simpan mutasi stok produk (masuk / keluar) dengan Database Transaction
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:masuk,keluar',
            'amount'     => 'required|integer|min:1',
            'note'       => 'nullable|string|max:255',
        ], [
            'product_id.required' => 'Pilih produk yang akan dimutasi.',
            'type.required'       => 'Pilih jenis mutasi (Masuk atau Keluar).',
            'amount.min'          => 'Jumlah minimal 1 pcs.',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $stockBefore = (int) $product->stock;
            $amount = (int) $validated['amount'];

            if ($validated['type'] === 'masuk') {
                $stockAfter = $stockBefore + $amount;
            } else {
                if ($stockBefore < $amount) {
                    return back()
                        ->withInput()
                        ->withErrors(['amount' => "Stok tidak mencukupi! Stok saat ini: {$stockBefore} pcs, diminta keluar: {$amount} pcs."]);
                }
                $stockAfter = $stockBefore - $amount;
            }

            // Update stok produk
            $product->stock = $stockAfter;
            $product->save();

            // Catat log transaksi stok
            StockTransaction::create([
                'product_id'   => $product->id,
                'type'         => $validated['type'],
                'amount'       => $amount,
                'stock_before' => $stockBefore,
                'stock_after'  => $stockAfter,
                'note'         => $validated['note'] ?? null,
            ]);

            DB::commit();

            $tipeLabel = $validated['type'] === 'masuk' ? 'penambahan (masuk)' : 'pengurangan (keluar)';
            return redirect()->route('products.index')
                ->with('success', "✅ Berhasil mencatat mutasi {$tipeLabel} untuk '{$product->name}'. Stok sekarang: {$stockAfter} pcs.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'Gagal memproses transaksi: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan riwayat / log mutasi stok
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $productId = $request->query('product_id');

        $query = StockTransaction::with('product')->latest();

        if (in_array($type, ['masuk', 'keluar'])) {
            $query->where('type', $type);
        }

        if (!empty($productId)) {
            $query->where('product_id', $productId);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'products', 'type', 'productId'));
    }
}
