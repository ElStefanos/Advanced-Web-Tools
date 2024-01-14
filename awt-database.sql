

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET time_zone = "+00:00";



-- --------------------------------------------------------

--
-- Table structure for table `awt_access_authorization`
--

CREATE TABLE IF NOT EXISTS `awt_access_authorization` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fileName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fileHash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `uniqueKey` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_admin`
--

CREATE TABLE IF NOT EXISTS `awt_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_logged_ip` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `permission_level` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_albums`
--

CREATE TABLE IF NOT EXISTS `awt_albums` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_cron`
--

CREATE TABLE IF NOT EXISTS `awt_cron` (
  `id` int NOT NULL AUTO_INCREMENT,
  `interval` int NOT NULL,
  `last_run` int NOT NULL,
  `caller` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_mail`
--

CREATE TABLE IF NOT EXISTS `awt_mail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `recipient` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` text COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_media`
--

CREATE TABLE IF NOT EXISTS `awt_media` (
  `id` int NOT NULL AUTO_INCREMENT,
  `album_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `file_type` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_menus`
--

CREATE TABLE IF NOT EXISTS `awt_menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `items` text COLLATE utf8mb4_general_ci NOT NULL,
  `active` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_metrics`
--

CREATE TABLE IF NOT EXISTS `awt_metrics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=824 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_notifications`
--

CREATE TABLE IF NOT EXISTS `awt_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `caller` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `importance` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=456 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_paging`
--

CREATE TABLE IF NOT EXISTS `awt_paging` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content_1` mediumtext COLLATE utf8mb4_general_ci,
  `content_2` mediumtext COLLATE utf8mb4_general_ci,
  `status` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `override` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_password_reset`
--

CREATE TABLE IF NOT EXISTS `awt_password_reset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `code` int NOT NULL,
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_plugins`
--

CREATE TABLE IF NOT EXISTS `awt_plugins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_settings`
--

CREATE TABLE IF NOT EXISTS `awt_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `required_permission_level` int NOT NULL DEFAULT '0',
  `category` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Miscellaneous',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awt_settings`
--

INSERT IGNORE INTO `awt_settings` (`id`, `name`, `value`, `required_permission_level`, `category`) VALUES
(1, 'enable_caching', 'false', 0, 'General'),
(2, 'page_caching_time', '150', 0, 'General'),
(3, 'cache_in_session_time', '300', 0, 'General'),
(4, 'whitelist', 'false', 0, 'Security'),
(5, 'whitelist_list', '127.0.0.1 ::1 localhost', 0, 'Security'),
(6, 'use_plugins', 'true', 0, 'General'),
(7, 'hostname_path', '/', 0, 'General'), -- Fixed the opening parenthesis here
(10, 'Enable API', 'true', 0, 'Security'),
(11, 'API request whitelist', '*', 0, 'Security'),
(13, 'PHP Error reporting', '1', 1, 'Security');


-- --------------------------------------------------------

--
-- Table structure for table `awt_themes`
--

CREATE TABLE IF NOT EXISTS `awt_themes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `placeholder` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `active` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `awt_themes`
--

INSERT IGNORE INTO `awt_themes` (`id`, `name`, `description`, `version`, `placeholder`, `active`)
VALUES (1, 'Twenty-Twenty-Three', 'This is a sleek and modern theme for your website', '0.0.1', 'placeholder.png', 1);


-- --------------------------------------------------------

--
-- Table structure for table `awt_theme_page`
--

CREATE TABLE IF NOT EXISTS `awt_theme_page` (
  `id` int NOT NULL AUTO_INCREMENT,
  `theme_id` int NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `awt_theme_settings`
--

CREATE TABLE IF NOT EXISTS `awt_theme_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `theme_id` int NOT NULL,
  `category` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

START TRANSACTION;

ALTER TABLE `awt_paging` ADD `description` VARCHAR(255) DEFAULT NULL AFTER `name`; 

COMMIT;
