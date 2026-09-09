# POS HADI

Aplikasi Point of Sale (POS) berbasis Laravel 12 untuk pengelolaan produk, kategori,
transaksi, pengguna, laporan, profil, dan pengaturan toko.

## Urutan penggunaan aplikasi

1. **Login** — autentikasi dan session.
2. **Dashboard** — ringkasan stok dan penjualan.
3. **Penjualan** — buka transaksi, pilih produk, ubah/hapus item, lalu checkout.
4. **Produk** — kelola katalog, harga, stok, foto, kategori, dan status.
5. **Kategori** — kelola kategori (Admin).
6. **Laporan** — filter transaksi selesai dan export CSV.
7. **Users** — kelola akun dan role (Admin).
8. **Profil Saya** — ubah nama, email, foto, dan password.
9. **Pengaturan** — identitas toko, logo, batas stok, dan footer struk (Admin).
10. **Keluar** — logout dan invalidasi session.

## Role

- **Admin:** akses seluruh modul.
- **Kasir:** Dashboard, Penjualan, Produk, Laporan, dan Profil. Data transaksi
  dibatasi pada transaksi miliknya.

## Instalasi lokal

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Atur koneksi database pada `.env`, kemudian:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Seeder membuat akun awal:
- `admin@gmail.com` / `password`
- `kasir@gmail.com` / `password`

Ganti password akun tersebut setelah instalasi.

## Lupa kata sandi

Fitur reset password sudah menggunakan password broker Laravel dan tabel
`password_reset_tokens`. Untuk development dengan mailer `log`, isi email reset
ditulis ke log aplikasi, bukan dikirim ke inbox.

Untuk pengiriman email sungguhan, konfigurasi `MAIL_MAILER`, `MAIL_HOST`,
`MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, dan
`MAIL_FROM_NAME` di `.env`.

## Catatan verifikasi

Audit source code mencakup route/controller, model-relasi, migration, policy,
validasi form, Blade route references, serta PHP syntax. PHP syntax seluruh file
berhasil lolos pemeriksaan. Pengujian end-to-end dengan database, browser,
storage link, dan SMTP tetap perlu dijalankan di lingkungan Laravel yang
memiliki dependency Composer dan konfigurasi `.env` lengkap.
