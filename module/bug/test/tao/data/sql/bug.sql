-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: zentaopms184
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.22.04.2

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
-- Table structure for table `zt_bug`
--

DROP TABLE IF EXISTS `zt_bug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zt_bug` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `project` mediumint unsigned NOT NULL DEFAULT '0',
  `product` mediumint unsigned NOT NULL DEFAULT '0',
  `injection` mediumint unsigned NOT NULL DEFAULT '0',
  `identify` mediumint unsigned NOT NULL DEFAULT '0',
  `branch` mediumint unsigned NOT NULL DEFAULT '0',
  `module` mediumint unsigned NOT NULL DEFAULT '0',
  `execution` mediumint unsigned NOT NULL DEFAULT '0',
  `plan` mediumint unsigned NOT NULL DEFAULT '0',
  `story` mediumint unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint NOT NULL DEFAULT '1',
  `task` mediumint unsigned NOT NULL DEFAULT '0',
  `toTask` mediumint unsigned NOT NULL DEFAULT '0',
  `toStory` mediumint NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `severity` tinyint NOT NULL DEFAULT '0',
  `pri` tinyint unsigned NOT NULL DEFAULT '0',
  `type` varchar(30) NOT NULL DEFAULT '',
  `os` varchar(255) NOT NULL DEFAULT '',
  `browser` varchar(255) NOT NULL DEFAULT '',
  `hardware` varchar(30) NOT NULL DEFAULT '',
  `found` varchar(30) NOT NULL DEFAULT '',
  `steps` mediumtext,
  `status` enum('active','resolved','closed') NOT NULL DEFAULT 'active',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL DEFAULT '',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `activatedCount` smallint NOT NULL DEFAULT '0',
  `activatedDate` datetime DEFAULT NULL,
  `feedbackBy` varchar(100) NOT NULL DEFAULT '',
  `notifyEmail` varchar(100) NOT NULL DEFAULT '',
  `mailto` text,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime DEFAULT NULL,
  `openedBuild` varchar(255) NOT NULL DEFAULT '',
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `resolvedBy` varchar(30) NOT NULL DEFAULT '',
  `resolution` varchar(30) NOT NULL DEFAULT '',
  `resolvedBuild` varchar(30) NOT NULL DEFAULT '',
  `resolvedDate` datetime DEFAULT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime DEFAULT NULL,
  `duplicateBug` mediumint unsigned NOT NULL DEFAULT '0',
  `linkBug` varchar(255) NOT NULL DEFAULT '',
  `case` mediumint unsigned NOT NULL DEFAULT '0',
  `caseVersion` smallint NOT NULL DEFAULT '1',
  `feedback` mediumint unsigned NOT NULL DEFAULT '0',
  `result` mediumint unsigned NOT NULL DEFAULT '0',
  `repo` mediumint unsigned NOT NULL DEFAULT '0',
  `mr` mediumint unsigned NOT NULL DEFAULT '0',
  `entry` text,
  `lines` varchar(10) NOT NULL DEFAULT '',
  `v1` varchar(40) NOT NULL DEFAULT '',
  `v2` varchar(40) NOT NULL DEFAULT '',
  `repoType` varchar(30) NOT NULL DEFAULT '',
  `issueKey` varchar(50) NOT NULL DEFAULT '',
  `testtask` mediumint unsigned NOT NULL DEFAULT '0',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `execution` (`execution`),
  KEY `status` (`status`),
  KEY `plan` (`plan`),
  KEY `story` (`story`),
  KEY `case` (`case`),
  KEY `toStory` (`toStory`),
  KEY `result` (`result`),
  KEY `assignedTo` (`assignedTo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_bug`
--

LOCK TABLES `zt_bug` WRITE;
/*!40000 ALTER TABLE `zt_bug` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_bug` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-08 10:24:51
