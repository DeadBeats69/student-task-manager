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
   - **Modal Detail Tugas:** Klik baris tugas atau judul tugas untuk melihat detail lengkap secara langsung.

2. **Manajemen Mata Kuliah (CRUD):**
   - Melihat daftar seluruh mata kuliah.
   - Menambahkan mata kuliah baru (Nama Mata Kuliah, Dosen, Semester).
   - Mengubah data mata kuliah.
   - Menghapus mata kuliah (dengan proteksi *Foreign Key*: mata kuliah yang masih memiliki tugas tidak dapat dihapus).

3. **Manajemen Tugas (CRUD):**
   - Melihat daftar tugas lengkap dengan nama mata kuliah (SQL `JOIN`).
   - Menambahkan tugas dengan memilih mata kuliah dari dropdown relasional.
   - **Modal Detail Tugas (View Only):** Klik baris tugas, judul tugas, atau tombol aksi ikon mata (<i class="bi bi-eye"></i>) untuk melihat rincian tugas (judul, mata kuliah & dosen, deskripsi lengkap, deadline, status, dan waktu pembuatan).
   - Mengedit data tugas dan memperbarui status pengerjaan.
   - Menghapus tugas dengan modal konfirmasi aman.

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
│   │   └── script.js            # JavaScript konfirmasi modal, detail view, & interaksi
│   └── img/                     # Aset gambar pendukung (opsional)
│
├── config/
│   └── database.php             # Koneksi PDO, helper CSRF, Flash, dan utilitas
│
├── includes/
│   ├── header.php               # Header HTML & link stylesheet Bootstrap 5
│   ├── sidebar.php              # Navigasi sidebar desktop
│   ├── navbar.php               # Top navbar & offcanvas menu mobile
│   ├── footer.php               # Footer HTML & script Bootstrap JS
│   ├── flash.php                # Penampil alert flash messages
│   └── task_modal.php           # Helper modal detail tugas (View Only)
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
│   ├── index.php                # Halaman daftar tugas (dengan Search, Filter, & Detail)
│   ├── create.php               # Form tambah tugas
│   ├── store.php                # Pemroses POST tambah tugas
│   ├── edit.php                 # Form edit tugas
│   ├── update.php               # Pemroses POST update tugas
│   └── delete.php               # Pemroses POST hapus tugas
│
├── database/
│   └── student_task_manager.sql # Skrip DDL & DML database + dummy data
│
├── .gitignore                   # Konfigurasi ignore file Git
├── index.php                    # Halaman utama (Dashboard ringkasan statistik)
└── README.md                    # Panduan & dokumentasi lengkap proyek
```

---

## ⚙️ Panduan Lengkap Menjalankan Aplikasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal menggunakan **XAMPP**:

### 1. Prasyarat Sistem
Pastikan perangkat Anda sudah terinstal:
- **XAMPP** (sudah mencakup Apache Web Server & MariaDB/MySQL).
- **PHP versi 8.0** atau yang lebih baru (dilengkapi ekstensi `pdo_mysql`).
- **Web Browser** (Google Chrome, Firefox, Edge, Brave, dll.).
- **Git** (opsional, jika ingin meng-clone repositori).

---

### 2. Penempatan Folder Proyek

Pastikan folder proyek berada di dalam folder `htdocs` dari instalasi XAMPP Anda.

#### Opsi A: Menggunakan Git Clone
Buka terminal/PowerShell, arahkan ke folder `htdocs`, lalu jalankan:
```bash
cd C:\xampp\htdocs
git clone https://github.com/DeadBeats69/student-task-manager.git
```

#### Opsi B: Mengunduh ZIP
1. Download file ZIP repositori dan ekstrak.
2. Pindahkan folder hasil ekstrak ke:
   ```text
   C:\xampp\htdocs\student-task-manager\
   ```

> **Penting:** Pastikan nama foldernya adalah `student-task-manager` agar rute asset dan tautan navigasi berfungsi dengan benar.

---

### 3. Menjalankan Server XAMPP
1. Buka aplikasi **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache**.
3. Klik tombol **Start** pada modul **MySQL**.
4. Pastikan kedua modul berstatus warna hijau (running).

---

### 4. Mengimpor Database

1. Buka browser dan buka phpMyAdmin di alamat:  
   👉 [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Klik menu **Baru / New** pada panel sebelah kiri untuk membuat database baru.
3. Beri nama database: `student_task_manager` (atau `task_manager_db`), lalu klik **Buat / Create**.
4. Pilih database yang baru dibuat tersebut.
5. Klik tab **Impor / Import** di bagian atas.
6. Klik tombol **Choose File / Telusuri**, lalu arahkan ke file:
   ```text
   C:\xampp\htdocs\student-task-manager\database\student_task_manager.sql
   ```
7. Gulir ke bawah dan klik tombol **Kirim / Import / Go**.
8. Tunggu hingga muncul pesan hijau bahwa impor berhasil. Tabel `courses` dan `tasks` beserta contoh datanya akan otomatis terbuat.

> **Catatan Koneksi Otomatis:**  
> File `config/database.php` menggunakan konfigurasi default XAMPP (`host: localhost`, `user: root`, `password: ''`). Konfigurasi ini juga otomatis mendukung nama database `student_task_manager` maupun `task_manager_db`.

---

### 5. Mengakses Aplikasi Web
Setelah server aktif dan database terimpor, buka browser Anda dan kunjungi tautan berikut:

🌐 **[http://localhost/student-task-manager/](http://localhost/student-task-manager/)**

---

### 6. Panduan Penggunaan Fitur

1. **Dashboard (`index.php`):**
   - Melihat ringkasan jumlah mata kuliah, total tugas, dan status tugas (*Belum Dikerjakan*, *Sedang Dikerjakan*, *Selesai*).
   - Melihat daftar 10 tugas dengan deadline terdekat.
   - **Melihat Detail Tugas:** Klik pada judul tugas atau baris tabel untuk memunculkan modal rincian tugas.
2. **Kelola Mata Kuliah (`courses/index.php`):**
   - Tambahkan mata kuliah sebelum membuat tugas.
   - Mata kuliah yang masih memiliki tugas tidak dapat dihapus (*Proteksi Foreign Key*) untuk menjaga integritas data.
3. **Kelola Tugas (`tasks/index.php`):**
   - Klik **Tambah Tugas** dan pilih mata kuliah terkait dari dropdown.
   - Gunakan fitur **Cari Judul Tugas** untuk pencarian cepat.
   - Gunakan filter **Mata Kuliah** dan **Status** untuk menyaring daftar tugas.
   - Klik tombol ikon mata (<i class="bi bi-eye"></i>) atau klik baris tugas untuk membuka **Modal Detail Tugas**.
   - Klik ikon pensil untuk mengubah data, atau ikon tempat sampah untuk menghapus tugas.

---

## ❓ Troubleshooting / Pemecahan Masalah

| Masalah | Penyebab Umum | Solusi |
|---|---|---|
| *Error: Terjadi kesalahan koneksi database* | MySQL belum aktif atau database belum dibuat | Buka XAMPP Control Panel, pastikan MySQL dalam keadaan **Start**, dan pastikan file SQL sudah diimpor di phpMyAdmin. |
| *Tampilan CSS / JS tidak muncul (polos)* | Nama folder proyek tidak sesuai | Pastikan folder berada tepat di `C:\xampp\htdocs\student-task-manager\`. |
| *Port Apache Bentrok (Port 80/443 in use)* | Aplikasi lain (seperti VMware, Skype, IIS) menggunakan port 80 | Ubah port Apache di `httpd.conf` atau hentikan aplikasi yang memakai port tersebut. |
| *Mata kuliah tidak bisa dihapus* | Terdapat tugas yang masih terhubung ke mata kuliah tersebut | Hapus atau ubah mata kuliah pada tugas terkait terlebih dahulu di menu Tugas. |

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
| TC-13 | Detail Tugas | Klik baris tugas / tombol mata | Memunculkan modal rincian tugas (View Only) | Berhasil |
| TC-14 | Pencarian | Cari kata kunci judul tugas | Hanya tugas dengan judul sesuai yang ditampilkan | Berhasil |
| TC-15 | Filter | Filter berdasarkan mata kuliah | Hanya tugas dari mata kuliah terpilih yang muncul | Berhasil |
| TC-16 | Filter | Filter berdasarkan status | Hanya tugas dengan status terpilih yang muncul | Berhasil |
| TC-17 | Dashboard | Buka halaman dashboard | Angka counter & daftar deadline terdekat dihitung real-time dari database | Berhasil |
| TC-18 | Relasi | Tampilkan tabel tugas | Nama mata kuliah tampil via SQL `INNER JOIN` | Berhasil |
| TC-19 | Keamanan | Kirim POST tanpa token CSRF valid | Permintaan ditolak demi keamanan | Berhasil |

---

## 👨‍💻 Catatan Pengembang
Aplikasi ini dikembangkan menggunakan paradigma personal single-user tanpa login session berlebih sesuai ruang lingkup tugas akhir mata kuliah Pemrograman Berbasis Web.
