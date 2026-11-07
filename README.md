# PortalBerita - Aplikasi Portal Informasi & Blog Mini (Proyek Kelompok RPL)

Aplikasi web portal berita berbasis **Laravel** dan **Tailwind CSS** (mendukung *Tema Terang & Gelap / Dark Mode*), dilengkapi sistem **Autentikasi Multi-Role** (Admin, Penulis, dan User Penglihat), keamanan **UUID** pada seluruh tabel, manajemen artikel (CRUD), manajemen kategori berita, serta manajemen pengguna.

---

## Daftar Akun Default (Seeder)

Setelah menjalankan migrasi dan seeder database (`php artisan migrate:fresh --seed`), Anda dapat login melalui halaman `/login` menggunakan akun berikut:

| Role | Nama Pengguna | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `Admin PortalBerita` | `admin@portalberita.com` | `password` | Akses penuh: CRUD Artikel, CRUD Kategori, Kelola/Daftarkan Akun Penulis & User, serta membaca berita. |
| **Penulis** | `Penulis Redaksi` | `penulis@portalberita.com` | `password` | Menulis artikel baru, mengedit & mengelola daftar artikel (CRUD Artikel), tambah kategori cepat, serta membaca berita. |
| **User Penglihat** | `User Penglihat` | `user@portalberita.com` | `password` | Membaca berita, memfilter kategori, dan mencari artikel (pengunjung juga dapat membaca berita tanpa login). |

> **Catatan:** Pendaftaran akun melalui halaman publik (`/register`) otomatis terdaftar sebagai **User Penglihat**. Penambahan akun dengan role **Penulis** atau **Admin** hanya dapat dilakukan oleh Admin melalui menu **Daftar Penulis/User** (`/users`).

---

## Fitur Utama

1. **Halaman Publik & Beranda Berita (`/`)**
   - Header bergaya portal berita dengan navigasi kategori horizontal, kotak pencarian langsung, dan tombol ganti **Tema Terang / Gelap (Light & Dark Mode)**.
   - Banner *auto-slider* untuk sorotan berita terbaru yang bergeser otomatis kanan-kiri.
   - Daftar artikel dengan *pagination* Tailwind, pencarian kata kunci (`?q=...`), dan filter berdasarkan kategori (`?kategori=...`).
   - *Placeholder* ilustrasi otomatis apabila artikel tidak memiliki gambar atau URL gambar gagal dimuat.
   - Daftar berita terpopuler berdasarkan jumlah pembaca (*views*) dan *footer* portal berita lengkap.
2. **Keamanan Database dengan UUID (`HasUuids`)**
   - Seluruh tabel utama (`users`, `categories`, `articles`) menggunakan **UUID** sebagai *primary key* dan *foreign key* agar ID tidak mudah ditebak pada URL.
3. **Sistem Autentikasi Multi-Role (`/login` & `/register`)**
   - Pengunjung dapat membaca berita secara langsung tanpa login (*mode penglihat*) atau login menggunakan akun.
   - Proteksi rute (*route middleware*) berbasis role (`role:admin,penulis` dan `role:admin`).
4. **Manajemen Artikel (`/articles`)** — *Admin & Penulis*
   - Tambah, lihat, ubah, dan hapus artikel berita (mendukung upload file gambar atau URL gambar).
5. **Manajemen Kategori (`/categories`)** — *Admin*
   - Kelola daftar kategori berita beserta warna badge-nya.
6. **Manajemen Pengguna (`/users`)** — *Admin*
   - Daftarkan akun Penulis atau User Penglihat baru, ubah role, dan hapus akun pengguna.

---

## Data Awal (Database Seeder)

Perintah `php artisan migrate:fresh --seed` akan otomatis mengisi:
- **3 Akun Pengguna** (`Admin`, `Penulis`, `User Penglihat`)
- **5 Kategori Berita** (*Teknologi & AI*, *Pendidikan & Kampus*, *Ekonomi Digital*, *Olahraga*, *Sains & Lingkungan*)
- **12 Artikel Berita Contoh** lengkap dengan ringkasan, isi berita multi-paragraf, kategori, dan jumlah pembaca.

---

## Cara Menjalankan Proyek

1. **Pastikan PHP & Composer sudah terpasang:**
   ```bash
   php -v
   composer -V
   ```

2. **Install dependensi Composer:**
   ```bash
   composer install
   ```

3. **Salin konfigurasi environment & generate app key:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Buat file database SQLite (jika menggunakan SQLite) & jalankan migrasi beserta Seeder:**
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```

5. **Jalankan server lokal:**
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000` di browser Anda.
