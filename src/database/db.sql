/*
SQLyog Ultimate v13.2.1 (64 bit)
MySQL - 5.7.40 : Database - cfoam
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cfoam` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cfoam`;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`,`is_active`,`created_by`,`created_at`,`updated_by`,`updated_at`) values 
(1,'Seeds',1,1,'2024-06-01 19:24:53',NULL,NULL),
(2,'Pesticides',1,1,'2024-06-01 19:25:05',NULL,NULL),
(3,'Insecticides',1,1,'2024-06-01 19:25:05',NULL,NULL),
(4,'Herbecides',1,1,'2024-06-01 19:25:05',NULL,NULL),
(5,'Fungicides',1,1,'2024-06-01 19:25:05',NULL,NULL),
(6,'Equipments',1,1,'2024-06-01 19:25:05',NULL,NULL),
(7,'Fertilizers',1,1,'2024-06-01 19:25:05',NULL,NULL);

/*Table structure for table `inventory` */

DROP TABLE IF EXISTS `inventory`;

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `inventory` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `expires_at` datetime DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `pricing_history` */

DROP TABLE IF EXISTS `pricing_history`;

CREATE TABLE `pricing_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `valid_from` datetime DEFAULT NULL,
  `valid_to` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pricing_history` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `size` int(11) DEFAULT NULL,
  `volume` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `category_id` int(10) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`name`,`size`,`volume`,`description`,`category_id`,`is_active`,`created_by`,`created_at`,`updated_by`,`updated_at`) values 
(1,'Solomon',NULL,NULL,NULL,3,1,1,'2024-06-01 20:42:21',NULL,NULL),
(2,'Almix',NULL,NULL,NULL,4,1,1,'2024-06-01 20:42:21',NULL,NULL),
(3,'2,4-D Amine',NULL,NULL,NULL,4,1,1,'2024-06-01 22:12:16',NULL,NULL),
(4,'Baylucide',NULL,NULL,NULL,2,1,1,'2024-06-01 22:12:16',NULL,NULL),
(5,'Machete',1,'LTR',NULL,4,1,1,'2024-06-01 22:12:16',NULL,NULL),
(6,'Machete',500,'ML',NULL,4,1,1,'2024-06-02 08:38:01',NULL,NULL),
(7,'Selecron',NULL,NULL,NULL,3,1,1,'2024-06-06 22:21:59',NULL,NULL),
(8,'Prevathon',NULL,NULL,NULL,3,1,1,'2024-06-06 22:22:04',NULL,NULL),
(9,'Glyphotex',NULL,NULL,NULL,4,1,1,'2024-06-06 22:22:07',NULL,NULL),
(10,'Plastic Munch',NULL,NULL,NULL,6,1,1,'2024-06-06 22:22:11',NULL,NULL),
(11,'H 14-14-14',1,'CVN',NULL,7,1,1,'2024-06-06 22:22:23',NULL,NULL),
(12,'Crusher',1,'LTR',NULL,3,1,1,'2024-06-06 22:23:44',NULL,NULL),
(13,'2,4-D Ester',NULL,NULL,NULL,4,1,1,'2024-06-06 22:23:51',NULL,NULL),
(14,'Onecide',1,'LTR',NULL,4,1,1,'2024-06-06 22:23:55',NULL,NULL),
(15,'Agrowell',NULL,NULL,NULL,7,1,1,'2024-06-06 22:23:59',NULL,NULL),
(16,'Nominee',1,'BOX',NULL,4,1,1,'2024-06-06 22:24:00',NULL,NULL),
(17,'Brodan',1,'LTR',NULL,3,1,1,'2024-06-06 22:24:05',NULL,NULL),
(18,'Exacto',NULL,NULL,NULL,3,1,1,'2024-06-06 22:24:15',NULL,NULL),
(19,'Basta',NULL,NULL,NULL,4,1,1,'2024-06-06 22:24:17',NULL,NULL),
(20,'Furadan',NULL,NULL,NULL,3,1,1,'2024-06-06 22:25:01',NULL,NULL);

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference_id` varchar(30) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `sales` */

insert  into `sales`(`id`,`reference_id`,`product_id`,`quantity`,`sale_price`,`sale_date`,`customer_id`,`created_by`,`created_at`,`updated_by`,`updated_at`) values 
(1,'SI-199211',1,1,NULL,'2020-01-02',1,1,'2024-06-06 21:37:33',NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
