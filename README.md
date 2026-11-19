# Student Task Manager

Aplikasi web personal untuk membantu mahasiswa mencatat, mengelola, dan memantau tugas kuliah berdasarkan mata kuliah dan tenggat waktu (deadline).

---

## 📌 Ringkasan Proyek

- **Konsep:** Single-User Personal Student Task Manager
- **Teknologi:** PHP Native, Bootstrap 5, MySQL / MariaDB, PDO, HTML5, CSS3, JavaScript
- **Lingkungan:** XAMPP (Apache + MySQL / phpMyAdmin)
- **Database:** `student_task_manager` (atau `task_manager_db`)
- **Tabel Utama:** `courses` dan `tasks` (Relasi 1:N One-to-Many)
- **Autentikasi:** Tanpa login, registrasi, atau role (Personal Local Application)

---

## 🚀 Fitur Utama

1. **Dashboard Dinamis:**
   - Ringkasan total mata kuliah.
   - Ringkasan total seluruh tugas.
   - Jumlah tugas per status (`Belum Dikerjakan`, `Sedang Dikerjakan`, `Selesai`).
   - Tabel tugas dengan deadline terdekat dan indikator visual status tenggat waktu.

2. **Manajemen Mata Kuliah (CRUD):**
   - Melihat daftar seluruh mata kuliah.
   - Menambahkan mata kuliah baru (Nama Mata Kuliah, Dosen, Semester).
   - Mengubah data mata kuliah.
   - Menghapus mata kuliah (dengan proteksi *Foreign Key*: mata kuliah yang masih memiliki tugas tidak dapat dihapus).

3. **Manajemen Tugas (CRUD):**
   - Melihat daftar tugas lengkap dengan nama mata kuliah (SQL `JOIN`).
   - Menambahkan tugas dengan memilih mata kuliah dari dropdown relasional.
   - Mengedit data tugas dan memperbarui status pengerjaan.
   - Menghapus tugas dengan modal konfirmasi.

4. **Pencarian dan Filter:**
   - Pencarian real-time berdasarkan judul tugas.
   - Filter tugas berdasarkan mata kuliah.
   - Filter tugas berdasarkan status pengerjaan.
   - Tombol reset/bersihkan filter.

5. **Keamanan & Validasi:**
   - **PDO Prepared Statements** untuk mencegah *SQL Injection*.
   - **Output Escaping** (`htmlspecialchars()`) untuk mitigasi *Cross-Site Scripting (XSS)*.
   - **CSRF Token Protection** pada semua operasi perubahan data (POST: store, update, delete).
   - Validasi data di sisi server (PHP) dan client (HTML5).
   - Post/Redirect/Get (PRG) pattern dengan *Flash Messages*.

---

## 🗄️ Rancangan Database

Database menggunakan relasi **One-to-Many (1:N)** antara tabel `courses` dan `tasks`:
- Satu mata kuliah dapat memiliki banyak tugas.
- Satu tugas terikat pada tepat satu mata kuliah.
- Foreign Key: `tasks.course_id` -> `courses.course_id` dengan aturan `ON UPDATE CASCADE ON DELETE RESTRICT`.

### 1. Tabel `courses`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `course_id` | INT AUTO_INCREMENT (PK) | Primary Key mata kuliah |
| `course_name` | VARCHAR(100) NOT NULL | Nama mata kuliah |
| `lecturer` | VARCHAR(100) NULL | Dosen pengampu |
| `semester` | VARCHAR(20) NULL | Semester |
| `created_at` | TIMESTAMP | Waktu pembuatan data |

### 2. Tabel `tasks`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `task_id` | INT AUTO_INCREMENT (PK) | Primary Key tugas |
| `course_id` | INT NOT NULL (FK) | Foreign Key merujuk ke `courses.course_id` |
| `task_title` | VARCHAR(150) NOT NULL | Judul tugas |
| `description` | TEXT NULL | Deskripsi tugas |
| `deadline` | DATE NOT NULL | Tenggat waktu |
| `status` | ENUM('Belum Dikerjakan','Sedang Dikerjakan','Selesai') | Status pengerjaan |
| `created_at` | TIMESTAMP | Waktu pembuatan data |
| `updated_at` | TIMESTAMP | Waktu modifikasi terakhir |

---

## 📁 Struktur Direktori

