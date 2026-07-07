-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2026 at 02:50 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_peminjaman`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int NOT NULL,
  `id_peminjaman` int DEFAULT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approval`
--

CREATE TABLE `approval` (
  `id` int NOT NULL,
  `id_peminjaman` int DEFAULT NULL,
  `id_approver` int DEFAULT NULL,
  `level` enum('1','2','3') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `tgl_acc` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval`
--

INSERT INTO `approval` (`id`, `id_peminjaman`, `id_approver`, `level`, `status`, `tgl_acc`) VALUES
(3, 20, 7, '2', 'approved', '2026-04-28 18:30:55'),
(4, 22, 2, '1', 'approved', '2026-05-11 16:54:25'),
(5, 21, 2, '1', 'rejected', '2026-05-11 16:55:23'),
(6, 16, 2, '1', 'rejected', '2026-05-11 16:55:37'),
(7, 13, 2, '1', 'rejected', '2026-05-11 16:56:08'),
(8, 1, 2, '1', 'approved', '2026-05-11 16:57:37'),
(9, 2, 2, '1', 'rejected', '2026-05-12 17:50:06');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint UNSIGNED NOT NULL,
  `inisial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_dosen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_asset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi_asset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `inisial`, `nama`, `email_dosen`, `nama_asset`, `posisi_asset`, `created_at`, `updated_at`) VALUES
