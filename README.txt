# SISTEM INVENTORY & AKUNTANSI ERP (LARAVEL)

Aplikasi ini adalah Sistem Informasi Inventory & Akuntansi berbasis Laravel dan MySQL.
Dibuat khusus untuk shared hosting (cPanel) tanpa ketergantungan NodeJS/Vite.

## FITUR UTAMA
- **Dashboard**: Statistik ringkas (Item, Stok, Hutang, Piutang) & Grafik Transaksi.
- **Master Data**: Item (Kartu Stok), Supplier, Customer.
- **Pembelian**: Transaksi Invoice, Pembayaran Hutang.
- **Penjualan**: Transaksi Kasir, Pembayaran Piutang.
- **Persediaan**: Masuk, Keluar, Stok Opname, Transfer Gudang.
- **Akuntansi**: Chart of Accounts, Kas Masuk/Keluar/Transfer, Saldo Awal, Buku Besar.
- **Laporan**: Pembelian, Penjualan, Hutang, Piutang, Persediaan, Buku Kas (Filter Tanggal & Print).
- **Pengaturan**: Monitoring PO & Transaksi (Sparepart & Hose).

## PERSYARATAN SERVER
- PHP >= 8.0
- MySQL / MariaDB
- Extension PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML.

## CARA INSTALASI DI CPANEL

### 1. Upload File
1. Compress seluruh folder `inventory_erp` menjadi `inventory_erp.zip`.
2. Login ke cPanel -> File Manager.
3. Upload `inventory_erp.zip` ke folder tujuan (misal: `public_html` atau subfolder).
4. Extract file tersebut.

### 2. Konfigurasi Database
1. Di cPanel, buka **MySQL Database Wizard**.
2. Buat database baru (misal: `u12345_inventory`).
3. Buat user database baru (misal: `u12345_user`) dan passwordnya.
4. Berikan hak akses penuh (All Privileges) user ke database.

### 3. Import Database
Ada 2 cara untuk setup database:

**CARA A: Import SQL Manual (Mudah)**
1. Buka **phpMyAdmin** di cPanel.
2. Pilih database yang baru dibuat.
3. Klik menu **Import**.
4. Pilih file `database.sql` yang ada di root folder aplikasi ini.
5. Klik **Go** / **Kirim**.

**CARA B: Migrasi Laravel (Advanced)**
Jika Anda memiliki akses SSH terminal:
1. `cd /path/to/inventory_erp`
2. `php artisan migrate --seed`

### 4. Konfigurasi Environment (.env)
1. Rename file `.env.example` menjadi `.env`.
2. Edit file `.env` dan sesuaikan konfigurasi database:
   ```
   APP_NAME="Inventory ERP"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=http://domain-anda.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=user_database_anda
   DB_PASSWORD=password_database_anda
   ```
3. Simpan file.

### 5. Setup Key & Storage (Opsional jika error)
Jika Anda tidak bisa menjalankan command terminal, pastikan:
- Folder `storage` dan `bootstrap/cache` memiliki permission 775 atau 777.
- Generate key (jika belum ada di .env) bisa pakai tool online "Laravel Key Generator" lalu copas ke `APP_KEY` di `.env`.

## AKUN LOGIN DEFAULT
- **Email**: `admin@example.com`
- **Password**: `password`

## CATATAN PENTING
- Aplikasi ini menggunakan **Bootstrap 5 CDN**, pastikan server memiliki koneksi internet agar tampilan termuat dengan benar.
- Semua link menggunakan relative path Laravel (`route()`), aman untuk di-deploy di subfolder maupun domain utama.