```text
student-task-manager/
│
├── assets/
│   ├── css/
│   │   └── style.css            # Custom CSS styling & responsive adjustments
│   ├── js/
│   │   └── script.js            # JavaScript konfirmasi modal & interaksi
│   └── img/                     # Aset gambar pendukung (opsional)
│
├── config/
│   └── database.php             # Koneksi PDO, helper CSRF, Flash, dan utilitas
│
├── includes/
│   ├── header.php               # Header HTML & link stylesheet Bootstrap
│   ├── sidebar.php              # Navigasi sidebar desktop
│   ├── navbar.php               # Top navbar & offcanvas menu mobile
│   ├── footer.php               # Footer HTML & script Bootstrap JS
│   └── flash.php                # Penampil alert flash messages
│
├── courses/
│   ├── index.php                # Halaman daftar mata kuliah
│   ├── create.php               # Form tambah mata kuliah
│   ├── store.php                # Pemroses POST tambah mata kuliah
│   ├── edit.php                 # Form edit mata kuliah
│   ├── update.php               # Pemroses POST update mata kuliah
│   └── delete.php               # Pemroses POST hapus mata kuliah
│
├── tasks/
│   ├── index.php                # Halaman daftar tugas (dengan Search & Filter)
│   ├── create.php               # Form tambah tugas
│   ├── store.php                # Pemroses POST tambah tugas
│   ├── edit.php                 # Form edit tugas
│   ├── update.php               # Pemroses POST update tugas
│   └── delete.php               # Pemroses POST hapus tugas
│
├── database/
│   └── student_task_manager.sql # Skrip DDL & DML database + dummy data
│
├── index.php                    # Halaman utama (Dashboard ringkasan statistik)
└── README.md                    # Dokumentasi proyek
```

---

## ⚙️ Panduan Instalasi dan Penggunaan

### 1. Prasyarat
- XAMPP (Apache & MySQL aktif)
- PHP 8.x atau versi yang mendukung PDO
- Browser web modern (Google Chrome, Mozilla Firefox, Microsoft Edge)

### 2. Penempatan Berkas
Pastikan folder `student-task-manager` diletakkan di direktori `htdocs` XAMPP Anda:
```
C:\xampp\htdocs\student-task-manager\
```

### 3. Konfigurasi Database
1. Buka browser dan akses phpMyAdmin: `http://localhost/phpmyadmin`
2. Buat database baru bernama `student_task_manager` (atau `task_manager_db`).
3. Pilih database tersebut, buka tab **Import**, lalu pilih file `database/student_task_manager.sql`.
4. Klik **Go / Impor** untuk mengeksekusi pembuatan tabel dan pengisian dummy data.

*(Catatan: File `config/database.php` telah dirancang cerdas untuk otomatis mendeteksi apakah nama database Anda `student_task_manager` atau `task_manager_db`).*

### 4. Menjalankan Aplikasi
Buka peramban (browser) dan ketik alamat:
```
http://localhost/student-task-manager/
```

---

## 🧪 Tabel Pengujian Black-Box

| ID | Fitur | Skenario Uji | Hasil yang Diharapkan | Status |
|---|---|---|---|:---:|
| TC-01 | Mata Kuliah | Tambah dengan nama valid | Data tersimpan & muncul notifikasi sukses | Berhasil |
| TC-02 | Mata Kuliah | Tambah dengan nama kosong | Ditolak oleh validasi server & muncul pesan error | Berhasil |
| TC-03 | Mata Kuliah | Edit data nama/dosen/semester | Data berhasil diperbarui di database | Berhasil |
| TC-04 | Mata Kuliah | Hapus mata kuliah tanpa tugas | Data terhapus dengan pesan sukses | Berhasil |
| TC-05 | Mata Kuliah | Hapus mata kuliah yang memiliki tugas | Ditolak oleh aturan relasi (*Foreign Key*) dengan peringatan | Berhasil |
| TC-06 | Tugas | Tambah tugas dengan data valid | Data tersimpan dan terhubung ke mata kuliah terkait | Berhasil |
| TC-07 | Tugas | Tambah tugas dengan judul kosong | Ditolak oleh validasi server-side | Berhasil |
| TC-08 | Tugas | Pilih mata kuliah tidak valid | Ditolak dengan pesan validasi | Berhasil |
| TC-09 | Tugas | Format tanggal deadline tidak valid | Ditolak dengan pesan kesalahan format | Berhasil |
| TC-10 | Tugas | Edit data tugas | Data tugas berhasil diperbarui | Berhasil |
| TC-11 | Tugas | Ubah status pengerjaan | Status tugas terupdate (Belum / Sedang / Selesai) | Berhasil |
| TC-12 | Tugas | Hapus tugas | Data tugas berhasil dihapus | Berhasil |
| TC-13 | Pencarian | Cari kata kunci judul tugas | Hanya tugas dengan judul sesuai yang ditampilkan | Berhasil |
| TC-14 | Filter | Filter berdasarkan mata kuliah | Hanya tugas dari mata kuliah terpilih yang muncul | Berhasil |
| TC-15 | Filter | Filter berdasarkan status | Hanya tugas dengan status terpilih yang muncul | Berhasil |
| TC-16 | Dashboard | Buka halaman dashboard | Angka counter & daftar deadline terdekat dihitung real-time dari database | Berhasil |
| TC-17 | Relasi | Tampilkan tabel tugas | Nama mata kuliah tampil via SQL `INNER JOIN` | Berhasil |
| TC-18 | Keamanan | Kirim POST tanpa token CSRF valid | Permintaan ditolak demi keamanan | Berhasil |

---

## 👨‍💻 Catatan Pengembang
Aplikasi ini dikembangkan menggunakan paradigma personal single-user tanpa login session berlebih sesuai ruang lingkup tugas akhir mata kuliah Pemrograman Berbasis Web.
