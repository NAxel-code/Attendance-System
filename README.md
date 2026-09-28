# 📋 Sistem Absensi Pegawai — Laravel 11

Sistem pencatatan kehadiran pegawai berbasis web yang dibangun dengan Laravel 11 dan PHP 8.2+. Dilengkapi dengan fitur autentikasi terproteksi, otorisasi berbasis peran (Role-Based Access), pencegahan brute force, dan rekapitulasi laporan absensi.

---

## ✅ Matriks Hak Akses & Fitur

Sistem membedakan peran antara **Administrator** dan **Pegawai** dengan penegakan validasi ketat di tingkat rute (*backend middleware*) dan controller:

| Fitur | Admin | Pegawai | Keterangan Akses |
|---|:---:|:---:|---|
| **Login / Logout** | ✅ | ✅ | Terproteksi Rate Limiter (Anti Brute Force) & Session Fixation |
| **Dashboard Statistik** | ✅ | ✅ | Admin melihat ringkasan seluruh kantor, Pegawai melihat statistik pribadi |
| **Manajemen Pegawai (CRUD)** | ✅ | ❌ | Tambah, ubah, nonaktifkan, dan hapus data pegawai |
| **Manajemen Departemen (CRUD)** | ✅ | ❌ | Kelola divisi & departemen |
| **Manajemen Absensi Manual (CRUD)** | ✅ | ❌ | Hanya Admin yang berhak menambah/mengubah/menghapus absensi |
| **Lihat Riwayat Absensi Sendiri** | ✅ | ✅ | Pegawai hanya dapat melihat data miliknya sendiri (Anti IDOR) |
| **Check In & Check Out Mandiri** | ❌ | ✅ | Khusus Pegawai berstatus **Active** |
| **Laporan Rekap Bulanan** | ✅ | ❌ | Rekapitulasi per bulan dengan filter status & departemen |
| **Laporan per Departemen** | ✅ | ❌ | Rekapitulasi statistik kehadiran per departemen |

---

## 🖼️ Interface

![Dashboard Absensi](docs/attendance-system%20dashboard.png)

![CRUD Pegawai dan Absensi](docs/employee%20and%20attendance%20dashboard.png)

---

## 🛡️ Arsitektur & Keamanan Sistem Terapan

Aplikasi ini menerapkan standar keamanan *Defense-in-Depth*:

1. **Proteksi Brute Force Login (Rate Limiting)**
   - Menggunakan `RateLimiter` pada rute login dengan batas maksimal **5 percobaan per 60 detik** per kombinasi email & IP address.
   - Jika limit terlampaui, request otomatis ditolak dengan pesan sisa waktu tunggu (*lockout countdown*).
2. **Pencegahan Broken Access Control (Anti IDOR)**
   - Rute modifikasi absensi (`create`, `store`, `edit`, `update`, `destroy`) diproteksi ganda oleh `admin` middleware dan pengecekan otorisasi di dalam `AttendanceController`.
   - Pegawai biasa hanya memiliki akses ke rute baca (`index`, `show`) yang difilter khusus untuk ID pegawai yang bersangkutan.
3. **Integritas Bisnis Absensi & Anti Race Condition**
   - **Validasi Status Pegawai:** Hanya pegawai dengan status `active` yang diizinkan melakukan check-in dan check-out. Pegawai berstatus `inactive` (dinonaktifkan/resign) otomatis ditolak.
   - **Anti Double-Submit / Race Condition:** Constraint unik `(employee_id, date)` di database diperkuat dengan penanganan exception di controller untuk mencegah duplikasi absensi saat tombol diklik ganda.
   - **Kalkulasi Keterlambatan Otomatis:** Pegawai yang melakukan check-in setelah pukul **08:30** otomatis ditandai berstatus `late` (terlambat).
4. **Zona Waktu Terstandarisasi (`Asia/Jakarta`)**
   - Aplikasi dikonfigurasi menggunakan zona waktu `Asia/Jakarta` (WIB) pada `config/app.php` dan `.env` agar waktu absensi dan status keterlambatan presisi sesuai jam kerja operasional.
5. **Keamanan Unggah File (Anti Stored XSS)**
   - Upload foto pegawai divalidasi ketat pada format gambar raster aman: `mimes:jpeg,png,jpg,webp` dengan batas ukuran maksimal 2MB (mengecualikan format `.svg` yang rentan disusupi skrip).
6. **Keamanan Sesi & Cookie**
   - `HttpOnly` aktif untuk mencegah pencurian cookie sesi via JavaScript (XSS).
   - `SameSite=lax` untuk mencegah serangan Cross-Site Request Forgery (CSRF).
   - Rotasi sesi (`session()->regenerate()`) pada login dan invalidasi total pada logout.

---

