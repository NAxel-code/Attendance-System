# 📋 Sistem Absensi Pegawai — Laravel 11

Sistem pencatatan kehadiran pegawai berbasis web dengan fitur autentikasi, CRUD pegawai, departemen, dan laporan absensi.

---

## ✅ Fitur Sistem

| Fitur | Admin | Pegawai |
|---|:---:|:---:|
| Login / Logout | ✅ | ✅ |
| Dashboard statistik | ✅ | ✅ |
| CRUD Data Pegawai | ✅ | ❌ |
| CRUD Departemen | ✅ | ❌ |
| Catat Absensi Manual | ✅ | ❌ |
| Lihat Absensi Sendiri | ✅ | ✅ |
| Check In / Check Out Mandiri | ❌ | ✅ |
| Laporan Bulanan | ✅ | ❌ |
| Laporan per Departemen | ✅ | ❌ |

---

## 🖼️ Tampilan Antarmuka

![Dashboard Absensi](docs/attendance-system%20dashboard.png)

![CRUD Pegawai dan Absensi](docs/employee%20and%20attendance%20dashboard.png)

---

## 🗂️ Struktur File

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login & logout
│   │   ├── DashboardController.php     # Dashboard admin & pegawai
│   │   ├── EmployeeController.php      # CRUD pegawai
│   │   ├── DepartmentController.php    # CRUD departemen
│   │   ├── AttendanceController.php    # CRUD absensi + check-in/out
│   │   └── ReportController.php        # Laporan bulanan & departemen
│   └── Middleware/
│       └── AdminMiddleware.php         # Guard khusus admin
├── Models/
│   ├── User.php                        # Akun login (role: admin|employee)
│   ├── Employee.php                    # Data pegawai
│   ├── Department.php                  # Departemen
│   └── Attendance.php                  # Rekaman absensi
database/
├── migrations/
│   ├── ..._create_users_table.php
│   ├── ..._create_departments_table.php
│   ├── ..._create_employees_table.php
│   └── ..._create_attendances_table.php
└── seeders/
    └── DatabaseSeeder.php              # Data awal (admin + 2 pegawai contoh)
resources/views/
├── layouts/app.blade.php               # Template utama dengan sidebar
├── auth/login.blade.php
├── employees/index.blade.php
├── attendances/index.blade.php
├── attendances/create.blade.php
└── ...
routes/web.php                          # Semua route aplikasi
bootstrap/app.php                       # Registrasi middleware 'admin'
```

---

## 🚀 Langkah Instalasi

### 1. Buat proyek Laravel 11 baru
```bash
composer create-project laravel/laravel sistem-absensi "11.*"
cd sistem-absensi
```

### 2. Salin semua file dari folder ini ke proyek

Salin seluruh isi folder sesuai path masing-masing.

### 3. Konfigurasi database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_absensi
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan migrasi & seeder
```bash
php artisan migrate --seed
```

### 5. Buat symlink storage (untuk foto pegawai)
```bash
php artisan storage:link
```

### 6. Jalankan server
```bash
php artisan serve
```

Akses di: **http://localhost:8000**

---

## 🔐 Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@perusahaan.com | password |
| Pegawai | budi@perusahaan.com | password |
| Pegawai | sari@perusahaan.com | password |

---

## 🗄️ Skema Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar unique | |
| password | varchar | hashed |
| role | enum | admin / employee |

### Tabel `departments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar | Nama departemen |
| code | varchar(10) unique | Kode singkat |
| description | text nullable | |

### Tabel `employees`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | Akun login |
| department_id | FK → departments | |
| employee_code | varchar(20) unique | Nomor induk |
| name | varchar | |
| email | varchar unique | |
| phone | varchar nullable | |
| address | text nullable | |
| position | varchar | Jabatan |
| join_date | date | |
| status | enum | active / inactive |
| photo | varchar nullable | Path file foto |

### Tabel `attendances`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| employee_id | FK → employees | |
| date | date | Tanggal absensi |
| check_in | time nullable | Jam masuk |
| check_out | time nullable | Jam keluar |
| status | enum | present / late / absent / leave |
| notes | text nullable | Keterangan |
| approved_by | FK → users nullable | Admin yang menyetujui |

**Constraint unik:** `(employee_id, date)` — satu pegawai satu record per hari.

---

## 📝 Catatan Pengembangan Lanjutan

- Tambahkan export Excel/PDF menggunakan `maatwebsite/excel` atau `barryvdh/laravel-dompdf`
- Tambahkan notifikasi email otomatis saat absensi disetujui
- Implementasikan QR Code untuk sistem check-in mandiri
- Tambahkan fitur izin/cuti dengan approval workflow
