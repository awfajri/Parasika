-- --------------------------------------------------------
-- Database: db_senandika
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. Tabel users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `npm` varchar(13) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('sekretaris','ketua','anggota') NOT NULL DEFAULT 'anggota',
  `status` enum('pending','approved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `npm` (`npm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel kategori_arsip
CREATE TABLE `kategori_arsip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel dokumen
CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uploader_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `tipe_file` varchar(10) NOT NULL,
  `ukuran_file` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `uploader_id` (`uploader_id`),
  KEY `kategori_id` (`kategori_id`),
  CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dokumen_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_arsip` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel log_aktivitas
CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `aksi` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel pengajuan_surat
CREATE TABLE `pengajuan_surat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `status` enum('pending','diproses','selesai','ditolak') NOT NULL DEFAULT 'pending',
  `catatan_sekre` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Data Seeder (Isi Awal)
-- --------------------------------------------------------

INSERT INTO `kategori_arsip` (`nama_kategori`) VALUES
('Surat Masuk'),
('Surat Keluar'),
('Proposal'),
('LPJ'),
('AD/ART'),
('SK');

-- User default (Password: password123) - Status langsung Approved
INSERT INTO `users` (`nama_lengkap`, `npm`, `password`, `role`, `status`) VALUES
('Auf Fajri Ramadhani', '2410631170059', '$2y$10$1m/pPqqx0FQ39IhRJkTa.u4XN4T.NqYly7R0J8JBF4tmkAbossj7C', 'ketua', 'approved'),
('Imas Anisa', '2410631170027', '$2y$10$1m/pPqqx0FQ39IhRJkTa.u4XN4T.NqYly7R0J8JBF4tmkAbossj7C', 'sekretaris', 'approved');

COMMIT;