## 🗂️ Struktur File Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AttendanceController.php    # Absensi mandiri & manajemen absensi admin
│   │   ├── AuthController.php          # Login (Rate Limited) & logout aman
│   │   ├── DashboardController.php     # Dashboard admin & pegawai
│   │   ├── DepartmentController.php    # CRUD departemen
│   │   ├── EmployeeController.php      # CRUD pegawai & akun login
│   │   └── ReportController.php        # Laporan bulanan & departemen
│   └── Middleware/
│       └── AdminMiddleware.php         # Guard rute khusus administrator
├── Models/
│   ├── User.php                        # Akun login (role: admin | employee)
│   ├── Employee.php                    # Data profil pegawai & status
│   ├── Department.php                  # Data departemen/divisi
│   └── Attendance.php                  # Rekaman absensi & status kehadiran
config/
├── app.php                             # Konfigurasi timezone (Asia/Jakarta) & locale (id)
└── session.php                         # Pengaturan keamanan sesi cookie
database/
├── migrations/
│   ├── 2024_01_01_000001_create_users_table.php
│   ├── 2024_01_01_000002_create_departments_table.php
│   ├── 2024_01_01_000003_create_employees_table.php
│   └── 2024_01_01_000004_create_attendances_table.php
└── seeders/
    └── DatabaseSeeder.php              # Akun default admin & pegawai contoh
resources/views/
├── layouts/app.blade.php               # Template utama & navigasi
├── auth/login.blade.php                # Tampilan login dengan pesan error rate limit
├── attendances/                        # Tampilan daftar, detail, dan form absensi
├── employees/                          # Tampilan kelola pegawai
├── departments/                        # Tampilan kelola departemen
├── dashboard/                          # Dashboard admin & pegawai
└── reports/                            # Tampilan rekapitulasi laporan
routes/web.php                          # Definisi rute dengan segregasi middleware
```

---

## 🚀 Langkah Instalasi & Menjalankan

### 1. Salin File ke Proyek Laravel 11 Anda
Pastikan Anda telah memiliki project Laravel 11 (PHP 8.2+). Salin seluruh file dari repository ini ke dalam direktori project Anda.

### 2. Salin dan Sesuaikan `.env`
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Pastikan variabel penting berikut telah terisi:
```env
APP_NAME="Sistem Absensi"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_absensi
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Jalankan Migrasi & Database Seeder
```bash
php artisan migrate --seed
```

### 5. Buat Symlink Storage (Untuk Foto Pegawai)
```bash
php artisan storage:link
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Buka browser dan akses: **http://localhost:8000**

---

## 🔐 Akun Default Bawaan (Database Seeder)

| Peran (Role) | Email | Password | Hak Akses |
|---|---|---|---|
| **Administrator** | `admin@perusahaan.com` | `password` | Akses penuh dashboard, pegawai, departemen, laporan & kelola absensi |
| **Pegawai (IT)** | `budi@perusahaan.com` | `password` | Dashboard pegawai, check-in, check-out, lihat riwayat absensi pribadi |
| **Pegawai (HR)** | `sari@perusahaan.com` | `password` | Dashboard pegawai, check-in, check-out, lihat riwayat absensi pribadi |

---

## 🗄️ Skema Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | Auto increment |
| `name` | varchar | Nama pengguna |
| `email` | varchar unique | Alamat email unik untuk login |
| `password` | varchar | Kata sandi (di-hash dengan Bcrypt/Argon2) |
| `role` | enum | `admin` atau `employee` |

### Tabel `departments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | Auto increment |
| `name` | varchar | Nama departemen/divisi |
| `code` | varchar(10) unique | Kode unik departemen (contoh: `IT`, `HR`) |
| `description` | text nullable | Deskripsi departemen |

### Tabel `employees`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | Auto increment |
| `user_id` | FK → `users` | Relasi akun login (cascade on delete) |
| `department_id` | FK → `departments` | Relasi departemen |
| `employee_code` | varchar(20) unique | Nomor Induk Pegawai (NIP) |
| `name` | varchar | Nama lengkap pegawai |
| `email` | varchar unique | Email resmi pegawai |
| `phone` | varchar nullable | Nomor telepon |
| `address` | text nullable | Alamat domisili |
| `position` | varchar | Jabatan kerja |
| `join_date` | date | Tanggal mulai bekerja |
| `status` | enum | `active` atau `inactive` |
| `photo` | varchar nullable | Path file foto di storage publik |

### Tabel `attendances`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | bigint PK | Auto increment |
| `employee_id` | FK → `employees` | Relasi data pegawai |
| `date` | date | Tanggal pencatatan absensi |
| `check_in` | time nullable | Waktu absen masuk |
| `check_out` | time nullable | Waktu absen keluar |
| `status` | enum | `present`, `late`, `absent`, atau `leave` |
| `notes` | text nullable | Catatan / keterangan kehadiran |
| `approved_by` | FK → `users` nullable | ID Admin yang menyetujui / mencatat |

> **Constraint Unik:** `(employee_id, date)` — menjamin satu pegawai hanya memiliki 1 catatan absensi per hari.
