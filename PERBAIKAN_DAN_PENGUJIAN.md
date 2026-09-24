# POS WEB — Perbaikan dan Pengujian

Project ini sudah dibersihkan dari artefak development/runtime yang dapat mengganggu aplikasi production.

## Perbaikan utama

- Menghapus `public/hot` agar Laravel production tidak mencoba terhubung ke Vite dev server `localhost:5173`.
- Menghapus compiled Blade views dan log runtime lama agar tidak membawa cache/error dari mesin sebelumnya.
- Membersihkan `bootstrap/cache` dari cache package/service lama; Laravel akan membuat ulang saat Composer dijalankan.
- Menambahkan `.gitignore` untuk mencegah `.env`, `vendor`, `node_modules`, cache, log, dan file runtime ikut ter-commit.
- Mengatur timezone default ke `Asia/Jakarta` dan locale default ke Bahasa Indonesia.
- Menambahkan validasi penghapusan user agar user yang masih memiliki histori transaksi atau produk tidak menyebabkan error foreign-key database.
- QR pada metode QRIS diberi label **simulasi**, karena QR generator yang digunakan bukan gateway QRIS sungguhan.

## Pemeriksaan yang dilakukan

- Seluruh file PHP pada `app`, `config`, `database`, `routes`, `bootstrap`, dan `tests` lolos `php -l`.
- Total 75 file PHP diperiksa tanpa syntax error.
- `public/hot` sudah tidak ada.
- Compiled Blade views lama sudah dibersihkan.
- `storage/logs/laravel.log` lama sudah dibersihkan.

## Menjalankan lokal

Di folder project:

```bash
composer install
npm install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run build
php artisan serve
```

Untuk development dengan Vite:

```bash
npm run dev
```

Jika memakai `.env` lokal MySQL, pastikan database `pos_hady` sudah dibuat dan kredensial MySQL benar.

## Akun seed

- Admin: `admin@gmail.com` / `password`
- Kasir: `kasir@gmail.com` / `password`

Sebaiknya password tersebut diganti setelah login untuk penggunaan nyata.

## Catatan deployment

Vercel tetap membutuhkan environment variable production seperti `APP_KEY` dan koneksi MySQL managed database. File `.env.vercel.example` hanya template dan tidak berisi secret asli.
