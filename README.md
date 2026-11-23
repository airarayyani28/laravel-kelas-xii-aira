# Bahan materi kelas XII
Langkah-langkah yang harus dilakukan:

1. Silahkan kalian fork untuk memasukan kedalam repository kalian masing-masing
2. Setelah di fork silahkan kalian lakukan cloning dari repo hasil fork
```bash
git clone https://github.com/airarayyani28/laravel-kelas-xii-aira.git
cd dataweb
```
3. Membuat file .env
```bash
cp .env.example .env
nano .env
```
4. Atur koneksi database (pastikan databasenya sudah di buat terlebih dahulu)
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_film_baru
DB_USERNAME=root
DB_PASSWORD=
```
5. Install dependncies
```bash
composer install
```
6. Generate APP Key
```bash
php artisan key:generate
```
7. Migration & Seeder
```bash
php artisan migrate -s
```
8. copy folder image yang ada pada folder public ke dalam folder storage/app
9. Buat symlink untuk storage
```bash
php artisan storage:link
```  

# Aplikasi Web Katalog & Ulasan Film (Laravel)

Aplikasi web berbasis **Laravel** dan **MySQL** untuk menampilkan katalog film, informasi pemeran (cast) beserta perannya, serta ulasan dan rating dari pengguna terdaftar.

**Identitas Pengembang:**
- **Nama:** Aira Rayyani Saifa Rahman
- **Kelas:** XII Rekayasa Perangkat Lunak (RPL)
- **Sekolah:** SMKN 1 Karawang

---

## Fitur yang Dikerjakan

### 1. Pengunjung (Guest)
- Melihat daftar film terbaru di halaman utama (**Home**) dan seluruh katalog di halaman **Movies**.
- Memfilter daftar film berdasarkan kategori **Genre**.
- Melihat halaman detail film yang memuat poster, tahun rilis, genre, sinopsis, tabel daftar pemeran (**Cast & Peran**), serta daftar ulasan pengguna.

### 2. Autentikasi & Pembagian Hak Akses (Role-Based Access Control)
- **Registrasi Pengguna Baru:** Otomatis membuat data profil di tabel `profiles` dan menetapkan hak akses sebagai User Biasa (`role_id = 1`).
- **Login & Logout:** Autentikasi pengguna serta penyesuaian tampilan menu navigasi (*navbar*) berdasarkan status login dan role pengguna.

### 3. Pengguna Terdaftar (User Biasa - `role_id = 1`)
- **Kritik & Rating Film:** Mengirimkan ulasan teks serta penilaian rating (skala 1–5) pada halaman detail film.
- **Manajemen Profil:** Melihat informasi akun serta memperbarui data diri (nama, email, umur, alamat, bio) dan password melalui halaman **Profile**.

### 4. Administrator (`role_id = 2`)
- **Pembatasan Komentar:** Administrator dibatasi oleh sistem sehingga tidak dapat mengirimkan kritik atau rating pada halaman detail film.
- **Dashboard Administrator:**
  - **List Data User:** Melihat daftar seluruh pengguna terdaftar beserta role dan data profilnya.
  - **CRUD Data Film:** Menambah, melihat, mengubah, dan menghapus data film termasuk fitur *upload* gambar poster ke penyimpanan lokal (`storage/app/public/images`).
  - **CRUD Data Genre:** Mengelola kategori genre film.
  - **CRUD Data Cast:** Mengelola data aktor/aktris (nama, umur, dan biografi).
  - **CRUD Data Peran:** Mengelola relasi karakter/peran yang dimainkan oleh setiap cast pada masing-masing film.

---

## Struktur Database (`db_film_baru`)

Aplikasi ini menggunakan 8 tabel utama yang saling berelasi di MySQL:
1. `roles` — Menyimpan hak akses (`user` dan `admin`).
2. `profiles` — Menyimpan biodata pengguna (`umur`, `bio`, `alamat`).
3. `users` — Menyimpan data akun pengguna yang terhubung dengan `roles` dan `profiles`.
4. `genres` — Menyimpan daftar genre film.
5. `casts` — Menyimpan data aktor dan aktris.
6. `films` — Menyimpan data film (`title`, `sinopsis`, `year`, `poster`, `genre_id`).
7. `perans` — Tabel relasi antara `films` dan `casts` beserta nama karakter (`actor`).
8. `kritiks` — Menyimpan ulasan (`content`) dan nilai rating (`point`) dari `users` untuk setiap `films`.

---

## Akun Default Pengujian

- **Administrator:**
  - **Email:** `admin@example.com`
  - **Password:** `12345678`

---
