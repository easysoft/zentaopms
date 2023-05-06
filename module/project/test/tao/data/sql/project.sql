-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: zentaout
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.20.04.2

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
-- Table structure for table `zt_project`
--

DROP TABLE IF EXISTS `zt_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zt_project` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint NOT NULL DEFAULT '0',
  `model` char(30) NOT NULL DEFAULT '',
  `type` char(30) NOT NULL DEFAULT 'sprint',
  `lifetime` char(30) NOT NULL DEFAULT '',
  `budget` varchar(30) NOT NULL DEFAULT '0',
  `budgetUnit` char(30) NOT NULL DEFAULT 'CNY',
  `attribute` varchar(30) NOT NULL DEFAULT '',
  `percent` float unsigned NOT NULL DEFAULT '0',
  `milestone` enum('0','1') NOT NULL DEFAULT '0',
  `output` text,
  `auth` char(30) NOT NULL DEFAULT '',
  `parent` mediumint unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `grade` tinyint unsigned NOT NULL DEFAULT '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL DEFAULT '',
  `hasProduct` tinyint unsigned NOT NULL DEFAULT '1',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `realBegan` date DEFAULT NULL,
  `realEnd` date DEFAULT NULL,
  `days` smallint unsigned NOT NULL DEFAULT '0',
  `status` varchar(10) NOT NULL,
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `pri` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `desc` mediumtext,
  `version` smallint NOT NULL DEFAULT '0',
  `parentVersion` smallint NOT NULL DEFAULT '0',
  `planDuration` int NOT NULL DEFAULT '0',
  `realDuration` int NOT NULL DEFAULT '0',
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedVersion` varchar(20) NOT NULL DEFAULT '',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime DEFAULT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime DEFAULT NULL,
  `canceledBy` varchar(30) NOT NULL DEFAULT '',
  `canceledDate` datetime DEFAULT NULL,
  `suspendedDate` date DEFAULT NULL,
  `PO` varchar(30) NOT NULL DEFAULT '',
  `PM` varchar(30) NOT NULL DEFAULT '',
  `QD` varchar(30) NOT NULL DEFAULT '',
  `RD` varchar(30) NOT NULL DEFAULT '',
  `team` varchar(90) NOT NULL DEFAULT '',
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text,
  `order` mediumint unsigned NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `division` enum('0','1') NOT NULL DEFAULT '1',
  `displayCards` smallint NOT NULL DEFAULT '0',
  `fluidBoard` enum('0','1') NOT NULL DEFAULT '0',
  `multiple` enum('0','1') NOT NULL DEFAULT '1',
  `colWidth` smallint NOT NULL DEFAULT '264',
  `minColWidth` smallint NOT NULL DEFAULT '200',
  `maxColWidth` smallint NOT NULL DEFAULT '384',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `status` (`status`),
  KEY `acl` (`acl`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_project`
--

LOCK TABLES `zt_project` WRITE;
/*!40000 ALTER TABLE `zt_project` DISABLE KEYS */;
INSERT INTO `zt_project` VALUES (11,1,'scrum','project','','900000','CNY','',84,'0','','extend',1,',1,11,',2,'项目1','project1',1,'2023-02-06','2023-06-10',NULL,NULL,0,'doing','','1',NULL,0,0,0,0,'','2023-02-06 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','admin','','','','open',NULL,5,'rnd','1',0,'0','1',264,200,384,'0'),(12,2,'scrum','project','','899900','USD','',96,'0','','extend',2,',2,12,',2,'项目2','project2',1,'2023-02-07','2023-06-11',NULL,NULL,0,'doing','','1',NULL,0,0,0,0,'','2023-02-07 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','user1','','','','private',NULL,10,'rnd','1',0,'0','1',264,200,384,'0'),(13,3,'scrum','project','','899800','CNY','',92,'0','','extend',3,',3,13,',2,'项目3','project3',1,'2023-02-08','2023-06-12',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-08 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','admin','','','','program',NULL,15,'rnd','1',0,'0','1',264,200,384,'0'),(14,4,'scrum','project','','899700','USD','',38,'0','','extend',4,',4,14,',2,'项目4','project4',1,'2023-02-09','2023-06-13',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-09 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','user1','','','','open',NULL,20,'rnd','1',0,'0','1',264,200,384,'0'),(15,5,'scrum','project','','899600','CNY','',15,'0','','extend',5,',5,15,',2,'项目5','project5',1,'2023-02-10','2023-06-14',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-10 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','admin','','','','private',NULL,25,'rnd','1',0,'0','1',264,200,384,'0'),(16,6,'scrum','sprint','','899500','USD','',94,'0','','extend',6,',6,16,',2,'项目6','project6',1,'2023-02-11','2023-06-15',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-11 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','user1','','','','program',NULL,30,'rnd','1',0,'0','1',264,200,384,'0'),(17,7,'scrum','project','','899400','CNY','',27,'0','','extend',7,',7,17,',2,'项目7','project7',1,'2023-02-12','2023-06-16',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-12 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','admin','','','','open',NULL,35,'rnd','1',0,'0','1',264,200,384,'0'),(18,8,'scrum','project','','899300','USD','',93,'0','','extend',8,',8,18,',2,'项目8','project8',1,'2023-02-13','2023-06-17',NULL,NULL,0,'closed','','1',NULL,0,0,0,0,'','2023-02-13 00:00:00','16.5','',NULL,'',NULL,'',NULL,NULL,'','user1','','','','private',NULL,40,'rnd','1',0,'0','1',264,200,384,'0');
/*!40000 ALTER TABLE `zt_project` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-06 16:46:45