(1, 'BD', 'Bapak Budi', 'budi@pcr.ac.id', 'Proyektor Epson', 'Lab Pemrograman', '2026-06-11 07:39:45', '2026-06-11 07:39:45'),
(2, 'AN', 'Ibu Andi', 'andi@pcr.ac.id', 'Oscilloscope', 'Lab Elektronika', '2026-06-11 07:39:45', '2026-06-11 07:39:45'),
(3, 'CW', 'Bapak Cipto', 'cipto@pcr.ac.id', 'Router Cisco', 'Lab Jaringan', '2026-06-11 07:39:45', '2026-06-11 07:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` bigint UNSIGNED NOT NULL,
  `id_lab` int NOT NULL,
  `mata_kuliah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `id_lab`, `mata_kuliah`, `dosen`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(1, 1, 'Algoritma Pemrograman', 'ABC', 'Senin', '07:00:00', '11:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab`
--

CREATE TABLE `lab` (
  `id_lab` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status` enum('tersedia','dipinjam') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lab`
--

INSERT INTO `lab` (`id_lab`, `nama`, `status`) VALUES
(1, 'Lab Software Engineering (330)', 'tersedia'),
(2, 'Lab IoT dan AI (320)', 'tersedia'),
(3, 'Lab Computer Networking (324)', 'tersedia'),
(4, 'Lab Programing (313)', 'tersedia'),
(5, 'Lab Animasi dan Game (316)', 'tersedia'),
(6, 'Lab Mobile Application (317)', 'tersedia'),
(7, 'Lab Big Data (325)', 'tersedia'),
(8, 'Lab Business Analyst (329)', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nim` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_user` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_23_082246_create_personal_access_tokens_table', 1),
(5, '2026_04_14_183413_add_details_to_peminjaman_table', 2),
(6, '2026_05_05_165037_create_jadwal_kuliahs_table', 3),
(8, '2026_06_10_160234_add_rfid_uid_to_users_table', 4),
(9, '2026_06_11_141002_create_assets_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_lab` int DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `keterangan` text,
  `daftar_nama` text,
  `ketua_kegiatan` varchar(255) DEFAULT NULL,
  `kontak_ketua` varchar(255) DEFAULT NULL,
  `level` enum('1','2','3') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `id_user`, `id_lab`, `tgl_mulai`, `tgl_selesai`, `jam_mulai`, `jam_selesai`, `keterangan`, `daftar_nama`, `ketua_kegiatan`, `kontak_ketua`, `level`, `status`) VALUES
(1, 1, 1, '2026-02-25', '2026-02-25', '09:00:00', '11:00:00', 'Pengerjaan Proyek Akhir', NULL, NULL, NULL, '1', 'approved'),
(2, 1, 1, '2026-02-25', '2026-02-25', '21:00:00', '22:00:00', 'Pengerjaan Proyek Akhir', NULL, NULL, NULL, '1', 'rejected'),
(3, 1, 1, '2026-02-25', '2026-02-25', '19:00:00', '22:00:00', 'Pengerjaan Proyek Akhir', NULL, NULL, NULL, '1', 'pending'),
(4, 1, 1, '2026-02-25', '2026-02-25', '19:00:00', '22:00:00', 'Pengerjaan Proyek Akhir', NULL, NULL, NULL, '2', 'approved'),
(5, 1, 1, '2026-02-23', '2026-02-23', '17:00:00', '19:00:00', 'Testing Buka Pintu', NULL, NULL, NULL, '2', 'approved'),
(6, 4, 1, '2026-03-01', '2026-03-01', '08:00:00', '12:00:00', 'Eksperimen IoT otomatisasi ID', NULL, NULL, NULL, '3', 'pending'),
(10, 4, 2, '2026-02-26', '2026-02-26', '13:00:00', '17:03:00', 'qwertyu', NULL, NULL, NULL, '1', 'approved'),
(11, 4, 3, '2026-02-27', '2026-02-27', '09:00:00', '11:00:00', 'awookwokwokwokwo', NULL, NULL, NULL, '1', 'approved'),
(12, 4, 3, '2026-02-27', '2026-02-27', '09:00:00', '11:00:00', 'awookwokwokwokwo', NULL, NULL, NULL, '1', 'approved'),
(13, 4, 3, '2026-02-27', '2026-02-27', '09:00:00', '11:00:00', 'awookwokwokwokwo', NULL, NULL, NULL, '1', 'rejected'),
(14, 4, 2, '2026-03-01', '2026-03-01', '09:00:00', '11:00:00', 'kerjain laprak', NULL, NULL, NULL, '3', 'pending'),
(15, 4, 3, '2026-03-01', '2026-03-01', '18:00:00', '21:00:00', 'kerjain laprak', NULL, NULL, NULL, '3', 'pending'),
(16, 4, 1, '2026-03-16', '2026-03-16', '10:10:00', '12:20:00', 'dfghjk', NULL, NULL, NULL, '1', 'rejected'),
(17, 4, 2, '2026-04-12', '2026-04-12', '17:00:00', '21:00:00', 'main main', NULL, NULL, NULL, '3', 'pending'),
(18, 4, 3, '2026-04-19', '2026-04-19', '19:00:00', '22:00:00', 'awrsvsfdba', NULL, NULL, NULL, '3', 'pending'),
(19, 4, 2, '2026-04-30', '2026-04-30', '12:10:00', '13:50:00', 'bootcamp PA', '1. Randy Prada', 'Feby', '098765432112', '1', 'approved'),
(20, 4, 1, '2026-05-01', '2026-05-02', '17:00:00', '19:01:00', 'Kegiatan Rapat', '1. ksdvhs8v\r\n2. osudhfsoduvb', 'Arif', '08123456789', '2', 'approved'),
(21, 4, 1, '2026-05-11', '2026-05-11', '08:00:00', '10:00:00', 'Bootcamp', '1235', 'qwerty', '08123456789', '1', 'rejected'),
(22, 4, 3, '2026-05-13', '2026-05-13', '15:00:00', '17:00:00', 'Kerjakan tugas kuliah', '1. Andi\r\n2. Badul\r\n3. Cecep', 'Denis', '087889455621', '1', 'approved'),
(23, 4, 2, '2026-05-11', '2026-05-11', '03:00:00', '04:00:00', 'Main main', '1.\r\n2.\r\n3.', 'Ajdueus', '098876654321', '3', 'pending'),
(24, 4, 3, '2026-06-09', '2026-06-09', '16:00:00', '17:00:00', 'Belajar Jaringan', '1.a\r\n2.b\r\n3.c', 'anto', '0123456789', '1', 'pending'),
(25, 4, 2, '2026-06-10', '2026-06-10', '12:00:00', '14:00:00', 'Bootcamp', 'qwertyuiop', 'anto', '098765432112', '1', 'pending'),
(26, 4, 3, '2026-06-11', '2026-06-11', '12:00:00', '13:00:00', 'Bikin Laporan', '1. anto\r\n2. yuli', 'Randy', '0123456789', '1', 'pending'),
(27, 4, 1, '2026-06-11', '2026-06-11', '12:00:00', '14:00:00', 'Bikin Laporan', '1. Anto\r\n2. Yuli', 'Randy', '082288956554', '1', 'rejected'),
(28, 8, 3, '2026-06-11', '2026-06-11', '18:10:00', '19:10:00', 'Bootcamp', '1. Febhy\r\n2. Ppoy\r\n3. Randy', 'Randy', '07111321654', '2', 'approved'),
(29, 8, 3, '2026-06-11', '2026-06-11', '19:15:00', '21:00:00', 'Latihannn', 'qwertyuio', 'wertyui', '07989456123', '2', 'approved'),
(30, 4, 3, '2026-07-01', '2026-07-01', '18:00:00', '19:00:00', 'Belajar', '1a\r\n2b', 'Iren', '0123654789', '2', 'approved'),
(31, 8, 3, '2026-07-02', '2026-07-02', '16:00:00', '19:00:00', 'Belajar IOT', 'qwerfg', 'qwert', '0236', '1', 'approved'),
(32, 8, 4, '2026-07-05', '2026-07-05', '21:40:00', '23:00:00', 'TESTING', 'qwertyui', 'qwyu', 'wertyuk', '3', 'approved'),
(33, 4, 3, '2026-07-05', '2026-07-05', '21:55:00', '23:00:00', 'TESTING', 'qqwerty', 'qwertyu', '1wertyujhgf', '3', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 4, 'auth_token', '0d7d87984ea201751bbeeca0948848103a6706b1725915ed3d05c51bb610e761', '[\"*\"]', '2026-02-24 07:14:20', NULL, '2026-02-24 07:09:01', '2026-02-24 07:14:20'),
(2, 'App\\Models\\User', 4, 'auth_token', '785c7bc1490036ebef6f77aed53043198846cf815455440985cd7832b05c2265', '[\"*\"]', '2026-02-24 09:04:46', NULL, '2026-02-24 08:47:13', '2026-02-24 09:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `rfid`
--

CREATE TABLE `rfid` (
  `id_rfid` int NOT NULL,
  `uid_tag` varchar(50) NOT NULL,
  `id_user` int DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL,
  `jadwal_lab` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rfid`
--

INSERT INTO `rfid` (`id_rfid`, `uid_tag`, `id_user`, `status`, `jadwal_lab`) VALUES
(1, 'A1B2C3D4', 1, 'aktif', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AqaLfAshDApvTyXpNqiNuI6ZakxT6WlqEnaa53rN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHJYVlJMYm1EamJOdXRjaUlXMG5lakM4dFR6M09Od1daTkRxQjdqZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782932069),
('jzOKbhx0RnVFIp6DW5G1YJB6fuzEgVbxxqhQ3n6e', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicTlMQk1QdjBQazZNcDdHZ21nOUJha1lvbzB2NVQ4TUE1N0JrbXkwdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1782904286),
('UM2wbExKxiTCuPG4uYtFLTiUEAdAM8Bux5Z59ZFP', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoickdibTAyUVk1amhOTno2UFcybUpLSUhFcWlFSlJ6ejB2NXVUV01JdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZW1pbmphbWFuL2RldGFpbC8zMSI7czo1OiJyb3V0ZSI7czoxNToicGVtaW5qYW1hbi5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1782986314),
('XmFPts8WiEchmuiEvjNkGP31SYQkqb5Mc2BurWoM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjdROEpqVkNJT0prZ1BQTU1FMEYxcjVYN3UxZ1Qyb0NMMFFWbEpydSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782903862),
('Z7hyCtDlyYYv8Rw6Jm1eK3fR5G9EEGJC1m0mBDCu', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNldRbFBKSnJiRFNZSk5lbWo3WXBPdnJBckJ5alNlYUxwdXhTQzhRdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1783263366);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rfid_uid` varchar(255) DEFAULT NULL,
  `role` enum('mahasiswa','laboran','kajur','wadir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`, `rfid_uid`, `role`) VALUES
(1, 'randy', 'randy@pcr.ac.id', 'password123', NULL, 'mahasiswa'),
(2, 'Pak_Laboran', 'randyprada016@gmail.com', '$2y$12$B1u3YSQaannkd4Yqxr0NRetFVtP5k0qNIRH89aMeNf442Omt7fko6', NULL, 'laboran'),
(3, 'Pak_kajur', 'kajur@pcr.ac.id', '$2y$12$B1u3YSQaannkd4Yqxr0NRetFVtP5k0qNIRH89aMeNf442Omt7fko6', NULL, 'kajur'),
(4, 'irenreby', 'iren@mahasiswa.pcr.ac.id', '$2y$12$9GVz7GRaMI3iYRHv4xO5S.hNGpHqtV.vIwOU3TVUt/PYgGgWLVyAi', '0791904517', 'mahasiswa'),
(5, 'Pak_Wadir', 'randyprada016@gmail.com\r\n', '$2y$12$B1u3YSQaannkd4Yqxr0NRetFVtP5k0qNIRH89aMeNf442Omt7fko6', NULL, 'wadir'),
(6, 'budy', 'budy@mahasiswa.pcr.ac.id', '$2y$12$v4zcdYrm94f7X5DtxXcSBuOxZsLfv9JA5V0QRiRoZTuvdj4SbEpSS', NULL, 'mahasiswa'),
(7, 'Kajur_2', 'randy22si@mahasiswa.pcr.ac.id', '$2y$12$o97LqmMXnH6uefjXSX34uewNOJnzRjBDpNSVAkr.garB5t5wG1BUG', NULL, 'kajur'),
(8, 'gibranraka', 'gibranraka@mahasiswa.pcr.ac.id', '$2y$12$8JIEYxl5r6v83oJHy4xQ3ONUE/lqO6Gn0qm8jd/NiCzDQX.nrOJHS', '1084168614', 'mahasiswa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `approval`
--
ALTER TABLE `approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_approver` (`id_approver`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`id_lab`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_lab` (`id_lab`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `rfid`
--
ALTER TABLE `rfid`
  ADD PRIMARY KEY (`id_rfid`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user_rfid_uid_unique` (`rfid_uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approval`
--
ALTER TABLE `approval`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab`
--
ALTER TABLE `lab`
  MODIFY `id_lab` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rfid`
--
ALTER TABLE `rfid`
  MODIFY `id_rfid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `anggota_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anggota_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `approval`
--
ALTER TABLE `approval`
  ADD CONSTRAINT `approval_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approval_ibfk_2` FOREIGN KEY (`id_approver`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_lab`) REFERENCES `lab` (`id_lab`) ON DELETE CASCADE;

--
-- Constraints for table `rfid`
--
ALTER TABLE `rfid`
  ADD CONSTRAINT `rfid_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
