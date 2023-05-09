-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: zentaopms_unittest
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
-- Table structure for table `zt_task`
--

DROP TABLE IF EXISTS `zt_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zt_task` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint unsigned NOT NULL DEFAULT '0',
  `parent` mediumint NOT NULL DEFAULT '0',
  `execution` mediumint unsigned NOT NULL DEFAULT '0',
  `module` mediumint unsigned NOT NULL DEFAULT '0',
  `design` mediumint unsigned NOT NULL DEFAULT '0',
  `story` mediumint unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint NOT NULL DEFAULT '1',
  `designVersion` smallint unsigned NOT NULL DEFAULT '1',
  `fromBug` mediumint unsigned NOT NULL DEFAULT '0',
  `feedback` mediumint unsigned NOT NULL DEFAULT '0',
  `fromIssue` mediumint unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `mode` varchar(10) NOT NULL DEFAULT '',
  `pri` tinyint unsigned NOT NULL DEFAULT '0',
  `estimate` float unsigned NOT NULL,
  `consumed` float unsigned NOT NULL DEFAULT '0',
  `left` float unsigned NOT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('wait','doing','done','pause','cancel','closed') NOT NULL DEFAULT 'wait',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL DEFAULT '',
  `mailto` text,
  `desc` mediumtext NOT NULL,
  `version` smallint NOT NULL DEFAULT '0',
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime DEFAULT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime DEFAULT NULL,
  `estStarted` date DEFAULT NULL,
  `realStarted` datetime DEFAULT NULL,
  `finishedBy` varchar(30) NOT NULL DEFAULT '',
  `finishedDate` datetime DEFAULT NULL,
  `finishedList` text,
  `canceledBy` varchar(30) NOT NULL DEFAULT '',
  `canceledDate` datetime DEFAULT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime DEFAULT NULL,
  `planDuration` int NOT NULL DEFAULT '0',
  `realDuration` int NOT NULL DEFAULT '0',
  `closedReason` varchar(30) NOT NULL DEFAULT '',
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime DEFAULT NULL,
  `activatedDate` datetime DEFAULT NULL,
  `order` mediumint unsigned NOT NULL DEFAULT '0',
  `repo` mediumint unsigned NOT NULL DEFAULT '0',
  `mr` mediumint unsigned NOT NULL DEFAULT '0',
  `entry` varchar(255) NOT NULL DEFAULT '',
  `lines` varchar(10) NOT NULL DEFAULT '',
  `v1` varchar(40) NOT NULL DEFAULT '',
  `v2` varchar(40) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  PRIMARY KEY (`id`),
  KEY `execution` (`execution`),
  KEY `story` (`story`),
  KEY `parent` (`parent`),
  KEY `assignedTo` (`assignedTo`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_task`
--

LOCK TABLES `zt_task` WRITE;
/*!40000 ALTER TABLE `zt_task` DISABLE KEYS */;
INSERT INTO `zt_task` VALUES (1,11,-1,101,21,0,1,1,0,0,0,0,'开发任务11','design','',1,0,3,0,'2023-05-15','wait','0','','','这里是任务描述1',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','admin','2023-05-08 19:47:48',NULL,0,0,0,'','','','','0','rnd'),(2,12,0,102,24,0,5,1,0,0,0,0,'开发任务12','devel','',2,1,4,1,'2023-05-14','doing','0','','','这里是任务描述2',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(3,13,0,103,27,0,9,1,0,0,0,0,'开发任务13','test','',3,2,5,2,'2023-05-13','done','0','','','这里是任务描述3',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(4,14,0,104,30,0,13,1,0,0,0,0,'开发任务14','study','',4,3,6,3,'2023-05-12','pause','0','','','这里是任务描述4',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(5,15,0,105,33,0,17,1,0,0,0,0,'开发任务15','discuss','',1,4,7,4,'2023-05-11','cancel','0','','','这里是任务描述5',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(6,16,0,106,36,0,21,1,0,0,0,0,'开发任务16','ui','',2,5,8,5,'2023-05-10','closed','0','','','这里是任务描述6',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(7,17,0,107,39,0,25,1,0,0,0,0,'开发任务17','affair','',3,6,9,6,'2023-05-09','wait','0','','','这里是任务描述7',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(8,18,0,108,42,0,29,1,0,0,0,0,'开发任务18','misc','',4,7,10,7,'2023-05-08','doing','0','','','这里是任务描述8',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','',NULL,'','',NULL,'',NULL,1,1,'','',NULL,NULL,0,0,0,'','','','','0','rnd'),(9,11,1,101,21,0,1,1,0,0,0,0,'开发任务11','design','',1,0,3,0,'2023-05-15','wait','0','','','这里是任务描述1',1,'admin','2023-05-08 00:00:00','','2023-05-08 00:00:00','2023-05-08','2023-05-08 00:00:00','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',1,1,'','','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,'','','','','0','rnd');
/*!40000 ALTER TABLE `zt_task` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-08 11:49:15
