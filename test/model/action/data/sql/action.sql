-- MySQL dump 10.19  Distrib 10.3.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: zentao1243
-- ------------------------------------------------------
-- Server version	5.7.36

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
-- Table structure for table `zt_action`
--

DROP TABLE IF EXISTS `zt_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_action` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` text NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `execution` mediumint(8) unsigned NOT NULL,
  `actor` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(80) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  `extra` text,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `efforted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `project` (`execution`),
  KEY `objectID` (`objectID`),
  KEY `action` (`action`)
) ENGINE=MyISAM AUTO_INCREMENT=420 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_action`
--

LOCK TABLES `zt_action` WRITE;
/*!40000 ALTER TABLE `zt_action` DISABLE KEYS */;
INSERT INTO `zt_action` VALUES (101,'product',0,'',0,0,'','common','0000-00-00 00:00:00','这是一个系统日志测试备注1',NULL,'0','rnd',0),(102,'story',0,'',0,0,'','extra','0000-00-00 00:00:00','这是一个系统日志测试备注2',NULL,'0','rnd',0),(103,'productplan',0,'',0,0,'','opened','0000-00-00 00:00:00','这是一个系统日志测试备注3',NULL,'0','rnd',0),(104,'release',0,'',0,0,'','created','0000-00-00 00:00:00','这是一个系统日志测试备注4',NULL,'0','rnd',0),(105,'project',0,'',0,0,'','changed','0000-00-00 00:00:00','这是一个系统日志测试备注5',NULL,'0','rnd',0);
/*!40000 ALTER TABLE `zt_action` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-17  8:52:10
