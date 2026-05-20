-- Database Schema Export
-- Generated on 2026-05-20 20:08:07

SET FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------
-- Table structure for table `gallery`
-- --------------------------------------------------------

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedding_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `thumb_url` varchar(500) NOT NULL DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wedding_id` (`wedding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `guestbook`
-- --------------------------------------------------------

CREATE TABLE `guestbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedding_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `circle` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `attendance` tinyint(1) DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wedding_id` (`wedding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `guestbook_rate_limit`
-- --------------------------------------------------------

CREATE TABLE `guestbook_rate_limit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedding_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `last_submitted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_ip_wedding` (`wedding_id`,`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `invitation_categories`
-- --------------------------------------------------------

CREATE TABLE `invitation_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedding_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wedding_id` (`wedding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `invitation_guests`
-- --------------------------------------------------------

CREATE TABLE `invitation_guests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `wedding_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wedding_id` (`wedding_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `love_stories`
-- --------------------------------------------------------

CREATE TABLE `love_stories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wedding_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `story_date` date NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT '',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wedding_id` (`wedding_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES ('1', 'admin', '$2y$10$BLMLdMc951OkHvd1ohZYNeLhNbw3QSworFlbuaq57xZMHKcczzwSm', 'admin', '2026-05-02 11:47:00');
INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES ('2', 'anto', '$2y$10$s5tDmrDqqEQ8yU1sAveozep37MVg3CEqBtlXLeZNzb6Bkvb0E/tbm', 'client', '2026-05-02 12:00:18');
INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES ('3', 'syifarochim', '$2y$10$5mrvF8H/pfGM2Qy1UEqIIO.pmIHfCAR7lgE5MSK8N5CEi5JeziNLO', 'client', '2026-05-18 12:57:03');

-- --------------------------------------------------------
-- Table structure for table `weddings`
-- --------------------------------------------------------

CREATE TABLE `weddings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `theme_path` varchar(255) DEFAULT 'default',
  `story_intro` text DEFAULT NULL,
  `story_outro` text DEFAULT NULL,
  `bride_name` varchar(255) DEFAULT NULL,
  `bride_nickname` varchar(100) DEFAULT NULL,
  `bride_order` varchar(50) DEFAULT NULL,
  `bride_father` varchar(255) DEFAULT NULL,
  `bride_mother` varchar(255) DEFAULT NULL,
  `groom_name` varchar(255) DEFAULT NULL,
  `groom_nickname` varchar(100) DEFAULT NULL,
  `groom_order` varchar(50) DEFAULT NULL,
  `groom_father` varchar(255) DEFAULT NULL,
  `groom_mother` varchar(255) DEFAULT NULL,
  `akad_date` varchar(100) DEFAULT NULL,
  `akad_time` varchar(100) DEFAULT NULL,
  `akad_place` text DEFAULT NULL,
  `reception_date` varchar(100) DEFAULT NULL,
  `reception_time` varchar(100) DEFAULT NULL,
  `reception_place` text DEFAULT NULL,
  `reception_map_link` text DEFAULT NULL,
  `akad_map_link` varchar(500) DEFAULT NULL,
  `bride_bank_name` varchar(100) DEFAULT NULL,
  `bride_bank_acc` varchar(100) DEFAULT NULL,
  `groom_bank_name` varchar(100) DEFAULT NULL,
  `groom_bank_acc` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bank1_owner` varchar(255) DEFAULT NULL,
  `bank1_name` varchar(100) DEFAULT NULL,
  `bank1_acc` varchar(100) DEFAULT NULL,
  `bank2_owner` varchar(255) DEFAULT NULL,
  `bank2_name` varchar(100) DEFAULT NULL,
  `bank2_acc` varchar(100) DEFAULT NULL,
  `music_url` varchar(500) DEFAULT NULL,
  `bride_image` varchar(500) DEFAULT NULL,
  `groom_image` varchar(500) DEFAULT NULL,
  `section_settings` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_user` (`user_id`),
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS=1;
