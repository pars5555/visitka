/*
SQLyog Ultimate v11.52 (64 bit)
MySQL - 5.6.14 : Database - clinics
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `admins` */

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `admins` */

LOCK TABLES `admins` WRITE;

insert  into `admins`(`id`,`email`,`password`,`hash`) values (2,'aaa@aaa.aaa','aaaaaa',NULL);

UNLOCK TABLES;

/*Table structure for table `clinics` */

CREATE TABLE `clinics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_en` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_ru` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_am` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registered_date` datetime DEFAULT NULL,
  `subdomain` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_en` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_ru` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_am` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephones` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `work_start_time` time DEFAULT NULL,
  `work_end_time` time DEFAULT NULL,
  `enable` tinyint(4) DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain` (`subdomain`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `clinics` */

LOCK TABLES `clinics` WRITE;

insert  into `clinics`(`id`,`email`,`password`,`name_en`,`name_ru`,`name_am`,`registered_date`,`subdomain`,`address_en`,`address_ru`,`address_am`,`telephones`,`work_start_time`,`work_end_time`,`enable`,`hash`) values (1,'aaa@aaa.aaa','aaaaaa',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `cms_settings` */

CREATE TABLE `cms_settings` (
  `var` varchar(60) CHARACTER SET ascii NOT NULL,
  `value` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`var`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cms_settings` */

LOCK TABLES `cms_settings` WRITE;

UNLOCK TABLES;

/*Table structure for table `dentists` */

CREATE TABLE `dentists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_ru` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name_am` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `dentists` */

LOCK TABLES `dentists` WRITE;

insert  into `dentists`(`id`,`name_en`,`name_ru`,`name_am`,`email`,`password`,`hash`) values (2,NULL,NULL,NULL,'aaa@aaa.aaa','aaaaaa',NULL);

UNLOCK TABLES;

/*Table structure for table `languages` */

CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phrase_en` mediumtext,
  `phrase_am` mediumtext,
  `phrase_ru` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=648 DEFAULT CHARSET=utf8;

/*Data for the table `languages` */

LOCK TABLES `languages` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
