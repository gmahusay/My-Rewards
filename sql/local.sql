-- MySQL dump 10.13  Distrib 8.0.35, for macos13 (arm64)
--
-- Host: localhost    Database: local
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `claim_categories`
--

DROP TABLE IF EXISTS `claim_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `claim_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_reward` int NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `claim_categories_business_id_foreign` (`business_id`),
  CONSTRAINT `claim_categories_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `claim_categories`
--

LOCK TABLES `claim_categories` WRITE;
/*!40000 ALTER TABLE `claim_categories` DISABLE KEYS */;
INSERT INTO `claim_categories` VALUES (1,2,'7Eleven Claims 500 Total Receipt to 5pints',5,'categories/NXggxm392B8O6oY3cijZhgJIsfae0wk83vQOpt0P.png','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',1,'2026-02-02','2026-02-01 22:30:58','2026-02-01 23:21:27');
/*!40000 ALTER TABLE `claim_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `claims` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `business_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `rewarded_points` int NOT NULL DEFAULT '0',
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `claims_category_invoice_unique` (`category_id`,`invoice_number`),
  KEY `claims_user_id_foreign` (`user_id`),
  KEY `claims_business_id_foreign` (`business_id`),
  CONSTRAINT `claims_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `claims_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `claim_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `claims_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `claims`
--

LOCK TABLES `claims` WRITE;
/*!40000 ALTER TABLE `claims` DISABLE KEYS */;
INSERT INTO `claims` VALUES (1,25,2,NULL,'Claim 1','this is claim 1',500.00,5,'INV-1','Ayala','approved','claims/ywqAveXfF1nSLm0a4E3KSPXutEmlx9dhGmCKOe4Z.png',NULL,'2026-01-30 01:11:34','2026-01-30 01:11:58');
INSERT INTO `claims` VALUES (2,24,2,1,'711Claims','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',500.00,5,'INV-1','7Eleven','approved','claims/AGKaoJG8IHShiHTeWiFmeZyI5RB35lTMLaDVoTmS.png',NULL,'2026-02-01 23:15:58','2026-02-01 23:25:22');
/*!40000 ALTER TABLE `claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_likes`
--

DROP TABLE IF EXISTS `comment_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comment_likes_comment_id_user_id_unique` (`comment_id`,`user_id`),
  KEY `comment_likes_user_id_foreign` (`user_id`),
  CONSTRAINT `comment_likes_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_likes`
--

LOCK TABLES `comment_likes` WRITE;
/*!40000 ALTER TABLE `comment_likes` DISABLE KEYS */;
INSERT INTO `comment_likes` VALUES (1,1,2,'2026-02-01 21:46:47','2026-02-01 21:46:47');
INSERT INTO `comment_likes` VALUES (2,2,24,'2026-02-01 21:47:14','2026-02-01 21:47:14');
INSERT INTO `comment_likes` VALUES (3,3,25,'2026-03-16 21:01:51','2026-03-16 21:01:51');
INSERT INTO `comment_likes` VALUES (4,4,2,'2026-03-16 21:02:19','2026-03-16 21:02:19');
INSERT INTO `comment_likes` VALUES (5,3,2,'2026-03-16 21:02:30','2026-03-16 21:02:30');
/*!40000 ALTER TABLE `comment_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_event_id_foreign` (`event_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_parent_id_foreign` (`parent_id`),
  CONSTRAINT `comments_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,24,'I am excited for this event','2026-02-01 21:44:57','2026-02-01 21:44:57',NULL);
INSERT INTO `comments` VALUES (2,1,2,'me too, see there my dear :)','2026-02-01 21:47:04','2026-02-01 21:47:04',1);
INSERT INTO `comments` VALUES (3,4,2,'kuyog si customer 1 guys :D','2026-03-16 21:01:43','2026-03-16 21:01:43',NULL);
INSERT INTO `comments` VALUES (4,4,25,'kuyog ko kay manginhas ko hahaha','2026-03-16 21:02:07','2026-03-16 21:02:07',3);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_participants`
--

DROP TABLE IF EXISTS `event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('going','maybe','not_going') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'going',
  `attended_at` timestamp NULL DEFAULT NULL,
  `points_awarded` tinyint(1) NOT NULL DEFAULT '0',
  `awarded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_participants_event_id_user_id_unique` (`event_id`,`user_id`),
  KEY `event_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_participants`
--

LOCK TABLES `event_participants` WRITE;
/*!40000 ALTER TABLE `event_participants` DISABLE KEYS */;
INSERT INTO `event_participants` VALUES (1,1,24,'going',NULL,0,NULL,'2026-02-01 21:34:16','2026-02-01 21:34:16');
INSERT INTO `event_participants` VALUES (2,1,2,'going',NULL,0,NULL,'2026-02-01 21:48:50','2026-02-01 21:48:50');
INSERT INTO `event_participants` VALUES (3,2,24,'going','2026-02-01 21:55:51',1,'2026-02-01 21:55:51','2026-02-01 21:51:26','2026-02-01 21:55:51');
INSERT INTO `event_participants` VALUES (4,2,2,'going',NULL,0,NULL,'2026-02-01 21:52:23','2026-02-01 21:52:23');
INSERT INTO `event_participants` VALUES (5,4,25,'going',NULL,0,NULL,'2026-03-16 21:01:18','2026-03-16 21:01:18');
/*!40000 ALTER TABLE `event_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_reactions`
--

DROP TABLE IF EXISTS `event_reactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `event_reactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_reactions_event_id_user_id_unique` (`event_id`,`user_id`),
  KEY `event_reactions_user_id_foreign` (`user_id`),
  CONSTRAINT `event_reactions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_reactions`
--

LOCK TABLES `event_reactions` WRITE;
/*!40000 ALTER TABLE `event_reactions` DISABLE KEYS */;
INSERT INTO `event_reactions` VALUES (1,1,24,'like','2026-02-01 21:44:36','2026-02-01 21:44:36');
INSERT INTO `event_reactions` VALUES (2,4,25,'love','2026-03-16 21:01:21','2026-03-16 21:01:21');
/*!40000 ALTER TABLE `event_reactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `points_reward` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_business_id_foreign` (`business_id`),
  CONSTRAINT `events_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,2,'Go4Gold','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','cebu city','2026-02-02 14:00:00',30,1,NULL,'2026-02-01 21:33:02','2026-02-01 21:33:02');
INSERT INTO `events` VALUES (2,2,'3V3 Basketball','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','Araneta','2026-02-02 13:52:00',1,1,'events/uEEmkiNrwTAA5YNyXfvA92nrw43ysaUSmqA1YJQH.jpg','2026-02-01 21:51:13','2026-02-01 22:35:40');
INSERT INTO `events` VALUES (3,2,'Event Feb 14','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','SOGO','2026-02-14 13:31:00',12,1,'events/f8sGmkKf5fTCezEAZTpuT9cSUY6Po4kZAFvrkzEA.jpg','2026-02-02 21:31:58','2026-02-02 21:31:58');
INSERT INTO `events` VALUES (4,2,'RC by the Sea','ligo dagat','Lapu Lapu','2026-04-18 11:00:00',100,1,'events/oTffWMYp1AliHSPURqBat0uXGNd107Td6f23e0RK.png','2026-03-16 21:01:01','2026-03-16 21:01:01');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpi_categories`
--

DROP TABLE IF EXISTS `kpi_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpi_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points_reward` int NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kpi_categories_business_id_foreign` (`business_id`),
  CONSTRAINT `kpi_categories_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpi_categories`
--

LOCK TABLES `kpi_categories` WRITE;
/*!40000 ALTER TABLE `kpi_categories` DISABLE KEYS */;
INSERT INTO `kpi_categories` VALUES (1,2,'Monthly salees target','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','kpis/e1EnZ9sAUwWGdh3jgSUjVuCOtcpUwOchpZv3I3u5.jpg',15,'2026-02-02','2026-03-01',1,'2026-02-03 00:39:50','2026-02-03 00:40:41');
/*!40000 ALTER TABLE `kpi_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kpis`
--

DROP TABLE IF EXISTS `kpis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kpis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `proof_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rewarded_points` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kpis_category_id_foreign` (`category_id`),
  KEY `kpis_user_id_foreign` (`user_id`),
  CONSTRAINT `kpis_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `kpi_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kpis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpis`
--

LOCK TABLES `kpis` WRITE;
/*!40000 ALTER TABLE `kpis` DISABLE KEYS */;
INSERT INTO `kpis` VALUES (1,1,24,'I got 500 sales','kpi-proofs/AwcdRceJ6Wa14KRrRtZduNiUyrzWfhayiWbjvKf7.png','approved',15,'2026-02-03 03:26:36','2026-02-03 04:24:11');
INSERT INTO `kpis` VALUES (2,1,24,'got it','kpi-proofs/ShWQ3zW8Mk9mQlwlRBDJcUtf6NfoTzw8zfRYo1Le.png','pending',0,'2026-02-04 21:59:04','2026-02-04 21:59:04');
/*!40000 ALTER TABLE `kpis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2026_01_30_051329_add_role_to_users_table',1);
INSERT INTO `migrations` VALUES (5,'2026_01_30_053527_add_business_id_to_users_table',2);
INSERT INTO `migrations` VALUES (6,'2026_01_30_054403_add_profile_photo_path_to_users_table',3);
INSERT INTO `migrations` VALUES (7,'2026_01_30_060227_add_points_to_users_table',4);
INSERT INTO `migrations` VALUES (8,'2026_01_30_060227_create_point_transactions_table',4);
INSERT INTO `migrations` VALUES (9,'2026_01_30_144002_add_business_profile_fields_to_users_table',5);
INSERT INTO `migrations` VALUES (10,'2026_01_30_145500_create_products_table',6);
INSERT INTO `migrations` VALUES (11,'2026_01_30_145501_create_orders_table',6);
INSERT INTO `migrations` VALUES (12,'2026_01_30_145502_create_order_items_table',6);
INSERT INTO `migrations` VALUES (13,'2026_01_30_150500_add_payment_gateway_settings_to_users_table',7);
INSERT INTO `migrations` VALUES (14,'2026_01_30_152200_update_order_enums',8);
INSERT INTO `migrations` VALUES (15,'2026_01_30_164700_create_claims_table',9);
INSERT INTO `migrations` VALUES (16,'2026_01_30_170400_add_rewarded_points_to_claims_table',10);
INSERT INTO `migrations` VALUES (17,'2026_01_30_171000_add_invoice_and_store_to_claims_table',11);
INSERT INTO `migrations` VALUES (18,'2026_01_30_172000_create_nominations_system_tables',12);
INSERT INTO `migrations` VALUES (19,'2026_01_30_172600_add_winner_to_nomination_categories',13);
INSERT INTO `migrations` VALUES (20,'2026_01_30_094422_create_notifications_table',14);
INSERT INTO `migrations` VALUES (21,'2026_02_02_050741_create_events_table',15);
INSERT INTO `migrations` VALUES (22,'2026_02_02_052848_create_event_participants_table',16);
INSERT INTO `migrations` VALUES (23,'2026_02_02_053927_add_status_to_event_participants_table',17);
INSERT INTO `migrations` VALUES (24,'2026_02_02_054235_create_comments_table',18);
INSERT INTO `migrations` VALUES (25,'2026_02_02_054235_create_event_reactions_table',18);
INSERT INTO `migrations` VALUES (26,'2026_02_02_054537_add_parent_id_to_comments_table',19);
INSERT INTO `migrations` VALUES (27,'2026_02_02_054537_create_comment_likes_table',19);
INSERT INTO `migrations` VALUES (28,'2026_02_02_061005_create_claim_categories_table',20);
INSERT INTO `migrations` VALUES (29,'2026_02_02_061039_add_category_id_to_claims_table',20);
INSERT INTO `migrations` VALUES (30,'2026_02_02_062000_add_points_reward_to_claim_categories_table',21);
INSERT INTO `migrations` VALUES (31,'2026_02_02_062755_add_image_to_claim_categories_and_events_tables',22);
INSERT INTO `migrations` VALUES (32,'2026_02_02_063444_add_end_date_to_claim_categories_table',23);
INSERT INTO `migrations` VALUES (33,'2026_02_03_070000_create_referral_system_tables',24);
INSERT INTO `migrations` VALUES (34,'2026_02_03_140000_create_kpi_system_tables',25);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nomination_categories`
--

DROP TABLE IF EXISTS `nomination_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nomination_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `points_reward` int NOT NULL DEFAULT '0',
  `winner_id` bigint unsigned DEFAULT NULL,
  `awarded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nomination_categories_business_id_foreign` (`business_id`),
  KEY `nomination_categories_winner_id_foreign` (`winner_id`),
  CONSTRAINT `nomination_categories_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nomination_categories_winner_id_foreign` FOREIGN KEY (`winner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nomination_categories`
--

LOCK TABLES `nomination_categories` WRITE;
/*!40000 ALTER TABLE `nomination_categories` DISABLE KEYS */;
INSERT INTO `nomination_categories` VALUES (1,2,'El Presidente','Nominate employee for el Presidente','nominations/GBy2Pg7ldTIwSKbBUF98T4htbUpfwPwzVMoRgEMn.jpg','2026-01-29','2026-01-31',10,24,'2026-01-30 01:39:56',1,'2026-01-30 01:25:15','2026-01-30 01:39:56');
INSERT INTO `nomination_categories` VALUES (2,2,'Employee of the month','this award is for the best employee in this month of January 2026','nominations/vyJyJ1Eb9R3QVQmxoRdI45vrYgAV6dEM9lvkwqTc.jpg','2026-01-01','2026-01-31',50,NULL,NULL,1,'2026-01-30 01:48:08','2026-01-30 01:54:42');
INSERT INTO `nomination_categories` VALUES (3,2,'Employee of the month','Employee of the month','nominations/SZbACfAbWb5s968IKzRqEfQZMf55MYakba2hLnig.jpg','2026-02-01','2026-02-28',50,NULL,NULL,1,'2026-01-30 01:51:36','2026-01-30 01:51:36');
INSERT INTO `nomination_categories` VALUES (4,2,'Week Award','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','nominations/qKoWu3PkOweq6dHipUwNqj3xjQURfk16fY8lLMKW.jpg','2026-02-01','2026-02-06',0,NULL,NULL,1,'2026-02-01 22:57:22','2026-02-01 22:57:22');
INSERT INTO `nomination_categories` VALUES (5,2,'Best kisser','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','nominations/HjGbcTHDV991BKKaP1RlB6gdm1XoKUkO3JUqCN7I.jpg','2026-02-14','2026-02-15',1,NULL,NULL,1,'2026-02-02 21:32:44','2026-02-02 21:33:02');
/*!40000 ALTER TABLE `nomination_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nominations`
--

DROP TABLE IF EXISTS `nominations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nominations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `nominator_id` bigint unsigned NOT NULL,
  `nominee_id` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nominations_category_id_nominator_id_unique` (`category_id`,`nominator_id`),
  KEY `nominations_nominator_id_foreign` (`nominator_id`),
  KEY `nominations_nominee_id_foreign` (`nominee_id`),
  CONSTRAINT `nominations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `nomination_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nominations_nominator_id_foreign` FOREIGN KEY (`nominator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nominations_nominee_id_foreign` FOREIGN KEY (`nominee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nominations`
--

LOCK TABLES `nominations` WRITE;
/*!40000 ALTER TABLE `nominations` DISABLE KEYS */;
INSERT INTO `nominations` VALUES (1,1,24,26,'best for el Presidente','2026-01-30 01:30:09','2026-01-30 01:30:09');
INSERT INTO `nominations` VALUES (2,1,27,24,'nice for el Presidente','2026-01-30 01:30:51','2026-01-30 01:30:51');
INSERT INTO `nominations` VALUES (3,1,26,24,'this is the best','2026-01-30 01:31:17','2026-01-30 01:31:17');
INSERT INTO `nominations` VALUES (4,2,24,27,'he is the best employee','2026-01-30 02:13:41','2026-01-30 02:13:41');
/*!40000 ALTER TABLE `nominations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('027142b0-9fd5-4ad2-8a7e-4d20818648ce','App\\Notifications\\NewNominationCategory','App\\Models\\User',24,'{\"category_id\":4,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Week Award\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}','2026-02-03 00:41:15','2026-02-01 22:57:22','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('0a37c0bc-20ce-4f11-91b1-1c2967d58c92','App\\Notifications\\NewReferralCampaign','App\\Models\\User',24,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Referral Campaign!\",\"message\":\"Business User launched: 7Eleven Referral. Earn 2 pts per referral!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/referrals\",\"type\":\"referral_campaign\"}','2026-02-03 00:41:15','2026-02-02 21:43:51','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('13372f37-3ecb-4b36-af58-de52d434bb4c','App\\Notifications\\ClaimSubmitted','App\\Models\\User',2,'{\"claim_id\":2,\"user_name\":\"Employee1\",\"title\":\"New Claim Submitted\",\"message\":\"Employee1 has submitted a new claim for \'711Claims\'.\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/claims\\/2\",\"type\":\"claim\"}',NULL,'2026-02-01 23:15:58','2026-02-01 23:15:58');
INSERT INTO `notifications` VALUES ('18f48647-ff55-46e2-b6d8-ccd3f828c14a','App\\Notifications\\NewEventCreated','App\\Models\\User',24,'{\"event_id\":2,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'3V3 Basketball\' has been scheduled for Feb 02, 2026. Join now to earn 1 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}','2026-02-03 00:41:15','2026-02-01 21:51:13','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('1f211b7e-72aa-4642-9a3d-ed8c8d7747e3','App\\Notifications\\NewNominationCategory','App\\Models\\User',27,'{\"category_id\":5,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Best if Kiss\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-02-02 21:32:44','2026-02-02 21:32:44');
INSERT INTO `notifications` VALUES ('2a502550-c980-41fb-bbcc-19f634c68d3c','App\\Notifications\\NewNominationCategory','App\\Models\\User',27,'{\"category_id\":4,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Week Award\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-02-01 22:57:22','2026-02-01 22:57:22');
INSERT INTO `notifications` VALUES ('498a09fa-30da-4774-8bc5-e895d8ecb46e','App\\Notifications\\NewClaimCategoryCreated','App\\Models\\User',24,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Claim Category Available\",\"message\":\"Business User has added a new claim category: 7Elevent Claims 500 Total Receipt to 5pints (5 pts).\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/claims\",\"type\":\"claim_category\"}','2026-02-03 00:41:15','2026-02-01 22:30:58','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('4f353645-8314-4723-9b64-231852355f22','App\\Notifications\\NewEventCreated','App\\Models\\User',24,'{\"event_id\":4,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'RC by the Sea\' has been scheduled for Apr 18, 2026. Join now to earn 100 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-03-16 21:01:02','2026-03-16 21:01:02');
INSERT INTO `notifications` VALUES ('5495bb20-f8c6-419b-bc48-8eb4a1b15c12','App\\Notifications\\NewKpiCategoryCreated','App\\Models\\User',27,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New KPI Goal Available\",\"message\":\"New KPI goal \'Monthly salees target\' is now available. Earn 15 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/kpis\\/1\",\"type\":\"kpi_category_created\"}',NULL,'2026-02-03 00:39:50','2026-02-03 00:39:50');
INSERT INTO `notifications` VALUES ('5a04b392-13ac-49f3-a0bf-47ac4950a561','App\\Notifications\\NewEventCreated','App\\Models\\User',27,'{\"event_id\":3,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'Event Feb 14\' has been scheduled for Feb 14, 2026. Join now to earn 12 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-02 21:31:58','2026-02-02 21:31:58');
INSERT INTO `notifications` VALUES ('5c707583-4050-4b14-8a3e-d5fd42f1d20f','App\\Notifications\\UserJoinedEvent','App\\Models\\User',2,'{\"event_id\":1,\"user_id\":2,\"title\":\"User Joined Event\",\"message\":\"Business User has just joined the event \'Go4Gold\'.\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/events\\/1\\/participants\",\"type\":\"event\"}',NULL,'2026-02-01 21:48:51','2026-02-01 21:48:51');
INSERT INTO `notifications` VALUES ('5e767cd8-5c17-47ba-a487-bdf5b7409161','App\\Notifications\\UserJoinedEvent','App\\Models\\User',2,'{\"event_id\":2,\"user_id\":2,\"title\":\"User Joined Event\",\"message\":\"Business User has just joined the event \'3V3 Basketball\'.\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/events\\/2\\/participants\",\"type\":\"event\"}',NULL,'2026-02-01 21:52:23','2026-02-01 21:52:23');
INSERT INTO `notifications` VALUES ('68220925-47c7-40f8-a6b1-666f1eb11dc1','App\\Notifications\\NewReferralCampaign','App\\Models\\User',27,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Referral Campaign!\",\"message\":\"Business User launched: 7Eleven Referral. Earn 2 pts per referral!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/referrals\",\"type\":\"referral_campaign\"}',NULL,'2026-02-02 21:43:51','2026-02-02 21:43:51');
INSERT INTO `notifications` VALUES ('6f75bb7a-13fd-4804-b04d-eb74956b7f4a','App\\Notifications\\ClaimProcessed','App\\Models\\User',24,'{\"claim_id\":2,\"title\":\"Claim Approved\",\"message\":\"Your claim \'711Claims\' has been approved.\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/claims\",\"type\":\"claim\"}','2026-02-03 00:41:15','2026-02-01 23:25:22','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('7062bce0-3ed9-42cb-89e7-8ca4fa48aef4','App\\Notifications\\NewEventCreated','App\\Models\\User',25,'{\"event_id\":4,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'RC by the Sea\' has been scheduled for Apr 18, 2026. Join now to earn 100 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-03-16 21:01:02','2026-03-16 21:01:02');
INSERT INTO `notifications` VALUES ('7d04a18e-4694-437e-9f0c-b6d1cbb7c117','App\\Notifications\\NewKpiSubmitted','App\\Models\\User',2,'{\"kpi_id\":1,\"user_name\":\"Employee1\",\"category_name\":\"Monthly salees target\",\"title\":\"New KPI Submission\",\"message\":\"Employee1 submitted a KPI for \'Monthly salees target\'\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/kpis\\/categories\\/1\",\"type\":\"kpi_submitted\"}',NULL,'2026-02-03 03:26:36','2026-02-03 03:26:36');
INSERT INTO `notifications` VALUES ('81c2f355-5f9a-4cc8-bd91-58439e53238c','App\\Notifications\\NewClaimCategoryCreated','App\\Models\\User',26,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Claim Category Available\",\"message\":\"Business User has added a new claim category: 7Elevent Claims 500 Total Receipt to 5pints (5 pts).\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/claims\",\"type\":\"claim_category\"}',NULL,'2026-02-01 22:30:58','2026-02-01 22:30:58');
INSERT INTO `notifications` VALUES ('8a342b84-0424-489a-b1bd-1a24a9e6096f','App\\Notifications\\NewKpiSubmitted','App\\Models\\User',2,'{\"kpi_id\":2,\"user_name\":\"Employee1\",\"category_name\":\"Monthly salees target\",\"title\":\"New KPI Submission\",\"message\":\"Employee1 submitted a KPI for \'Monthly salees target\'\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/kpis\\/categories\\/1\",\"type\":\"kpi_submitted\"}',NULL,'2026-02-04 21:59:04','2026-02-04 21:59:04');
INSERT INTO `notifications` VALUES ('8e1f7d2a-c53b-4745-bba0-60652882b030','App\\Notifications\\NewEventCreated','App\\Models\\User',26,'{\"event_id\":3,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'Event Feb 14\' has been scheduled for Feb 14, 2026. Join now to earn 12 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-02 21:31:58','2026-02-02 21:31:58');
INSERT INTO `notifications` VALUES ('959f2c3b-7d1d-4f15-9153-11e617fc300e','App\\Notifications\\NewReferralCampaign','App\\Models\\User',25,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Referral Campaign!\",\"message\":\"Business User launched: 7Eleven Referral. Earn 2 pts per referral!\",\"url\":\"http:\\/\\/my-rewards.local\\/customer\\/referrals\",\"type\":\"referral_campaign\"}',NULL,'2026-02-02 21:43:51','2026-02-02 21:43:51');
INSERT INTO `notifications` VALUES ('99b00d6d-247c-461a-b56f-0e27be3aa0ac','App\\Notifications\\NewEventCreated','App\\Models\\User',25,'{\"event_id\":2,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'3V3 Basketball\' has been scheduled for Feb 02, 2026. Join now to earn 1 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-01 21:51:13','2026-02-01 21:51:13');
INSERT INTO `notifications` VALUES ('9d4a19f1-1ac7-4826-bb42-4889d8debcc7','App\\Notifications\\NewEventCreated','App\\Models\\User',26,'{\"event_id\":2,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'3V3 Basketball\' has been scheduled for Feb 02, 2026. Join now to earn 1 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-01 21:51:13','2026-02-01 21:51:13');
INSERT INTO `notifications` VALUES ('9dbee35a-8df5-4899-bf35-f4175907b972','App\\Notifications\\NewKpiCategoryCreated','App\\Models\\User',26,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New KPI Goal Available\",\"message\":\"New KPI goal \'Monthly salees target\' is now available. Earn 15 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/kpis\\/1\",\"type\":\"kpi_category_created\"}',NULL,'2026-02-03 00:39:50','2026-02-03 00:39:50');
INSERT INTO `notifications` VALUES ('a1f0ba45-7948-4230-9834-5cbc1f7075b2','App\\Notifications\\NewNominationCategory','App\\Models\\User',26,'{\"category_id\":4,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Week Award\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-02-01 22:57:22','2026-02-01 22:57:22');
INSERT INTO `notifications` VALUES ('aa8a5a69-f1d0-4c66-a73d-285fdfed6abe','App\\Notifications\\ReferralApproved','App\\Models\\User',24,'{\"referral_id\":2,\"points_awarded\":2,\"title\":\"Referral Approved!\",\"message\":\"Your referral for email2@example.com was approved! You earned 2 points.\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/referrals\",\"type\":\"referral_approved\"}','2026-02-03 00:41:15','2026-02-02 22:01:33','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('abc52852-06b8-49e7-b3c7-cbac48b39d43','App\\Notifications\\NewNominationCategory','App\\Models\\User',26,'{\"category_id\":5,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Best if Kiss\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-02-02 21:32:44','2026-02-02 21:32:44');
INSERT INTO `notifications` VALUES ('ade1667e-175a-4d8b-a23d-e7f622af3f22','App\\Notifications\\NewNominationCategory','App\\Models\\User',26,'{\"category_id\":3,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Employee of the month\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-01-30 01:51:36','2026-01-30 01:51:36');
INSERT INTO `notifications` VALUES ('ae4e26ae-329b-4948-a382-eb8d54db8c53','App\\Notifications\\NewReferralSubmitted','App\\Models\\User',2,'{\"referral_id\":2,\"referrer_name\":\"Employee1\",\"title\":\"New Referral Received\",\"message\":\"Employee1 referred email2@example.com for \\\"7Eleven Referral\\\".\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/referrals\",\"type\":\"referral_submitted\"}',NULL,'2026-02-02 21:57:08','2026-02-02 21:57:08');
INSERT INTO `notifications` VALUES ('af7cf580-7337-4183-a0ec-1ebb5a211883','App\\Notifications\\NewEventCreated','App\\Models\\User',24,'{\"event_id\":3,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'Event Feb 14\' has been scheduled for Feb 14, 2026. Join now to earn 12 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}','2026-02-03 00:41:15','2026-02-02 21:31:58','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('b24fcd7f-9f34-4b5d-a023-d38a97c86a20','App\\Notifications\\NewClaimCategoryCreated','App\\Models\\User',27,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Claim Category Available\",\"message\":\"Business User has added a new claim category: 7Elevent Claims 500 Total Receipt to 5pints (5 pts).\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/claims\",\"type\":\"claim_category\"}',NULL,'2026-02-01 22:30:58','2026-02-01 22:30:58');
INSERT INTO `notifications` VALUES ('bb779af7-7628-49f2-aecf-e8827c54939b','App\\Notifications\\NewReferralCampaign','App\\Models\\User',26,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Referral Campaign!\",\"message\":\"Business User launched: 7Eleven Referral. Earn 2 pts per referral!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/referrals\",\"type\":\"referral_campaign\"}',NULL,'2026-02-02 21:43:51','2026-02-02 21:43:51');
INSERT INTO `notifications` VALUES ('c19edc4a-8581-4f31-981a-7958e72c9a39','App\\Notifications\\UserJoinedEvent','App\\Models\\User',2,'{\"event_id\":2,\"user_id\":24,\"title\":\"User Joined Event\",\"message\":\"Employee1 has just joined the event \'3V3 Basketball\'.\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/events\\/2\\/participants\",\"type\":\"event\"}',NULL,'2026-02-01 21:51:26','2026-02-01 21:51:26');
INSERT INTO `notifications` VALUES ('c59dd106-06a0-45fb-bf22-6573b8910927','App\\Notifications\\NewKpiCategoryCreated','App\\Models\\User',24,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New KPI Goal Available\",\"message\":\"New KPI goal \'Monthly salees target\' is now available. Earn 15 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/kpis\\/1\",\"type\":\"kpi_category_created\"}','2026-02-03 00:41:15','2026-02-03 00:39:50','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('d12a9b10-514d-40fe-8979-8c3bacba2452','App\\Notifications\\NewEventCreated','App\\Models\\User',26,'{\"event_id\":4,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'RC by the Sea\' has been scheduled for Apr 18, 2026. Join now to earn 100 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-03-16 21:01:02','2026-03-16 21:01:02');
INSERT INTO `notifications` VALUES ('d490a001-d912-42d0-9d9b-50eb0c04a826','App\\Notifications\\NewEventCreated','App\\Models\\User',25,'{\"event_id\":3,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'Event Feb 14\' has been scheduled for Feb 14, 2026. Join now to earn 12 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-02 21:31:58','2026-02-02 21:31:58');
INSERT INTO `notifications` VALUES ('d8c76358-3bd5-47d9-8a24-5943a9c2cded','App\\Notifications\\UserJoinedEvent','App\\Models\\User',2,'{\"event_id\":4,\"user_id\":25,\"title\":\"User Joined Event\",\"message\":\"customer1 has just joined the event \'RC by the Sea\'.\",\"url\":\"http:\\/\\/my-rewards.local\\/business\\/events\\/4\\/participants\",\"type\":\"event\"}',NULL,'2026-03-16 21:01:18','2026-03-16 21:01:18');
INSERT INTO `notifications` VALUES ('ddad6585-f013-4210-abbf-9a7ed9c54b54','App\\Notifications\\NewNominationCategory','App\\Models\\User',24,'{\"category_id\":3,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Employee of the month\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}','2026-01-30 02:13:17','2026-01-30 01:51:36','2026-01-30 02:13:17');
INSERT INTO `notifications` VALUES ('dec82880-2e59-4289-9aaf-e7a466970add','App\\Notifications\\NewEventCreated','App\\Models\\User',27,'{\"event_id\":2,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'3V3 Basketball\' has been scheduled for Feb 02, 2026. Join now to earn 1 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-02-01 21:51:13','2026-02-01 21:51:13');
INSERT INTO `notifications` VALUES ('dff1e3b3-2d21-4854-8b7c-53abbebdf61d','App\\Notifications\\NewClaimCategoryCreated','App\\Models\\User',25,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New Claim Category Available\",\"message\":\"Business User has added a new claim category: 7Elevent Claims 500 Total Receipt to 5pints (5 pts).\",\"url\":\"http:\\/\\/my-rewards.local\\/customer\\/claims\",\"type\":\"claim_category\"}',NULL,'2026-02-01 22:30:58','2026-02-01 22:30:58');
INSERT INTO `notifications` VALUES ('e00bc40d-b90b-4c1e-81b1-3f2d0655ab03','App\\Notifications\\NewEventCreated','App\\Models\\User',27,'{\"event_id\":4,\"title\":\"New Event Scheduled!\",\"message\":\"A new event \'RC by the Sea\' has been scheduled for Apr 18, 2026. Join now to earn 100 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/events\",\"type\":\"event\"}',NULL,'2026-03-16 21:01:02','2026-03-16 21:01:02');
INSERT INTO `notifications` VALUES ('e7c809bd-6669-41a2-8900-1cc415e128ef','App\\Notifications\\NewKpiCategoryCreated','App\\Models\\User',25,'{\"category_id\":1,\"business_name\":\"Business User\",\"title\":\"New KPI Goal Available\",\"message\":\"New KPI goal \'Monthly salees target\' is now available. Earn 15 points!\",\"url\":\"http:\\/\\/my-rewards.local\\/customer\\/kpis\\/1\",\"type\":\"kpi_category_created\"}',NULL,'2026-02-03 00:39:50','2026-02-03 00:39:50');
INSERT INTO `notifications` VALUES ('e7c84acd-195d-49e6-906e-d7582992076b','App\\Notifications\\NewNominationCategory','App\\Models\\User',24,'{\"category_id\":5,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Best if Kiss\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}','2026-02-03 00:41:15','2026-02-02 21:32:44','2026-02-03 00:41:15');
INSERT INTO `notifications` VALUES ('f4da867b-b063-497b-a50d-1e01557ad3d5','App\\Notifications\\NewNominationCategory','App\\Models\\User',27,'{\"category_id\":3,\"title\":\"New Nomination Open!\",\"message\":\"A new nomination category \'Employee of the month\' has been created. Nominate a colleague now!\",\"url\":\"http:\\/\\/my-rewards.local\\/employee\\/nominations\",\"type\":\"nomination\"}',NULL,'2026-01-30 01:51:36','2026-01-30 01:51:36');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_points` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,1,80000.00,160000,'2026-01-29 22:59:43','2026-01-29 22:59:43');
INSERT INTO `order_items` VALUES (2,2,1,1,80000.00,160000,'2026-01-29 23:06:49','2026-01-29 23:06:49');
INSERT INTO `order_items` VALUES (3,3,1,1,80000.00,160000,'2026-01-29 23:16:21','2026-01-29 23:16:21');
INSERT INTO `order_items` VALUES (4,4,1,1,80000.00,160000,'2026-01-29 23:21:40','2026-01-29 23:21:40');
INSERT INTO `order_items` VALUES (5,5,1,1,80000.00,160000,'2026-03-16 20:56:37','2026-03-16 20:56:37');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `business_id` bigint unsigned NOT NULL,
  `total_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_points` int NOT NULL DEFAULT '0',
  `payment_method` enum('cash','points','stripe','paypal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','completed','cancelled','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_business_id_foreign` (`business_id`),
  CONSTRAINT `orders_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,24,2,80000.00,0,'cash','completed','2026-01-29 22:59:43','2026-01-29 22:59:43');
INSERT INTO `orders` VALUES (2,24,2,80000.00,160000,'cash','completed','2026-01-29 23:06:49','2026-01-29 23:06:49');
INSERT INTO `orders` VALUES (3,24,2,80000.00,160000,'cash','completed','2026-01-29 23:16:21','2026-01-29 23:16:21');
INSERT INTO `orders` VALUES (4,24,2,80000.00,160000,'paypal','completed','2026-01-29 23:21:40','2026-01-29 23:22:36');
INSERT INTO `orders` VALUES (5,25,2,80000.00,160000,'paypal','completed','2026-03-16 20:56:37','2026-03-16 20:56:41');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_transactions`
--

DROP TABLE IF EXISTS `point_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned DEFAULT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `amount` bigint unsigned NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `point_transactions_sender_id_foreign` (`sender_id`),
  KEY `point_transactions_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `point_transactions_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `point_transactions_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_transactions`
--

LOCK TABLES `point_transactions` WRITE;
/*!40000 ALTER TABLE `point_transactions` DISABLE KEYS */;
INSERT INTO `point_transactions` VALUES (5,NULL,2,1000,'Admin adjusted points: +1000','2026-01-29 22:18:49','2026-01-29 22:18:49');
INSERT INTO `point_transactions` VALUES (8,2,24,50,'Initial Allocation from Business','2026-01-29 22:29:40','2026-01-29 22:29:40');
INSERT INTO `point_transactions` VALUES (9,2,25,200,'Initial Allocation from Business','2026-01-30 00:51:20','2026-01-30 00:51:20');
INSERT INTO `point_transactions` VALUES (10,2,24,2,'Referral Reward: 7Eleven Referral','2026-02-02 22:01:33','2026-02-02 22:01:33');
INSERT INTO `point_transactions` VALUES (11,2,24,15,'KPI Reward: Monthly salees target','2026-02-03 04:24:11','2026-02-03 04:24:11');
/*!40000 ALTER TABLE `point_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_cash` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price_points` int NOT NULL DEFAULT '0',
  `stock_quantity` int NOT NULL DEFAULT '0',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_business_id_foreign` (`business_id`),
  CONSTRAINT `products_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,2,'Samsung Neo QLED QN80F 100 Inch 4K AI Smart TV','Samsung Neo QLED QN80F 100 Inch 4K AI Smart TV',80000.00,160000,95,'products/ToiSRHGx0p7nnOBw2NkVgz5asSM7tBlEq3fDq2zL.jpg',1,'2026-01-29 22:59:21','2026-03-16 20:56:41');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referral_categories`
--

DROP TABLE IF EXISTS `referral_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referral_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_reward` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referral_categories_business_id_foreign` (`business_id`),
  CONSTRAINT `referral_categories_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referral_categories`
--

LOCK TABLES `referral_categories` WRITE;
/*!40000 ALTER TABLE `referral_categories` DISABLE KEYS */;
INSERT INTO `referral_categories` VALUES (1,2,'7Eleven Referral','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.','referrals/GYZCxvLxBoa3NU4Gfxg3LluKsnEWT9aMGrnKT2jW.png','https://www.7-eleven.com.ph/?ref=my-rewards-1',2,1,'2026-02-02 21:43:51','2026-02-02 21:43:51');
/*!40000 ALTER TABLE `referral_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `referrer_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `referred_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `rewarded_points` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrals_category_id_foreign` (`category_id`),
  KEY `referrals_referrer_id_foreign` (`referrer_id`),
  CONSTRAINT `referrals_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `referral_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referrals`
--

LOCK TABLES `referrals` WRITE;
/*!40000 ALTER TABLE `referrals` DISABLE KEYS */;
INSERT INTO `referrals` VALUES (1,1,24,'pending','email1@example.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',NULL,'2026-02-02 21:55:39','2026-02-02 21:55:39');
INSERT INTO `referrals` VALUES (2,1,24,'approved','email2@example.com','Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',2,'2026-02-02 21:57:08','2026-02-02 22:01:33');
/*!40000 ALTER TABLE `referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('8UOnCJOrctHTmTj9wCVndXJXFRHomftXu3aKUwBj',24,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoibGU5YWNic2NUUmFiaTZBMnd5VkJkNEtrZ0F1VXVOdWNLQW1GaXBsYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9teS1yZXdhcmRzLmxvY2FsL2VtcGxveWVlL2twaXMiO3M6NToicm91dGUiO3M6MTk6ImVtcGxveWVlLmtwaXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyNDt9',1779934990);
INSERT INTO `sessions` VALUES ('be9tctYYsl392hRDXaBnm5ZRDosLuKTB448Xqghx',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoic2FuNzZaRzdibGdmWVc3TjZQRVFXd2FBZkR6RnJKZ2NFR3RiSnUzNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9teS1yZXdhcmRzLmxvY2FsL2V2ZW50cy80IjtzOjU6InJvdXRlIjtzOjExOiJldmVudHMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1773723750);
INSERT INTO `sessions` VALUES ('KyDoEQpTycX48JQ3DxEntmciHLwnf02UuhgVZ0lG',2,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWnE3T2xYbXVlUEhzQ1I0MTFHN3h5M1dGbkVYQlpVaHQ2TGVOOFlTeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9teS1yZXdhcmRzLmxvY2FsL2J1c2luZXNzL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxODoiYnVzaW5lc3MuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1774328690);
INSERT INTO `sessions` VALUES ('t3zyJOXspyj3n6sSlqf5UYnzMedqKjzKgm6zJgwu',25,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:148.0) Gecko/20100101 Firefox/148.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM05jVWxZTXJia1l3NEM2Sm42T0I0a3laYUFBanM5YzVRc1FyYkVHeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9teS1yZXdhcmRzLmxvY2FsL2V2ZW50cy80IjtzOjU6InJvdXRlIjtzOjExOiJldmVudHMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI1O30=',1773723746);
INSERT INTO `sessions` VALUES ('XXCALh9pZ6Egs2jWfze0nKvUoa5RLpXYOI8ELOEl',24,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoid3haTGFtQUc3cnM0d2JkaTdvVGc5OW5qa3RjSGJxWUZBOVhEc21VSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9teS1yZXdhcmRzLmxvY2FsL2VtcGxveWVlL3JlZmVycmFscy8xIjtzOjU6InJvdXRlIjtzOjIzOiJlbXBsb3llZS5yZWZlcnJhbHMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI0O30=',1780454379);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `business_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` bigint unsigned NOT NULL DEFAULT '0',
  `website_logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` text COLLATE utf8mb4_unicode_ci,
  `company_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_settings` json DEFAULT NULL,
  `preferred_gateway` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_business_id_foreign` (`business_id`),
  CONSTRAINT `users_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Admin User','admin@example.com','admin',NULL,'$2y$12$ddIx6ocJsZTZ42Qk03wgv.ZC8UI948Ijk49L0IobfXIAx29MUc6om',NULL,'2026-01-29 21:15:43','2026-01-29 21:15:43',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (2,NULL,'Business User','business@example.com','business',NULL,'$2y$12$4dGpRzD8PQOIYZgG7K1ToOMoM4fNPaYDouAdECPb6D68A/J/PoMby',NULL,'2026-01-29 21:15:43','2026-03-16 20:55:38','profile-photos/gsjMxzLaWYcoTRdbbK8KmxtSxm6uDkH8NC3urH9s.jpg',707,'website-logos/FO0DyzUjFMvBheZoOXZ65Uc4tC9faF29nAZdHzr0.jpg','Rewards','Rewards','cebu city','1234567890','Geo','{\"stripe_key\": null, \"paypal_secret\": \"AZ_1vbW0bh__CGqMDfd1F_EgQ9VP-vLIMr0xZsjAZDfEVdyogFW0cX86PwHXZxRdr80o-OkuRWQHYxgD\", \"stripe_secret\": null, \"paypal_sandbox\": true, \"stripe_sandbox\": false, \"paypal_client_id\": \"AZ_1vbW0bh__CGqMDfd1F_EgQ9VP-vLIMr0xZsjAZDfEVdyogFW0cX86PwHXZxRdr80o-OkuRWQHYxgD\"}','paypal');
INSERT INTO `users` VALUES (3,NULL,'Employee User','employee@example.com','employee',NULL,'$2y$12$ayi0bgGUxn/3u.Z4q/nF6.V.PO7LSMf/0idLtDK6j6Anb2u6WI362',NULL,'2026-01-29 21:15:43','2026-01-29 21:15:43',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (4,NULL,'Customer User','customer@example.com','customer',NULL,'$2y$12$TLHDDqxg0vUNrlehyiV8o.ChXhtL1JcypYFI6iNYtZeB4ZkT3nxAu',NULL,'2026-01-29 21:15:43','2026-01-29 21:15:43',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (5,NULL,'Referrer User','referrer@example.com','referrer',NULL,'$2y$12$ipnsDamFdzQ2ScBPXJPdbOn24gRfaRL3Sac6gh3cdZyqPs7Q8/Kny',NULL,'2026-01-29 21:15:44','2026-01-29 21:15:44',NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (24,2,'Employee1','employee1@example.com','employee',NULL,'$2y$12$8.lFJSADY8Hf5gHJd1sh2u4qzqOPOlwnIbiYRibpu1NNHXIpznBhu','wjp5hDttntqEDxgWM8HyFPBuw5AZjqSuV7JUTdHVtKN6gQZNIVchfrI6ZRMf','2026-01-29 22:29:40','2026-02-03 04:24:11','profile-photos/ig77m8w0SdAzDxXcWR2cCkeiaKVJ0x53W4GnOUu3.webp',83,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (25,2,'customer1','customer1@example.com','customer',NULL,'$2y$12$Nn/YOERqJGMBSFsN89XVH.gPHs7/mbM56zvbVW8rZMWoeIILa1/Ya','CFC6cvkAMjcdH6LRS2S5v5Tev5NCZEj0BUK4shDlz5duo4IOX446Sx0eBI49','2026-01-30 00:51:20','2026-01-30 01:11:58','profile-photos/dXvnTuf4upwLpVp5hHtdf6OcDitYrx8dqh4nU26w.webp',210,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (26,2,'Employee2','employee2@example.com','employee',NULL,'$2y$12$8SC3WjaUQpnJoV8uYCOCOOyLNGMinQH0lieSE0ZCasJW8QXolq05S',NULL,'2026-01-30 01:27:46','2026-01-30 01:27:46','profile-photos/3gmXJ5PeTDKH3t0z3tkOX7QgY8hxyBuLZl90MRKw.png',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
INSERT INTO `users` VALUES (27,2,'Employee3','employee3@example.com','employee',NULL,'$2y$12$9ra4GGITPHFVSQRwWsSF2OPFqMFAc7V9gvbKuDVeJnQwKP55aJTRO',NULL,'2026-01-30 01:28:10','2026-01-30 01:28:10','profile-photos/Kihjd5LCszxN0uO16GLYyCDFDRsRPhqhN0OS1j4o.png',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-03 10:39:53
