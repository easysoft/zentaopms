-- MySQL dump 10.19  Distrib 10.3.38-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: zentaopms01
-- ------------------------------------------------------
-- Server version	10.3.37-MariaDB-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_project` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) NOT NULL DEFAULT 0,
  `model` char(30) NOT NULL,
  `type` char(30) NOT NULL DEFAULT 'sprint',
  `lifetime` char(30) NOT NULL DEFAULT '',
  `budget` varchar(30) NOT NULL DEFAULT '0',
  `budgetUnit` char(30) NOT NULL DEFAULT 'CNY',
  `attribute` varchar(30) NOT NULL DEFAULT '',
  `percent` float unsigned NOT NULL DEFAULT 0,
  `milestone` enum('0','1') NOT NULL DEFAULT '0',
  `output` text NOT NULL,
  `auth` char(30) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `path` varchar(255) NOT NULL,
  `grade` tinyint(3) unsigned NOT NULL,
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `hasProduct` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `realBegan` date NOT NULL,
  `realEnd` date NOT NULL,
  `days` smallint(5) unsigned NOT NULL,
  `status` varchar(10) NOT NULL,
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `pri` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `desc` mediumtext NOT NULL,
  `version` smallint(6) NOT NULL,
  `parentVersion` smallint(6) NOT NULL,
  `planDuration` int(11) NOT NULL,
  `realDuration` int(11) NOT NULL,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedVersion` varchar(20) NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `canceledBy` varchar(30) NOT NULL DEFAULT '',
  `canceledDate` datetime NOT NULL,
  `suspendedDate` date NOT NULL,
  `PO` varchar(30) NOT NULL DEFAULT '',
  `PM` varchar(30) NOT NULL DEFAULT '',
  `QD` varchar(30) NOT NULL DEFAULT '',
  `RD` varchar(30) NOT NULL DEFAULT '',
  `team` varchar(90) NOT NULL,
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `division` enum('0','1') NOT NULL DEFAULT '1',
  `displayCards` smallint(6) NOT NULL DEFAULT 0,
  `fluidBoard` enum('0','1') NOT NULL DEFAULT '0',
  `multiple` enum('0','1') NOT NULL DEFAULT '1',
  `colWidth` smallint(4) NOT NULL DEFAULT 264,
  `minColWidth` smallint(4) NOT NULL DEFAULT 200,
  `maxColWidth` smallint(4) NOT NULL DEFAULT 384,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `status` (`status`),
  KEY `acl` (`acl`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=751 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_project`
--

LOCK TABLES `zt_project` WRITE;
/*!40000 ALTER TABLE `zt_project` DISABLE KEYS */;
INSERT INTO `zt_project` VALUES (1,0,'','project','','0','CNY','',0,'0','','',0,',1,',2,'项目1','',1,'2023-01-02','2023-02-12','0000-00-00','0000-00-00',0,'doing','','1','',0,0,0,0,'','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00','','','','','','open','',0,'rnd','1',0,'0','1',264,200,384,'0'),(2,0,'','project','','0','CNY','',0,'0','','',0,',2,',2,'项目2','',1,'2023-01-02','2023-02-12','0000-00-00','0000-00-00',0,'doing','','1','',0,0,0,0,'','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00','','','','','','open','',0,'rnd','1',0,'0','1',264,200,384,'0'),(3,0,'','sprint','','0','CNY','',0,'0','','',1,',1,3,',1,'迭代1','',1,'2023-01-02','2023-02-12','0000-00-00','0000-00-00',0,'doing','','1','',0,0,0,0,'','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00','','','','','','open','',0,'rnd','1',0,'0','1',264,200,384,'0'),(4,0,'','waterfall','','0','CNY','',0,'0','','',1,',1,4,',1,'迭代2','',1,'2023-01-02','2023-02-12','0000-00-00','0000-00-00',0,'closed','','1','',0,0,0,0,'','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00','','','','','','open','',0,'rnd','1',0,'0','1',264,200,384,'0'),(5,0,'','kanban','','0','CNY','',0,'0','','',2,',2,5,',1,'迭代3','',1,'2023-01-02','2023-02-12','0000-00-00','0000-00-00',0,'doing','','1','',0,0,0,0,'','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','0000-00-00 00:00:00','0000-00-00','','','','','','open','',0,'rnd','1',0,'0','1',264,200,384,'0');
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

-- Dump completed on 2023-02-23 15:18:07
