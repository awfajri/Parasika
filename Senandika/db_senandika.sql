CREATE DATABASE IF NOT EXISTS `db_senandika`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_senandika`;

-- Tabel: users (Sudah diganti jadi 'npm' sesuai TODO Hapis)
CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `npm`          VARCHAR(20)  NOT NULL UNIQUE, -- Mengganti username menjadi npm
  `password`     VARCHAR(255) NOT NULL,
  `role`         ENUM('sekretaris','ketua','anggota') NOT NULL DEFAULT 'anggota',
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: kategori_arsip
CREATE TABLE IF NOT EXISTS `kategori_arsip` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: dokumen (Sudah disempurnakan: ada deskripsi & nama_file)
CREATE TABLE IF NOT EXISTS `dokumen` (
  `id`           INT(11)      NOT NULL AUTO_INCREMENT,
  `uploader_id`  INT(11)      NOT NULL,
  `kategori_id`  INT(11)      NOT NULL,
  `nama_dokumen` VARCHAR(255) NOT NULL,
  `deskripsi`    TEXT         NULL,          -- Tambahan untuk form UI kamu
  `nama_file`    VARCHAR(255) NOT NULL,      
  `file_url`     VARCHAR(255) NOT NULL,   
  `tipe_file`    VARCHAR(10)  NOT NULL,   
  `ukuran_file`  INT(11)      NOT NULL,   
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`uploader_id`) REFERENCES `users`(`id`)        ON DELETE CASCADE,
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori_arsip`(`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel: log_aktivitas
CREATE TABLE IF NOT EXISTS `log_aktivitas` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)      NOT NULL,
  `aksi`       VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Kategori arsip default
INSERT INTO `kategori_arsip` (`nama_kategori`) VALUES
  ('Surat Masuk'),
  ('Surat Keluar'),
  ('Proposal'),
  ('LPJ'),
  ('AD/ART'),
  ('SK');

-- User default untuk testing (Password semua: "password123")
INSERT INTO `users` (`nama_lengkap`, `npm`, `password`, `role`) VALUES
  ('Sekretaris Senandika', '2210631170001', '$2y$10$1m/pPqqx0FQ39IhRJkTa.u4XN4T.NqYly7R0J8JBF4tmkAbossj7C', 'sekretaris'),
  ('Ketua Umum',           '2210631170002', '$2y$10$1m/pPqqx0FQ39IhRJkTa.u4XN4T.NqYly7R0J8JBF4tmkAbossj7C', 'ketua'),
  ('Anggota Test',         '2210631170003', '$2y$10$1m/pPqqx0FQ39IhRJkTa.u4XN4T.NqYly7R0J8JBF4tmkAbossj7C', 'anggota');