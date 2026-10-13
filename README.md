# 🌸 Sistem Inventori Toko Hijab

Aplikasi web manajemen inventori produk toko hijab dan kerudung modern berbasis **Laravel 12** dan **MySQL**. Dibangun dengan arsitektur MVC yang bersih, sistem autentikasi bawaan Laravel (*Auth Layer*), relasi Eloquent (*Category*, *Supplier*, *Product*, *StockTransaction*), penanganan *N+1 Query* (Eager Loading), serta fitur CRUD dan mutasi stok lengkap.

---

## 📌 Daftar Isi
- [1. Ringkasan & Kesesuaian Kriteria Evaluasi (P5)](#1-ringkasan--kesesuaian-kriteria-evaluasi-p5)
- [2. Arsitektur MVC & Penjelasan Komponen](#2-arsitektur-mvc--penjelasan-komponen)
- [3. Struktur Database & Relasi (ERD)](#3-struktur-database--relasi-erd)
- [4. Fitur Utama & Validasi Bisnis](#4-fitur-utama--validasi-bisnis)
- [5. Daftar Rute (Route List)](#5-daftar-rute-route-list)
- [6. Alur Operasional Sistem (Demonstrasi Alur Lengkap)](#6-alur-operasional-sistem-demonstrasi-alur-lengkap)
- [7. Panduan Instalasi & Akun Pengujian](#7-panduan-instalasi--akun-pengujian)
- [8. Pengujian Otomatis (Automated Testing)](#8-pengujian-otomatis-automated-testing)

---

## 1. Ringkasan & Kesesuaian Kriteria Evaluasi (P5)

Berdasarkan laporan evaluasi dan checklist P5, seluruh komponen wajib telah dipenuhi 100%:

| Komponen Evaluasi | Status | Keterangan Implementasi |
| :--- | :---: | :--- |
| **Authentication Layer** | ✅ **Selesai** | `AuthController.php`, view `auth/login.blade.php`, dan middleware `auth` & `guest`. |
| **Model & Migrasi Supplier** | ✅ **Selesai** | `Supplier.php`, migrasi `create_suppliers_table`, serta relasi FK di tabel `products`. |
| **Factory untuk Data Dummy** | ✅ **Selesai** | `CategoryFactory.php`, `SupplierFactory.php`, dan `ProductFactory.php` menggunakan Faker. |
| **Keamanan Form CSRF** | ✅ **Selesai** | Seluruh form (`create`, `edit`, `login`, `logout`, `destroy`) menggunakan directive `@csrf`. |
| **Validasi & Feedback Form** | ✅ **Selesai** | `$request->validate()` di controller & feedback `@error` di bawah setiap input Blade. |
| **Search, Filter & Sorting** | ✅ **Selesai** | Pencarian teks, filter dropdown kategori & supplier, serta sorting (harga, nama, stok, tanggal). |
| **Pagination with Query String** | ✅ **Selesai** | `$query->paginate(10)->withQueryString()` mempertahankan filter saat berpindah halaman. |
| **Eager Loading (Anti N+1)** | ✅ **Selesai** | Menggunakan `Product::with(['category', 'supplier'])` di `ProductController`. |
| **Clean Architecture** | ✅ **Selesai** | `routes/web.php` hanya berisi routing, logika bisnis di Controller/Model, view hanya presentasi. |

---

## 2. Arsitektur MVC & Penjelasan Komponen

Aplikasi ini membagi tanggung jawab ke dalam 3 lapisan arsitektur utama (**Model-View-Controller**):

1. **Model (Lapisan Data & Relasi)**:
   - `app/Models/User.php`: Pengguna sistem yang memiliki hak akses.
   - `app/Models/Category.php`: Kategori kerudung (Pashmina, Segi Empat, Bergo, Khimar, dll.). Relasi `hasMany(Product::class)`.
   - `app/Models/Supplier.php`: Pihak konveksi/grosir pemasok hijab. Relasi `hasMany(Product::class)`.
   - `app/Models/Product.php`: Master produk hijab. Terhubung ke `Category`, `Supplier`, dan riwayat `StockTransaction`.
   - `app/Models/StockTransaction.php`: Log pergerakan mutasi stok barang (masuk/keluar).

2. **Controller (Lapisan Logika Bisnis & Alur)**:
   - `AuthController.php`: Mengelola alur login, validasi kredensial, regenerasi session, dan logout.
   - `ProductController.php`: Mengelola katalog produk, pencarian, filter kategori, sorting aman (whitelisting), validasi input, serta operasi CRUD.
   - `StockTransactionController.php`: Mengelola pencatatan transaksi mutasi stok dengan transaksi database atomik (`DB::beginTransaction()`, `lockForUpdate()`, dan `DB::commit()`).

3. **View (Lapisan Antarmuka Pengguna)**:
   - `resources/views/layouts/app.blade.php`: Master template dengan palet *soft pink*, responsif, serta indikator profil user login.
   - `resources/views/auth/login.blade.php`: Halaman login bersih dan elegan.
   - `resources/views/products/`: Berkas `index.blade.php`, `create.blade.php`, `edit.blade.php`, dan `show.blade.php`.
   - `resources/views/transactions/`: Berkas `create.blade.php` (form mutasi) dan `index.blade.php` (log riwayat).

---

## 3. Struktur Database & Relasi (ERD)

Database: `db_toko_hijab_laravel` (MySQL InnoDB)

```text
+-------------------+        1 : N        +-----------------------+        1 : N        +-----------------------+
|    categories     | -------------------< |       products        | >------------------- |       suppliers       |
+-------------------+                      +-----------------------+                      +-----------------------+
| PK id             |                      | PK id                 |                      | PK id                 |
|    name           |                      | FK category_id        |                      |    name               |
|    created_at     |                      | FK supplier_id (NULL) |                      |    contact_person     |
|    updated_at     |                      |    sku (UNIQUE)       |                      |    phone              |
+-------------------+                      |    name               |                      |    email              |
                                           |    material           |                      |    address            |
                                           |    price              |                      |    created_at         |
                                           |    stock              |                      |    updated_at         |
                                           |    description        |                      +-----------------------+
                                           |    created_at         |
                                           |    updated_at         |
                                           +-----------------------+
                                                       |
                                                       | 1 : N
                                                       v
                                           +-----------------------+
                                           |  stock_transactions   |
                                           +-----------------------+
                                           | PK id                 |
                                           | FK product_id         |
                                           |    type (masuk/keluar)|
                                           |    amount             |
                                           |    stock_before       |
                                           |    stock_after        |
                                           |    note               |
                                           |    created_at         |
                                           |    updated_at         |
                                           +-----------------------+
```

---

## 4. Fitur Utama & Validasi Bisnis

1. **Proteksi Autentikasi**:
   - Semua halaman inventori dilindungi oleh middleware `auth`. Tamu (*guest*) yang membuka `/products` akan otomatis dialihkan ke `/login`.
2. **Pencegahan Duplikasi SKU**:
   - Kode SKU divalidasi `unique:products,sku` sehingga tidak akan terjadi nomor SKU ganda.
3. **Pencegahan Stok Minus**:
   - Transaksi barang keluar divalidasi secara ketat. Jika stok saat ini kurang dari jumlah yang diminta keluar, transaksi otomatis dibatalkan (*rollback*) dengan notifikasi error.
4. **Pencarian, Filter, dan Sorting Fleksibel**:
   - Pencarian multi-kolom (nama, SKU, material kain).
   - Filter berdasarkan kategori dan supplier.
   - Sorting berdasarkan harga, stok, nama produk, dan tanggal input.

---

## 5. Daftar Rute (Route List)

Hasil eksekusi `php artisan route:list`:

```text
+-----------+-----------------------+---------------------+-------------------------------------------------------+
| Method    | URI                   | Name                | Action                                                |
+-----------+-----------------------+---------------------+-------------------------------------------------------+
| GET|HEAD  | login                 | login               | App\Http\Controllers\AuthController@showLoginForm     |
| POST      | login                 | -                   | App\Http\Controllers\AuthController@login             |
| POST      | logout                | logout              | App\Http\Controllers\AuthController@logout            |
| GET|HEAD  | /                     | -                   | Redirect ke products.index                            |
| GET|HEAD  | products              | products.index      | App\Http\Controllers\ProductController@index          |
| POST      | products              | products.store      | App\Http\Controllers\ProductController@store          |
| GET|HEAD  | products/create       | products.create     | App\Http\Controllers\ProductController@create         |
| GET|HEAD  | products/{product}    | products.show       | App\Http\Controllers\ProductController@show           |
| PUT|PATCH | products/{product}    | products.update     | App\Http\Controllers\ProductController@update         |
| DELETE    | products/{product}    | products.destroy    | App\Http\Controllers\ProductController@destroy        |
| GET|HEAD  | products/{product}/edit| products.edit      | App\Http\Controllers\ProductController@edit          |
| GET|HEAD  | transactions          | transactions.index  | App\Http\Controllers\StockTransactionController@index |
| POST      | transactions          | transactions.store  | App\Http\Controllers\StockTransactionController@store |
| GET|HEAD  | transactions/create   | transactions.create | App\Http\Controllers\StockTransactionController@create|
+-----------+-----------------------+---------------------+-------------------------------------------------------+
```

---

## 6. Alur Operasional Sistem (Demonstrasi Alur Lengkap)

1. **Akses Awal & Login**:
   - Pengguna membuka `http://127.0.0.1:8000/`.
   - Sistem mendeteksi status belum login dan mengarahkan ke `/login`.
   - Masukkan kredensial pengujian (`admin@tokohijab` / `password123`).
2. **Katalog & Filter Data**:
   - Setelah login, pengguna melihat dashboard katalog produk, kartu statistik stok, filter kategori, pencarian, dan tombol aksi.
3. **Validasi For
m Tambah Produk**:
   - Klik tombol **"+ Tambah Produk"**.
   - Jika pengguna sengaja mengosongkan kolom wajib atau memasukkan SKU yang sudah ada, Laravel akan mengembalikan pesan error validasi spesifik di bawah kolom input (`@error`).
   - Jika data valid, produk tersimpan dan muncul notifikasi flash alert sukses.
4. **Mutasi Stok Cepat (In/Out)**:
   - Klik tombol `+ Masuk` atau `- Keluar` pada baris produk di tabel.
   - Form otomatis memuat produk terpilih. Masukkan jumlah dan alasan mutasi, lalu simpan.
   - Riwayat tercatat secara otomatis di tabel log mutasi.

---

## 7. Panduan Instalasi & Akun Pengujian

### Langkah Instalasi
```bash
# 1. Masuk ke direktori
cd "C:\xampp\htdocs\inventory-management-system"

# 2. Jalankan migrasi seluruh tabel (users, categories, suppliers, products, stock_transactions)
php artisan migrate

# 3. Jalankan Database Seeder (Membuat akun default, kategori, supplier, dan data dummy)
php artisan db:seed

# 4. Jalankan Server
php artisan serve
```

### Akun Uji Coba Default
- **Administrator (Akses Penuh CRUD, Nilai Aset, Mutasi)**:
  - **Email**: `admin@tokohijab`
  - **Password**: `password123`
  - **Peran**: `admin`
- **Staff (Operasional Kasir / Gudang: Mutasi Stok, Monitoring, Search)**:
  - **Email**: `staff@tokohijab` 
  - **Password**: `password123`
  - **Peran**: `staff`

---

## 8. Pengujian Otomatis (Automated Testing)

Semua skenario pengujian feature dan unit telah diuji dan lulus (100% PASS), termasuk pengujian otorisasi peran Admin & Staff:

```bash
php artisan test
```

**Output Terminal:**
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                   0.01s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                       0.35s  

   PASS  Tests\Feature\ProductCrudTest
  ✓ guest is redirected to login                                                        0.24s  
  ✓ can authenticate and access products                                                0.06s  
  ✓ staff can view dashboard with operational metrics                                   0.03s  
  ✓ staff cannot create or delete product                                               0.05s  
  ✓ can create and store product with supplier                                          0.04s  
  ✓ can update product                                                                  0.03s  
  ✓ can record stock mutation                                                           0.03s  
  ✓ can delete product                                                                  0.03s  

  Tests:    10 passed (24 assertions)
  Duration: 1.14s
```
