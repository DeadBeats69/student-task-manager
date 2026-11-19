-- ================================================================
-- Student Task Manager - Database SQL
-- ================================================================
-- Target DBMS : MySQL / MariaDB (XAMPP)
-- Database    : student_task_manager
-- Charset     : utf8mb4 / utf8mb4_unicode_ci
-- Engine      : InnoDB
-- ================================================================

CREATE DATABASE IF NOT EXISTS `student_task_manager`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `student_task_manager`;

-- ----------------------------------------------------------------
-- Struktur Tabel: courses (Mata Kuliah)
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
    `course_id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_name` VARCHAR(100) NOT NULL,
    `lecturer` VARCHAR(100) NULL,
    `semester` VARCHAR(20) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Struktur Tabel: tasks (Tugas)
-- ----------------------------------------------------------------
CREATE TABLE `tasks` (
    `task_id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT NOT NULL,
    `task_title` VARCHAR(150) NOT NULL,
    `description` TEXT NULL,
    `deadline` DATE NOT NULL,
    `status` ENUM(
        'Belum Dikerjakan',
        'Sedang Dikerjakan',
        'Selesai'
    ) NOT NULL DEFAULT 'Belum Dikerjakan',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_tasks_courses`
        FOREIGN KEY (`course_id`)
        REFERENCES `courses` (`course_id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Data Awal (Dummy Data)
-- ----------------------------------------------------------------
INSERT INTO `courses` (`course_id`, `course_name`, `lecturer`, `semester`) VALUES
(1, 'Pemrograman Berbasis Web', 'Budi Santoso', '5'),
(2, 'Data Mining', 'Andi Wijaya', '5'),
(3, 'Interaksi Manusia dan Komputer', 'Siti Rahma', '5');

INSERT INTO `tasks` (`course_id`, `task_title`, `description`, `deadline`, `status`) VALUES
(1, 'Membuat Aplikasi CRUD PHP', 'Membuat aplikasi web dinamis berbasis database.', '2026-12-01', 'Sedang Dikerjakan'),
(2, 'Analisis Dataset', 'Melakukan preprocessing dan analisis data.', '2026-12-05', 'Belum Dikerjakan'),
(1, 'Menyusun Laporan Proyek', 'Menyusun dokumentasi tahapan SDLC.', '2026-12-10', 'Selesai'),
(3, 'Desain Prototype UI/UX', 'Membuat wireframe dan prototipe sistem berbasis pengguna.', '2026-12-03', 'Belum Dikerjakan'),
(2, 'Presentasi Tugas Besar', 'Presentasi algoritma clustering pada data mining.', '2026-12-15', 'Belum Dikerjakan');
