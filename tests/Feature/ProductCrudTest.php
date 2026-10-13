<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $staffUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user terautentikasi untuk pengujian (Admin & Staff)
        $this->user = User::factory()->admin()->create([
            'email' => 'admin@toko-hijab.test',
        ]);

        $this->staffUser = User::factory()->staff()->create([
            'email' => 'staff@toko-hijab.test',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_can_authenticate_and_access_products(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Inventori Toko Hijab');
        $response->assertSee('Dashboard Administrator Inventori');
    }

    public function test_staff_can_view_dashboard_with_operational_metrics(): void
    {
        $response = $this->actingAs($this->staffUser)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Staf Gudang &amp; Kasir', false);
        $response->assertSee('Aktivitas Hari Ini');
    }

    public function test_staff_cannot_create_or_delete_product(): void
    {
        $responseCreate = $this->actingAs($this->staffUser)->get(route('products.create'));
        $responseCreate->assertStatus(403);

        $category = Category::firstOrCreate(['name' => 'Khimar']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku'         => 'TEST-STAFF-DEL',
            'name'        => 'Khimar Coba Dihapus Staff',
            'price'       => 50000,
            'stock'       => 5,
        ]);

        $responseDelete = $this->actingAs($this->staffUser)->delete(route('products.destroy', $product->id));
        $responseDelete->assertStatus(403);
    }

    public function test_can_create_and_store_product_with_supplier(): void
    {
        $category = Category::firstOrCreate(['name' => 'Pashmina']);
        $supplier = Supplier::factory()->create(['name' => 'Konveksi Bandung']);

        $payload = [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'sku'         => 'TEST-PSH-999',
            'name'        => 'Pashmina Silk Test',
            'material'    => 'Silk Premium',
            'price'       => 55000,
            'stock'       => 20,
            'description' => 'Untuk pengujian otomatis CRUD',
        ];

        $response = $this->actingAs($this->user)->post(route('products.store'), $payload);
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'sku'         => 'TEST-PSH-999',
            'supplier_id' => $supplier->id,
            'name'        => 'Pashmina Silk Test',
        ]);
    }

    public function test_can_update_product(): void
    {
        $category = Category::firstOrCreate(['name' => 'Pashmina']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku'         => 'TEST-UPD-111',
            'name'        => 'Pashmina Sebelum Update',
            'material'    => 'Ceruty',
            'price'       => 35000,
            'stock'       => 15,
        ]);

        $response = $this->actingAs($this->user)->put(route('products.update', $product->id), [
            'category_id' => $category->id,
            'sku'         => 'TEST-UPD-111',
            'name'        => 'Pashmina Sesudah Update',
            'material'    => 'Ceruty Babydoll Halus',
            'price'       => 40000,
            'stock'       => 25,
            'description' => 'Updated deskripsi',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'name'  => 'Pashmina Sesudah Update',
            'stock' => 25,
        ]);
    }

    public function test_can_record_stock_mutation(): void
    {
        $category = Category::firstOrCreate(['name' => 'Bergo']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku'         => 'TEST-MUTASI-001',
            'name'        => 'Bergo Maryam Test',
            'material'    => 'Jersey',
            'price'       => 30000,
            'stock'       => 10,
        ]);

        // Masuk 5
        $this->actingAs($this->user)->post(route('transactions.store'), [
            'product_id' => $product->id,
            'type'       => 'masuk',
            'amount'     => 5,
            'note'       => 'Restok tambahan',
        ]);

        $product->refresh();
        $this->assertEquals(15, $product->stock);

        // Keluar 3
        $this->actingAs($this->user)->post(route('transactions.store'), [
            'product_id' => $product->id,
            'type'       => 'keluar',
            'amount'     => 3,
            'note'       => 'Terjual di toko',
        ]);

        $product->refresh();
        $this->assertEquals(12, $product->stock);
    }

    public function test_can_delete_product(): void
    {
        $category = Category::firstOrCreate(['name' => 'Khimar']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku'         => 'TEST-DEL-001',
            'name'        => 'Khimar Akan Dihapus',
            'material'    => 'Wolfis',
            'price'       => 65000,
            'stock'       => 5,
        ]);

        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product->id));
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
