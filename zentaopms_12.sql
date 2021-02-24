-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: zentao_pms
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` varchar(255) NOT NULL,
  `project` mediumint(9) NOT NULL,
  `actor` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(30) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  `extra` text NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `project` (`project`),
  KEY `objectID` (`objectID`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_action`
--

LOCK TABLES `zt_action` WRITE;
/*!40000 ALTER TABLE `zt_action` DISABLE KEYS */;
INSERT INTO `zt_action` VALUES (1,'user',20,',0,',0,'azhi','logout','2012-06-05 09:24:52','','','0'),(2,'user',1,',0,',0,'admin','login','2012-06-05 09:25:00','','','0'),(3,'user',1,',0,',0,'admin','logout','2012-06-05 09:51:10','','','0'),(4,'user',2,',0,',0,'productManager','login','2012-06-05 09:51:33','','','0'),(5,'user',2,',0,',0,'productManager','logout','2012-06-05 09:53:05','','','0'),(6,'user',1,',0,',0,'admin','login','2012-06-05 09:53:10','','','0'),(7,'user',1,',0,',0,'admin','logout','2012-06-05 09:53:52','','','0'),(8,'user',2,',0,',0,'productManager','login','2012-06-05 09:54:07','','','0'),(9,'product',1,',1,',0,'productManager','opened','2012-06-05 09:57:07','','','0'),(10,'productplan',1,',1,',0,'productManager','opened','2012-06-05 10:02:49','','','0'),(11,'story',1,',1,',0,'productManager','opened','2012-06-05 10:09:49','','','0'),(12,'story',2,',1,',0,'productManager','opened','2012-06-05 10:16:37','','','0'),(13,'story',3,',1,',0,'productManager','opened','2012-06-05 10:18:10','','','0'),(14,'story',3,',1,',0,'productManager','changed','2012-06-05 10:19:06','','','0'),(15,'story',4,',1,',0,'productManager','opened','2012-06-05 10:20:16','','','0'),(16,'story',5,',1,',0,'productManager','opened','2012-06-05 10:21:39','','','0'),(17,'story',6,',1,',0,'productManager','opened','2012-06-05 10:23:11','','','0'),(18,'story',7,',1,',0,'productManager','opened','2012-06-05 10:24:19','','','0'),(19,'story',1,',1,',0,'productManager','reviewed','2012-06-05 10:25:19','','Pass','0'),(20,'story',2,',1,',0,'productManager','reviewed','2012-06-05 10:25:33','','Pass','0'),(21,'story',3,',1,',0,'productManager','reviewed','2012-06-05 10:25:38','','Pass','0'),(22,'story',4,',1,',0,'productManager','reviewed','2012-06-05 10:25:42','','Pass','0'),(23,'user',2,',0,',0,'productManager','logout','2012-06-05 10:26:20','','','0'),(24,'user',3,',0,',0,'projectManager','login','2012-06-05 10:26:38','','','0'),(25,'project',1,',1,',1,'projectManager','opened','2012-06-05 10:28:36','','','0'),(26,'story',4,',1,',1,'projectManager','linked2project','2012-06-05 10:31:02','','1','0'),(27,'story',3,',1,',1,'projectManager','linked2project','2012-06-05 10:31:02','','1','0'),(28,'story',2,',1,',1,'projectManager','linked2project','2012-06-05 10:31:02','','1','0'),(29,'story',1,',1,',1,'projectManager','linked2project','2012-06-05 10:31:02','','1','0'),(30,'task',1,',1,',1,'projectManager','opened','2012-06-05 10:32:03','','','0'),(31,'task',2,',1,',1,'projectManager','opened','2012-06-05 10:32:23','','','0'),(32,'task',3,',1,',1,'projectManager','opened','2012-06-05 10:33:01','','','0'),(33,'task',4,',1,',1,'projectManager','opened','2012-06-05 10:33:21','','','0'),(34,'task',5,',1,',1,'projectManager','opened','2012-06-05 10:33:44','','','0'),(35,'task',6,',1,',1,'projectManager','opened','2012-06-05 10:33:59','','','0'),(36,'task',7,',1,',1,'projectManager','opened','2012-06-05 10:34:25','','','0'),(37,'task',8,',1,',1,'projectManager','opened','2012-06-05 10:34:45','','','0'),(38,'task',9,',1,',1,'projectManager','opened','2012-06-05 10:35:01','','','0'),(39,'user',3,',0,',0,'projectManager','logout','2012-06-05 10:37:30','','','0'),(40,'user',4,',0,',0,'dev1','login','2012-06-05 10:37:40','','','0'),(41,'task',1,',1,',1,'dev1','started','2012-06-05 10:37:54','','','0'),(42,'task',1,',1,',1,'dev1','finished','2012-06-05 10:38:00','','','0'),(43,'task',8,',1,',1,'dev1','finished','2012-06-05 10:39:14','','','0'),(44,'task',9,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(45,'task',8,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(46,'task',7,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(47,'task',6,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(48,'task',5,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(49,'task',4,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(50,'task',3,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(51,'task',2,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(52,'task',1,',1,',1,'dev1','edited','2012-06-05 10:41:20','','','0'),(53,'user',4,',0,',0,'dev1','logout','2012-06-05 10:41:50','','','0'),(54,'user',5,',0,',0,'dev2','login','2012-06-05 10:41:56','','','0'),(55,'task',4,',1,',1,'dev2','edited','2012-06-05 10:42:56','','','0'),(56,'task',3,',1,',1,'dev2','edited','2012-06-05 10:42:57','','','0'),(57,'user',5,',0,',0,'dev2','logout','2012-06-05 10:43:02','','','0'),(58,'user',6,',0,',0,'dev3','login','2012-06-05 10:43:07','','','0'),(59,'task',6,',1,',1,'dev3','edited','2012-06-05 10:43:32','','','0'),(60,'task',5,',1,',1,'dev3','edited','2012-06-05 10:43:32','','','0'),(61,'user',6,',0,',0,'dev3','logout','2012-06-05 10:43:42','','','0'),(62,'user',3,',0,',0,'projectManager','login','2012-06-05 10:44:05','','','0'),(63,'user',3,',0,',0,'projectManager','logout','2012-06-05 10:50:03','','','0'),(64,'user',10,',0,',0,'testManager','login','2012-06-05 10:50:09','','','0'),(65,'user',10,',0,',0,'testManager','logout','2012-06-05 10:50:14','','','0'),(66,'user',10,',0,',0,'testManager','login','2012-06-05 10:50:23','','','0'),(67,'user',10,',0,',0,'testManager','logout','2012-06-05 10:50:32','','','0'),(68,'user',1,',0,',0,'admin','login','2012-06-05 10:50:36','','','0'),(69,'user',1,',0,',0,'admin','logout','2012-06-05 10:50:53','','','0'),(70,'user',10,',0,',0,'testManager','login','2012-06-05 10:51:01','','','0'),(71,'user',10,',0,',0,'testManager','logout','2012-06-05 10:51:33','','','0'),(72,'user',7,',0,',0,'tester1','login','2012-06-05 10:51:38','','','0'),(73,'bug',1,',1,',1,'tester1','opened','2012-06-05 10:56:11','','','0'),(74,'bug',2,',1,',1,'tester1','opened','2012-06-05 10:57:11','','','0'),(75,'user',7,',0,',0,'tester1','logout','2012-06-05 10:57:13','','','0'),(76,'user',8,',0,',0,'tester2','login','2012-06-05 10:57:24','','','0'),(77,'bug',3,',1,',1,'tester2','opened','2012-06-05 10:58:22','','','0'),(78,'user',8,',0,',0,'tester2','logout','2012-06-05 10:58:39','','','0'),(79,'user',9,',0,',0,'tester3','login','2012-06-05 10:58:46','','','0'),(80,'bug',4,',1,',1,'tester3','opened','2012-06-05 11:00:19','','','0'),(81,'case',1,',1,',0,'tester3','opened','2012-06-05 11:02:18','','','0'),(82,'case',1,',1,',0,'tester3','edited','2012-06-05 11:02:47','','','0'),(83,'user',9,',0,',0,'tester3','logout','2012-06-05 11:02:48','','','0'),(84,'user',7,',0,',0,'tester1','login','2012-06-05 11:02:56','','','0'),(85,'case',2,',1,',0,'tester1','opened','2012-06-05 11:03:28','','','0'),(86,'case',3,',1,',0,'tester1','opened','2012-06-05 11:03:54','','','0'),(87,'user',7,',0,',0,'tester1','logout','2012-06-05 11:04:00','','','0'),(88,'user',8,',0,',0,'tester2','login','2012-06-05 11:04:10','','','0'),(89,'case',4,',1,',0,'tester2','opened','2012-06-05 11:04:48','','','0'),(90,'user',8,',0,',0,'tester2','logout','2012-06-05 11:04:52','','','0'),(91,'user',10,',0,',0,'testManager','login','2012-06-05 11:04:58','','','0'),(92,'testtask',1,',1,',1,'testManager','opened','2012-06-05 11:07:41','','','0'),(93,'testtask',1,',1,',1,'testManager','edited','2012-06-05 11:07:52','','','0'),(94,'user',10,',0,',0,'testManager','logout','2012-06-05 11:08:10','','','0'),(95,'user',1,',0,',0,'admin','login','2012-06-05 11:08:15','','','0'),(96,'user',1,',0,',0,'admin','logout','2012-06-05 11:08:23','','','0'),(97,'user',10,',0,',0,'testManager','login','2012-06-05 11:08:35','','','0'),(98,'user',10,',0,',0,'testManager','logout','2012-06-05 11:08:55','','','0'),(99,'user',7,',0,',0,'tester1','login','2012-06-05 11:08:59','','','0'),(100,'user',7,',0,',0,'tester1','logout','2012-06-05 11:09:52','','','0'),(101,'user',1,',0,',0,'admin','login','2012-06-05 11:09:54','','','0'),(102,'user',1,',0,',0,'admin','logout','2012-06-05 11:10:38','','','0'),(103,'user',10,',0,',0,'testManager','login','2012-06-05 11:10:42','','','0'),(104,'user',10,',0,',0,'testManager','logout','2012-06-05 11:11:13','','','0'),(105,'user',3,',0,',0,'projectManager','login','2012-06-05 11:11:16','','','0'),(106,'build',1,',1,',1,'projectManager','opened','2012-06-05 11:11:45','','','0'),(107,'project',2,',1,',2,'projectManager','opened','2012-06-05 11:12:28','','','0'),(108,'user',3,',0,',0,'projectManager','logout','2012-06-05 11:14:40','','','0'),(109,'user',2,',0,',0,'productManager','login','2012-06-05 11:14:43','','','0'),(110,'product',2,',2,',0,'productManager','opened','2012-06-05 11:15:20','','','0'),(111,'user',1,',0,',0,'admin','login','2020-11-20 13:36:49','','','0');
/*!40000 ALTER TABLE `zt_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_block`
--

DROP TABLE IF EXISTS `zt_block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_block` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `module` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `source` varchar(20) NOT NULL,
  `block` varchar(20) NOT NULL,
  `params` text NOT NULL,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `height` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `accountModuleOrder` (`account`,`module`,`order`),
  KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_block`
--

LOCK TABLES `zt_block` WRITE;
/*!40000 ALTER TABLE `zt_block` DISABLE KEYS */;
INSERT INTO `zt_block` VALUES (1,'admin','my','欢迎','','welcome','',1,8,0,0),(2,'admin','my','最新动态','','dynamic','',2,4,0,0),(3,'admin','my','流程图','','flowchart','',3,8,0,0),(4,'admin','my','我的待办','todo','list','{\"num\":\"20\"}',4,4,0,0),(5,'admin','my','项目统计','project','statistic','{\"type\":\"all\",\"num\":\"20\"}',5,8,0,0),(6,'admin','my','项目总览','project','overview','',6,4,0,0),(7,'admin','my','产品统计','product','statistic','{\"type\":\"all\",\"num\":\"20\"}',7,8,0,0),(8,'admin','my','产品总览','product','overview','',8,4,0,0),(9,'admin','my','指派给我的Bug','qa','bug','{\"num\":15,\"orderBy\":\"id_desc\",\"type\":\"assignedTo\"}',9,4,0,0);
/*!40000 ALTER TABLE `zt_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_branch`
--

DROP TABLE IF EXISTS `zt_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_branch` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` smallint(5) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_branch`
--

LOCK TABLES `zt_branch` WRITE;
/*!40000 ALTER TABLE `zt_branch` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_budget`
--

DROP TABLE IF EXISTS `zt_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_budget` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) NOT NULL,
  `stage` char(30) NOT NULL,
  `subject` mediumint(8) NOT NULL,
  `amount` char(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` date NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_budget`
--

LOCK TABLES `zt_budget` WRITE;
/*!40000 ALTER TABLE `zt_budget` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_budget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_bug`
--

DROP TABLE IF EXISTS `zt_bug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_bug` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plan` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `toTask` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `toStory` mediumint(8) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `severity` tinyint(4) NOT NULL DEFAULT '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `os` varchar(30) NOT NULL DEFAULT '',
  `browser` varchar(30) NOT NULL DEFAULT '',
  `hardware` varchar(30) NOT NULL,
  `found` varchar(30) NOT NULL DEFAULT '',
  `steps` text NOT NULL,
  `status` enum('active','resolved','closed') NOT NULL DEFAULT 'active',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `activatedCount` smallint(6) NOT NULL,
  `activatedDate` datetime NOT NULL,
  `mailto` text,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedBuild` varchar(255) NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `deadline` date NOT NULL,
  `resolvedBy` varchar(30) NOT NULL DEFAULT '',
  `resolution` varchar(30) NOT NULL DEFAULT '',
  `resolvedBuild` varchar(30) NOT NULL DEFAULT '',
  `resolvedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `duplicateBug` mediumint(8) unsigned NOT NULL,
  `linkBug` varchar(255) NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `caseVersion` smallint(6) NOT NULL DEFAULT '1',
  `result` mediumint(8) unsigned NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `entry` varchar(255) NOT NULL,
  `lines` varchar(10) NOT NULL,
  `v1` varchar(40) NOT NULL,
  `v2` varchar(40) NOT NULL,
  `repoType` varchar(30) NOT NULL DEFAULT '',
  `testtask` mediumint(8) unsigned NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `project` (`project`),
  KEY `status` (`status`),
  KEY `plan` (`plan`),
  KEY `story` (`story`),
  KEY `case` (`case`),
  KEY `assignedTo` (`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_bug`
--

LOCK TABLES `zt_bug` WRITE;
/*!40000 ALTER TABLE `zt_bug` DISABLE KEYS */;
INSERT INTO `zt_bug` VALUES (1,1,0,8,1,0,1,1,1,0,0,'首页页面问题','',3,0,'interface','','','','','<p>[步骤]进入首页</p>\r\n<p>[结果]出现乱码&nbsp;&nbsp;&nbsp;&nbsp;</p>\r\n<p>[期望]正常显示</p>','active','','',0,0,'0000-00-00 00:00:00','','tester1','2012-06-05 10:56:11','trunk','dev1','2012-06-05 10:56:11','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',0,'',0,1,0,0,'','','','','',0,'','0000-00-00 00:00:00','0'),(2,1,0,9,1,0,2,1,4,0,0,'新闻中心页面问题','',3,0,'codeerror','','','','','<p>[步骤]进入新闻中心</p>\r\n<p>[结果]页面出现乱码</p>\r\n<p>[期望]正常显示</p>','active','','',0,0,'0000-00-00 00:00:00','','tester1','2012-06-05 10:57:11','trunk','dev2','2012-06-05 10:57:11','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',0,'',0,1,0,0,'','','','','',0,'','0000-00-00 00:00:00','0'),(3,1,0,10,1,0,3,2,6,0,0,'成果展示页面问题','',3,0,'codeerror','','','','','<p>[步骤]进入成果展示&nbsp;&nbsp;&nbsp;&nbsp;</p>\r\n<p>[结果]乱码</p>\r\n<p>[期望]正常显示</p>','active','','',0,0,'0000-00-00 00:00:00','','tester2','2012-06-05 10:58:22','trunk','dev3','2012-06-05 10:58:22','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',0,'',0,1,0,0,'','','','','',0,'','0000-00-00 00:00:00','0'),(4,1,0,11,1,0,4,1,9,0,0,'售后服务页面问题','',3,0,'codeerror','','','','','<p>[步骤]进入售后服务</p>\r\n<p>[结果]乱码</p>\r\n<p>[期望]正常显示</p>','active','','',0,0,'0000-00-00 00:00:00','','tester3','2012-06-05 11:00:19','trunk','dev1','2012-06-05 11:00:19','0000-00-00','','','','0000-00-00 00:00:00','','0000-00-00 00:00:00',0,'',0,1,0,0,'','','','','',0,'','0000-00-00 00:00:00','0');
/*!40000 ALTER TABLE `zt_bug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_build`
--

DROP TABLE IF EXISTS `zt_build`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_build` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(150) NOT NULL,
  `scmPath` char(255) NOT NULL,
  `filePath` char(255) NOT NULL,
  `date` date NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `builder` char(30) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `project` (`project`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_build`
--

LOCK TABLES `zt_build` WRITE;
/*!40000 ALTER TABLE `zt_build` DISABLE KEYS */;
INSERT INTO `zt_build` VALUES (1,1,0,1,'第一期版本','','','2012-06-05','3,2,1,4','','projectManager','','0');
/*!40000 ALTER TABLE `zt_build` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_burn`
--

DROP TABLE IF EXISTS `zt_burn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_burn` (
  `project` mediumint(8) unsigned NOT NULL,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `estimate` float NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY (`project`,`date`,`task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_burn`
--

LOCK TABLES `zt_burn` WRITE;
/*!40000 ALTER TABLE `zt_burn` DISABLE KEYS */;
INSERT INTO `zt_burn` VALUES (1,0,'2012-06-05',0,0,38);
/*!40000 ALTER TABLE `zt_burn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_case`
--

DROP TABLE IF EXISTS `zt_case`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_case` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lib` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(30) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `precondition` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `type` char(30) NOT NULL DEFAULT '1',
  `auto` varchar(10) NOT NULL DEFAULT 'no',
  `frame` varchar(10) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `howRun` varchar(30) NOT NULL,
  `scriptedBy` varchar(30) NOT NULL,
  `scriptedDate` date NOT NULL,
  `scriptStatus` varchar(30) NOT NULL,
  `scriptLocation` varchar(255) NOT NULL,
  `status` char(30) NOT NULL DEFAULT '1',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL,
  `frequency` enum('1','2','3') NOT NULL DEFAULT '1',
  `order` tinyint(30) unsigned NOT NULL DEFAULT '0',
  `openedBy` char(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `reviewedBy` varchar(255) NOT NULL,
  `reviewedDate` date NOT NULL,
  `lastEditedBy` char(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `version` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `linkCase` varchar(255) NOT NULL,
  `fromBug` mediumint(8) unsigned NOT NULL,
  `fromCaseID` mediumint(8) unsigned NOT NULL,
  `fromCaseVersion` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `lastRunner` varchar(30) NOT NULL,
  `lastRunDate` datetime NOT NULL,
  `lastRunResult` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `story` (`story`),
  KEY `module` (`module`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_case`
--

LOCK TABLES `zt_case` WRITE;
/*!40000 ALTER TABLE `zt_case` DISABLE KEYS */;
INSERT INTO `zt_case` VALUES (1,1,0,0,0,0,4,1,'售后服务的测试用例','','',1,'feature','no','','feature','','','0000-00-00','','','normal','','','1',0,'tester3','2012-06-05 11:02:18','','0000-00-00','tester3','2012-06-05 11:02:46',1,'',0,0,1,'0','testManager','2012-06-05 11:11:00','pass'),(2,1,0,0,0,0,1,1,'首页的测试用例','','',3,'feature','no','','','','','0000-00-00','','','normal','','','1',0,'tester1','2012-06-05 11:03:28','','0000-00-00','','0000-00-00 00:00:00',1,'',0,0,1,'0','testManager','2012-06-05 11:11:05','pass'),(3,1,0,0,0,0,2,1,'新闻中心的测试用例','','',3,'feature','no','','feature','','','0000-00-00','','','normal','','','1',0,'tester1','2012-06-05 11:03:54','','0000-00-00','','0000-00-00 00:00:00',1,'',0,0,1,'0','testManager','2012-06-05 11:11:07','pass'),(4,1,0,0,0,0,3,2,'成果展示测试用例','','',3,'feature','no','','feature','','','0000-00-00','','','normal','','','1',0,'tester2','2012-06-05 11:04:48','','0000-00-00','','0000-00-00 00:00:00',1,'',0,0,1,'0','testManager','2012-06-05 11:11:08','pass');
/*!40000 ALTER TABLE `zt_case` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_casestep`
--

DROP TABLE IF EXISTS `zt_casestep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_casestep` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` smallint(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL DEFAULT 'step',
  `desc` text NOT NULL,
  `expect` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `case` (`case`),
  KEY `version` (`version`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_casestep`
--

LOCK TABLES `zt_casestep` WRITE;
/*!40000 ALTER TABLE `zt_casestep` DISABLE KEYS */;
INSERT INTO `zt_casestep` VALUES (1,0,1,1,'step','进入首页','正常显示'),(2,0,2,1,'step','进入首页','正常显示'),(3,0,3,1,'step','进入新闻中心','正常显示'),(4,0,4,1,'step','进入成果展示','正常显示');
/*!40000 ALTER TABLE `zt_casestep` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_company`
--

DROP TABLE IF EXISTS `zt_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_company` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(120) DEFAULT NULL,
  `phone` char(20) DEFAULT NULL,
  `fax` char(20) DEFAULT NULL,
  `address` char(120) DEFAULT NULL,
  `zipcode` char(10) DEFAULT NULL,
  `website` char(120) DEFAULT NULL,
  `backyard` char(120) DEFAULT NULL,
  `guest` enum('1','0') NOT NULL DEFAULT '0',
  `admins` char(255) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_company`
--

LOCK TABLES `zt_company` WRITE;
/*!40000 ALTER TABLE `zt_company` DISABLE KEYS */;
INSERT INTO `zt_company` VALUES (1,'开源版_TEST',NULL,NULL,NULL,NULL,NULL,NULL,'0',',admin,','0');
/*!40000 ALTER TABLE `zt_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_compile`
--

DROP TABLE IF EXISTS `zt_compile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_compile` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `job` mediumint(8) unsigned NOT NULL,
  `queue` mediumint(8) NOT NULL,
  `status` varchar(255) NOT NULL,
  `logs` text,
  `atTime` varchar(10) NOT NULL,
  `testtask` mediumint(8) unsigned NOT NULL,
  `tag` varchar(255) NOT NULL,
  `times` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_compile`
--

LOCK TABLES `zt_compile` WRITE;
/*!40000 ALTER TABLE `zt_compile` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_compile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_config`
--

DROP TABLE IF EXISTS `zt_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_config` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `owner` char(30) NOT NULL DEFAULT '',
  `module` varchar(30) NOT NULL,
  `section` char(30) NOT NULL DEFAULT '',
  `key` char(30) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`owner`,`module`,`section`,`key`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_config`
--

LOCK TABLES `zt_config` WRITE;
/*!40000 ALTER TABLE `zt_config` DISABLE KEYS */;
INSERT INTO `zt_config` VALUES (1,'system','common','global','showDemoUsers','1'),(2,'system','common','global','version','12.4.4'),(3,'system','common','global','flow','full'),(4,'system','common','safe','mode','1'),(5,'system','common','safe','changeWeak','1'),(6,'system','common','global','cron','1'),(7,'system','common','xuanxuan','turnon','1'),(8,'system','common','xuanxuan','key','49ba2c5e6100ea337f61287d67f09922'),(9,'system','common','xuanxuan','chatPort','11444'),(10,'system','common','xuanxuan','commonPort','11443'),(11,'system','common','xuanxuan','ip','0.0.0.0'),(12,'system','common','xuanxuan','uploadFileSize','20'),(13,'system','common','xuanxuan','https','off'),(14,'system','custom','','productProject','0_0'),(15,'system','custom','','storyRequirement','0'),(16,'system','custom','','hourPoint','0'),(17,'system','common','','conceptSetted','1'),(18,'admin','my','common','blockInited','1'),(19,'admin','my','block','initVersion','2'),(20,'system','cron','run','status','running');
/*!40000 ALTER TABLE `zt_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_cron`
--

DROP TABLE IF EXISTS `zt_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_cron` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `m` varchar(20) NOT NULL,
  `h` varchar(20) NOT NULL,
  `dom` varchar(20) NOT NULL,
  `mon` varchar(20) NOT NULL,
  `dow` varchar(20) NOT NULL,
  `command` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `buildin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  `lastTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lastTime` (`lastTime`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_cron`
--

LOCK TABLES `zt_cron` WRITE;
/*!40000 ALTER TABLE `zt_cron` DISABLE KEYS */;
INSERT INTO `zt_cron` VALUES (1,'*','*','*','*','*','','监控定时任务','zentao',1,'normal','2020-11-20 13:49:53'),(2,'30','23','*','*','*','moduleName=project&methodName=computeburn','更新燃尽图','zentao',1,'normal','2020-11-20 13:36:53'),(3,'0','8','*','*','*','moduleName=report&methodName=remind','每日任务提醒','zentao',1,'normal','2020-11-20 13:36:53'),(4,'*/5','*','*','*','*','moduleName=svn&methodName=run','同步SVN','zentao',1,'stop','0000-00-00 00:00:00'),(5,'*/5','*','*','*','*','moduleName=git&methodName=run','同步GIT','zentao',1,'stop','0000-00-00 00:00:00'),(6,'30','0','*','*','*','moduleName=backup&methodName=backup','备份数据和附件','zentao',1,'normal','2020-11-20 13:36:53'),(7,'*/5','*','*','*','*','moduleName=mail&methodName=asyncSend','异步发信','zentao',1,'normal','2020-11-20 13:45:53'),(8,'*/5','*','*','*','*','moduleName=webhook&methodName=asyncSend','异步发送Webhook','zentao',1,'normal','2020-11-20 13:45:53'),(9,'*/5','*','*','*','*','moduleName=admin&methodName=deleteLog','删除过期日志','zentao',1,'normal','2020-11-20 13:45:53'),(10,'1','1','*','*','*','moduleName=todo&methodName=createCycle','生成周期性待办','zentao',1,'normal','2020-11-20 13:36:53'),(11,'1','0','*','*','*','moduleName=ci&methodName=initQueue','创建周期性任务','zentao',1,'normal','2020-11-20 13:36:53'),(12,'*/5','*','*','*','*','moduleName=ci&methodName=checkCompileStatus','同步Jenkins任务状态','zentao',1,'normal','2020-11-20 13:45:53'),(13,'*/5','*','*','*','*','moduleName=ci&methodName=exec','执行Jenkins任务','zentao',1,'normal','2020-11-20 13:45:53');
/*!40000 ALTER TABLE `zt_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_dept`
--

DROP TABLE IF EXISTS `zt_dept`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_dept` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(60) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` smallint(4) unsigned NOT NULL DEFAULT '0',
  `position` char(30) NOT NULL DEFAULT '',
  `function` char(255) NOT NULL DEFAULT '',
  `manager` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `path` (`path`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_dept`
--

LOCK TABLES `zt_dept` WRITE;
/*!40000 ALTER TABLE `zt_dept` DISABLE KEYS */;
INSERT INTO `zt_dept` VALUES (1,'经理',0,',1,',1,0,'','',''),(2,'开发',0,',2,',1,0,'','',''),(3,'测试',0,',3,',1,0,'','',''),(4,'市场',0,',4,',1,0,'','',''),(5,'产品',1,',1,5,',2,0,'','',''),(6,'项目',1,',1,6,',2,0,'','',''),(7,'编程',2,',2,7,',2,0,'','',''),(8,'美工',2,',2,8,',2,0,'','','');
/*!40000 ALTER TABLE `zt_dept` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_design`
--

DROP TABLE IF EXISTS `zt_design`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_design` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `commit` text NOT NULL,
  `project` mediumint(9) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `parent` mediumint(8) unsigned NOT NULL,
  `status` varchar(30) NOT NULL,
  `subStatus` varchar(30) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `commitBy` varchar(30) NOT NULL,
  `commitDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedBy` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `story` char(30) NOT NULL,
  `desc` text NOT NULL,
  `version` smallint(6) NOT NULL,
  `type` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_design`
--

LOCK TABLES `zt_design` WRITE;
/*!40000 ALTER TABLE `zt_design` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_design` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_designspec`
--

DROP TABLE IF EXISTS `zt_designspec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_designspec` (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `files` varchar(255) NOT NULL,
  UNIQUE KEY `design` (`design`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_designspec`
--

LOCK TABLES `zt_designspec` WRITE;
/*!40000 ALTER TABLE `zt_designspec` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_designspec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_doc`
--

DROP TABLE IF EXISTS `zt_doc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_doc` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `lib` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `views` smallint(5) unsigned NOT NULL,
  `collector` text NOT NULL,
  `addedBy` varchar(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `acl` varchar(10) NOT NULL DEFAULT 'open',
  `groups` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `version` smallint(5) unsigned NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `project` (`project`),
  KEY `lib` (`lib`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_doc`
--

LOCK TABLES `zt_doc` WRITE;
/*!40000 ALTER TABLE `zt_doc` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_doc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_docLib`
--

DROP TABLE IF EXISTS `zt_docLib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_docLib` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `company` smallint(5) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_docLib`
--

LOCK TABLES `zt_docLib` WRITE;
/*!40000 ALTER TABLE `zt_docLib` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_docLib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_doccontent`
--

DROP TABLE IF EXISTS `zt_doccontent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_doccontent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `files` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `doc_version` (`doc`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_doccontent`
--

LOCK TABLES `zt_doccontent` WRITE;
/*!40000 ALTER TABLE `zt_doccontent` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_doccontent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_doclib`
--

DROP TABLE IF EXISTS `zt_doclib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_doclib` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `acl` varchar(10) NOT NULL DEFAULT 'open',
  `groups` varchar(255) NOT NULL,
  `users` text NOT NULL,
  `main` enum('0','1') NOT NULL DEFAULT '0',
  `collector` text NOT NULL,
  `order` tinyint(5) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `project` (`project`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_doclib`
--

LOCK TABLES `zt_doclib` WRITE;
/*!40000 ALTER TABLE `zt_doclib` DISABLE KEYS */;
INSERT INTO `zt_doclib` VALUES (1,'product',1,0,'产品库','default','','','1','',0,'0'),(2,'product',2,0,'产品库','default','','','1','',0,'0'),(3,'project',0,1,'项目库','default','','','1','',0,'0'),(4,'project',0,2,'项目库','default','','','1','',0,'0');
/*!40000 ALTER TABLE `zt_doclib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_durationestimation`
--

DROP TABLE IF EXISTS `zt_durationestimation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_durationestimation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
  `stage` mediumint(9) NOT NULL,
  `workload` varchar(255) NOT NULL,
  `worktimeRate` varchar(255) NOT NULL,
  `people` varchar(255) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_durationestimation`
--

LOCK TABLES `zt_durationestimation` WRITE;
/*!40000 ALTER TABLE `zt_durationestimation` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_durationestimation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_effort`
--

DROP TABLE IF EXISTS `zt_effort`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_effort` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user` char(30) NOT NULL DEFAULT '',
  `todo` enum('1','0') NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `begin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` enum('1','2','3') NOT NULL DEFAULT '1',
  `idvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `desc` char(255) NOT NULL DEFAULT '',
  `status` enum('1','2','3') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_effort`
--

LOCK TABLES `zt_effort` WRITE;
/*!40000 ALTER TABLE `zt_effort` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_effort` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_entry`
--

DROP TABLE IF EXISTS `zt_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_entry` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `account` varchar(30) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL,
  `key` varchar(32) NOT NULL,
  `freePasswd` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `calledTime` int(10) unsigned NOT NULL DEFAULT '0',
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_entry`
--

LOCK TABLES `zt_entry` WRITE;
/*!40000 ALTER TABLE `zt_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_extension`
--

DROP TABLE IF EXISTS `zt_extension`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_extension` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(30) NOT NULL,
  `version` varchar(50) NOT NULL,
  `author` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `license` text NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'extension',
  `site` varchar(150) NOT NULL,
  `zentaoCompatible` varchar(100) NOT NULL,
  `installedTime` datetime NOT NULL,
  `depends` varchar(100) NOT NULL,
  `dirs` mediumtext NOT NULL,
  `files` mediumtext NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `name` (`name`),
  KEY `installedTime` (`installedTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_extension`
--

LOCK TABLES `zt_extension` WRITE;
/*!40000 ALTER TABLE `zt_extension` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_extension` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_file`
--

DROP TABLE IF EXISTS `zt_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_file` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `pathname` char(100) NOT NULL,
  `title` char(255) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` int(10) unsigned NOT NULL DEFAULT '0',
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL DEFAULT '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `extra` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `objectType` (`objectType`),
  KEY `objectID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_file`
--

LOCK TABLES `zt_file` WRITE;
/*!40000 ALTER TABLE `zt_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_group`
--

DROP TABLE IF EXISTS `zt_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL,
  `role` char(30) NOT NULL DEFAULT '',
  `desc` char(255) NOT NULL DEFAULT '',
  `acl` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_group`
--

LOCK TABLES `zt_group` WRITE;
/*!40000 ALTER TABLE `zt_group` DISABLE KEYS */;
INSERT INTO `zt_group` VALUES (1,0,'管理员','admin','系统管理员',NULL),(2,0,'研发','dev','研发人员',NULL),(3,0,'测试','qa','测试人员',NULL),(4,0,'项目经理','pm','项目经理',NULL),(5,0,'产品经理','po','产品经理',NULL),(6,0,'研发主管','td','研发主管',NULL),(7,0,'产品主管','pd','产品主管',NULL),(8,0,'测试主管','qd','测试主管',NULL),(9,0,'高层管理','top','高层管理',NULL),(10,0,'其他','others','其他',NULL),(11,0,'guest','guest','For guest',NULL),(12,0,'受限用户','limited','受限用户分组(只能编辑与自己相关的内容)',NULL);
/*!40000 ALTER TABLE `zt_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_grouppriv`
--

DROP TABLE IF EXISTS `zt_grouppriv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_grouppriv` (
  `group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` char(30) NOT NULL DEFAULT '',
  `method` char(30) NOT NULL DEFAULT '',
  UNIQUE KEY `group` (`group`,`module`,`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_grouppriv`
--

LOCK TABLES `zt_grouppriv` WRITE;
/*!40000 ALTER TABLE `zt_grouppriv` DISABLE KEYS */;
INSERT INTO `zt_grouppriv` VALUES (1,'action','editComment'),(1,'action','hideAll'),(1,'action','hideOne'),(1,'action','trash'),(1,'action','undelete'),(1,'admin','checkDB'),(1,'admin','checkWeak'),(1,'admin','index'),(1,'admin','safe'),(1,'api','debug'),(1,'backup','backup'),(1,'backup','change'),(1,'backup','delete'),(1,'backup','index'),(1,'backup','restore'),(1,'branch','delete'),(1,'branch','manage'),(1,'branch','sort'),(1,'bug','activate'),(1,'bug','assignTo'),(1,'bug','batchActivate'),(1,'bug','batchAssignTo'),(1,'bug','batchChangeModule'),(1,'bug','batchClose'),(1,'bug','batchConfirm'),(1,'bug','batchCreate'),(1,'bug','batchEdit'),(1,'bug','batchResolve'),(1,'bug','browse'),(1,'bug','close'),(1,'bug','confirmBug'),(1,'bug','confirmStoryChange'),(1,'bug','create'),(1,'bug','delete'),(1,'bug','deleteTemplate'),(1,'bug','edit'),(1,'bug','export'),(1,'bug','index'),(1,'bug','linkBugs'),(1,'bug','report'),(1,'bug','resolve'),(1,'bug','saveTemplate'),(1,'bug','unlinkBug'),(1,'bug','view'),(1,'build','batchUnlinkBug'),(1,'build','batchUnlinkStory'),(1,'build','create'),(1,'build','delete'),(1,'build','edit'),(1,'build','linkBug'),(1,'build','linkStory'),(1,'build','unlinkBug'),(1,'build','unlinkStory'),(1,'build','view'),(1,'company','browse'),(1,'company','dynamic'),(1,'company','edit'),(1,'company','index'),(1,'company','view'),(1,'compile','browse'),(1,'compile','logs'),(1,'convert','checkBugFree'),(1,'convert','checkConfig'),(1,'convert','checkRedmine'),(1,'convert','convertBugFree'),(1,'convert','convertRedmine'),(1,'convert','execute'),(1,'convert','index'),(1,'convert','selectSource'),(1,'convert','setBugfree'),(1,'convert','setConfig'),(1,'convert','setRedmine'),(1,'cron','create'),(1,'cron','delete'),(1,'cron','edit'),(1,'cron','index'),(1,'cron','toggle'),(1,'cron','turnon'),(1,'custom','flow'),(1,'custom','index'),(1,'custom','restore'),(1,'custom','set'),(1,'custom','working'),(1,'dept','browse'),(1,'dept','delete'),(1,'dept','edit'),(1,'dept','manageChild'),(1,'dept','updateOrder'),(1,'dev','api'),(1,'dev','db'),(1,'dev','editor'),(1,'dev','translate'),(1,'doc','allLibs'),(1,'doc','browse'),(1,'doc','create'),(1,'doc','createLib'),(1,'doc','delete'),(1,'doc','deleteFile'),(1,'doc','deleteLib'),(1,'doc','edit'),(1,'doc','editLib'),(1,'doc','index'),(1,'doc','objectLibs'),(1,'doc','showFiles'),(1,'doc','sort'),(1,'doc','view'),(1,'extension','activate'),(1,'extension','browse'),(1,'extension','deactivate'),(1,'extension','erase'),(1,'extension','install'),(1,'extension','obtain'),(1,'extension','structure'),(1,'extension','uninstall'),(1,'extension','upgrade'),(1,'extension','upload'),(1,'file','delete'),(1,'file','download'),(1,'file','edit'),(1,'file','uploadImages'),(1,'git','apiSync'),(1,'git','cat'),(1,'git','diff'),(1,'group','browse'),(1,'group','copy'),(1,'group','create'),(1,'group','delete'),(1,'group','edit'),(1,'group','manageMember'),(1,'group','managePriv'),(1,'group','manageView'),(1,'index','index'),(1,'jenkins','browse'),(1,'jenkins','create'),(1,'jenkins','delete'),(1,'jenkins','edit'),(1,'job','browse'),(1,'job','create'),(1,'job','delete'),(1,'job','edit'),(1,'job','exec'),(1,'mail','batchDelete'),(1,'mail','browse'),(1,'mail','delete'),(1,'mail','detect'),(1,'mail','edit'),(1,'mail','index'),(1,'mail','resend'),(1,'mail','reset'),(1,'mail','save'),(1,'mail','test'),(1,'message','browser'),(1,'message','index'),(1,'message','setting'),(1,'misc','ping'),(1,'my','bug'),(1,'my','calendar'),(1,'my','changePassword'),(1,'my','deleteContacts'),(1,'my','dynamic'),(1,'my','editProfile'),(1,'my','index'),(1,'my','manageContacts'),(1,'my','profile'),(1,'my','project'),(1,'my','story'),(1,'my','task'),(1,'my','testCase'),(1,'my','testTask'),(1,'my','todo'),(1,'my','unbind'),(1,'product','all'),(1,'product','batchEdit'),(1,'product','browse'),(1,'product','build'),(1,'product','close'),(1,'product','create'),(1,'product','delete'),(1,'product','doc'),(1,'product','dynamic'),(1,'product','edit'),(1,'product','index'),(1,'product','order'),(1,'product','project'),(1,'product','roadmap'),(1,'product','updateOrder'),(1,'product','view'),(1,'productplan','batchUnlinkBug'),(1,'productplan','batchUnlinkStory'),(1,'productplan','browse'),(1,'productplan','create'),(1,'productplan','delete'),(1,'productplan','edit'),(1,'productplan','linkBug'),(1,'productplan','linkStory'),(1,'productplan','unlinkBug'),(1,'productplan','unlinkStory'),(1,'productplan','view'),(1,'project','activate'),(1,'project','all'),(1,'project','batchedit'),(1,'project','batchUnlinkStory'),(1,'project','browse'),(1,'project','bug'),(1,'project','build'),(1,'project','burn'),(1,'project','close'),(1,'project','computeBurn'),(1,'project','create'),(1,'project','delete'),(1,'project','doc'),(1,'project','dynamic'),(1,'project','edit'),(1,'project','grouptask'),(1,'project','importBug'),(1,'project','importPlanStories'),(1,'project','importtask'),(1,'project','index'),(1,'project','kanban'),(1,'project','linkStory'),(1,'project','manageMembers'),(1,'project','manageProducts'),(1,'project','order'),(1,'project','putoff'),(1,'project','start'),(1,'project','story'),(1,'project','suspend'),(1,'project','task'),(1,'project','team'),(1,'project','testtask'),(1,'project','tree'),(1,'project','treestory'),(1,'project','treetask'),(1,'project','unlinkMember'),(1,'project','unlinkStory'),(1,'project','updateOrder'),(1,'project','view'),(1,'qa','index'),(1,'release','batchUnlinkBug'),(1,'release','batchUnlinkStory'),(1,'release','browse'),(1,'release','changeStatus'),(1,'release','create'),(1,'release','delete'),(1,'release','edit'),(1,'release','export'),(1,'release','linkBug'),(1,'release','linkStory'),(1,'release','unlinkBug'),(1,'release','unlinkStory'),(1,'release','view'),(1,'repo','blame'),(1,'repo','browse'),(1,'repo','create'),(1,'repo','delete'),(1,'repo','diff'),(1,'repo','download'),(1,'repo','edit'),(1,'repo','log'),(1,'repo','maintain'),(1,'repo','revision'),(1,'repo','setRules'),(1,'repo','showSyncCommit'),(1,'repo','view'),(1,'report','bugAssign'),(1,'report','bugCreate'),(1,'report','index'),(1,'report','productSummary'),(1,'report','projectDeviation'),(1,'report','workload'),(1,'search','buildForm'),(1,'search','buildQuery'),(1,'search','deleteQuery'),(1,'search','saveQuery'),(1,'search','select'),(1,'story','activate'),(1,'story','assignTo'),(1,'story','batchAssignTo'),(1,'story','batchChangeBranch'),(1,'story','batchChangeModule'),(1,'story','batchChangePlan'),(1,'story','batchChangeStage'),(1,'story','batchClose'),(1,'story','batchCreate'),(1,'story','batchEdit'),(1,'story','batchReview'),(1,'story','bugs'),(1,'story','cases'),(1,'story','change'),(1,'story','close'),(1,'story','create'),(1,'story','delete'),(1,'story','edit'),(1,'story','export'),(1,'story','linkStory'),(1,'story','report'),(1,'story','review'),(1,'story','tasks'),(1,'story','unlinkStory'),(1,'story','view'),(1,'story','zeroCase'),(1,'svn','apiSync'),(1,'svn','cat'),(1,'svn','diff'),(1,'task','activate'),(1,'task','assignTo'),(1,'task','batchAssignTo'),(1,'task','batchChangeModule'),(1,'task','batchClose'),(1,'task','batchCreate'),(1,'task','batchEdit'),(1,'task','cancel'),(1,'task','close'),(1,'task','confirmStoryChange'),(1,'task','create'),(1,'task','delete'),(1,'task','deleteEstimate'),(1,'task','edit'),(1,'task','editEstimate'),(1,'task','export'),(1,'task','finish'),(1,'task','pause'),(1,'task','recordEstimate'),(1,'task','report'),(1,'task','restart'),(1,'task','start'),(1,'task','view'),(1,'testcase','batchCaseTypeChange'),(1,'testcase','batchChangeModule'),(1,'testcase','batchConfirmStoryChange'),(1,'testcase','batchCreate'),(1,'testcase','batchDelete'),(1,'testcase','batchEdit'),(1,'testcase','batchReview'),(1,'testcase','browse'),(1,'testcase','bugs'),(1,'testcase','confirmChange'),(1,'testcase','confirmStoryChange'),(1,'testcase','create'),(1,'testcase','createBug'),(1,'testcase','delete'),(1,'testcase','edit'),(1,'testcase','export'),(1,'testcase','exportTemplet'),(1,'testcase','groupCase'),(1,'testcase','import'),(1,'testcase','importFromLib'),(1,'testcase','index'),(1,'testcase','linkCases'),(1,'testcase','review'),(1,'testcase','showImport'),(1,'testcase','unlinkCase'),(1,'testcase','view'),(1,'testreport','browse'),(1,'testreport','create'),(1,'testreport','delete'),(1,'testreport','edit'),(1,'testreport','view'),(1,'testsuite','batchCreateCase'),(1,'testsuite','batchUnlinkCases'),(1,'testsuite','browse'),(1,'testsuite','create'),(1,'testsuite','createCase'),(1,'testsuite','createLib'),(1,'testsuite','delete'),(1,'testsuite','edit'),(1,'testsuite','exportTemplet'),(1,'testsuite','import'),(1,'testsuite','index'),(1,'testsuite','library'),(1,'testsuite','libView'),(1,'testsuite','linkCase'),(1,'testsuite','showImport'),(1,'testsuite','unlinkCase'),(1,'testsuite','view'),(1,'testtask','activate'),(1,'testtask','batchAssign'),(1,'testtask','batchRun'),(1,'testtask','block'),(1,'testtask','browse'),(1,'testtask','cases'),(1,'testtask','close'),(1,'testtask','create'),(1,'testtask','delete'),(1,'testtask','edit'),(1,'testtask','groupCase'),(1,'testtask','index'),(1,'testtask','linkcase'),(1,'testtask','report'),(1,'testtask','results'),(1,'testtask','runcase'),(1,'testtask','start'),(1,'testtask','unlinkcase'),(1,'testtask','view'),(1,'todo','activate'),(1,'todo','assignTo'),(1,'todo','batchClose'),(1,'todo','batchCreate'),(1,'todo','batchEdit'),(1,'todo','batchFinish'),(1,'todo','close'),(1,'todo','create'),(1,'todo','createCycle'),(1,'todo','delete'),(1,'todo','edit'),(1,'todo','export'),(1,'todo','finish'),(1,'todo','import2Today'),(1,'todo','view'),(1,'tree','browse'),(1,'tree','browseTask'),(1,'tree','delete'),(1,'tree','edit'),(1,'tree','fix'),(1,'tree','manageChild'),(1,'tree','updateOrder'),(1,'user','batchCreate'),(1,'user','batchEdit'),(1,'user','bug'),(1,'user','create'),(1,'user','delete'),(1,'user','deleteContacts'),(1,'user','dynamic'),(1,'user','edit'),(1,'user','manageContacts'),(1,'user','profile'),(1,'user','project'),(1,'user','story'),(1,'user','task'),(1,'user','testCase'),(1,'user','testTask'),(1,'user','todo'),(1,'user','unbind'),(1,'user','unlock'),(1,'user','view'),(2,'action','editComment'),(2,'bug','activate'),(2,'bug','assignTo'),(2,'bug','batchActivate'),(2,'bug','batchAssignTo'),(2,'bug','batchChangeModule'),(2,'bug','batchClose'),(2,'bug','batchConfirm'),(2,'bug','batchCreate'),(2,'bug','batchEdit'),(2,'bug','batchResolve'),(2,'bug','browse'),(2,'bug','close'),(2,'bug','confirmBug'),(2,'bug','confirmStoryChange'),(2,'bug','create'),(2,'bug','deleteTemplate'),(2,'bug','edit'),(2,'bug','export'),(2,'bug','index'),(2,'bug','linkBugs'),(2,'bug','report'),(2,'bug','resolve'),(2,'bug','saveTemplate'),(2,'bug','unlinkBug'),(2,'bug','view'),(2,'build','create'),(2,'build','delete'),(2,'build','edit'),(2,'build','view'),(2,'company','browse'),(2,'company','dynamic'),(2,'company','index'),(2,'company','view'),(2,'doc','allLibs'),(2,'doc','browse'),(2,'doc','create'),(2,'doc','edit'),(2,'doc','index'),(2,'doc','objectLibs'),(2,'doc','showFiles'),(2,'doc','view'),(2,'file','download'),(2,'file','edit'),(2,'git','apiSync'),(2,'git','cat'),(2,'git','diff'),(2,'group','browse'),(2,'index','index'),(2,'misc','ping'),(2,'my','bug'),(2,'my','calendar'),(2,'my','changePassword'),(2,'my','deleteContacts'),(2,'my','dynamic'),(2,'my','editProfile'),(2,'my','index'),(2,'my','manageContacts'),(2,'my','profile'),(2,'my','project'),(2,'my','story'),(2,'my','task'),(2,'my','todo'),(2,'my','unbind'),(2,'product','all'),(2,'product','browse'),(2,'product','build'),(2,'product','doc'),(2,'product','dynamic'),(2,'product','index'),(2,'product','project'),(2,'product','roadmap'),(2,'product','view'),(2,'productplan','browse'),(2,'productplan','view'),(2,'project','all'),(2,'project','browse'),(2,'project','bug'),(2,'project','build'),(2,'project','burn'),(2,'project','computeBurn'),(2,'project','doc'),(2,'project','dynamic'),(2,'project','grouptask'),(2,'project','importBug'),(2,'project','importtask'),(2,'project','index'),(2,'project','kanban'),(2,'project','story'),(2,'project','task'),(2,'project','team'),(2,'project','testtask'),(2,'project','tree'),(2,'project','treestory'),(2,'project','treetask'),(2,'project','view'),(2,'qa','index'),(2,'release','browse'),(2,'release','export'),(2,'release','view'),(2,'repo','blame'),(2,'repo','browse'),(2,'repo','diff'),(2,'repo','download'),(2,'repo','log'),(2,'repo','revision'),(2,'repo','showSyncCommit'),(2,'repo','view'),(2,'report','bugAssign'),(2,'report','bugCreate'),(2,'report','index'),(2,'report','productSummary'),(2,'report','projectDeviation'),(2,'report','workload'),(2,'search','buildForm'),(2,'search','buildQuery'),(2,'search','deleteQuery'),(2,'search','saveQuery'),(2,'search','select'),(2,'story','bugs'),(2,'story','cases'),(2,'story','export'),(2,'story','report'),(2,'story','tasks'),(2,'story','view'),(2,'svn','apiSync'),(2,'svn','cat'),(2,'svn','diff'),(2,'task','activate'),(2,'task','assignTo'),(2,'task','batchAssignTo'),(2,'task','batchChangeModule'),(2,'task','batchClose'),(2,'task','batchCreate'),(2,'task','batchEdit'),(2,'task','cancel'),(2,'task','close'),(2,'task','confirmStoryChange'),(2,'task','create'),(2,'task','deleteEstimate'),(2,'task','edit'),(2,'task','editEstimate'),(2,'task','export'),(2,'task','finish'),(2,'task','pause'),(2,'task','recordEstimate'),(2,'task','report'),(2,'task','restart'),(2,'task','start'),(2,'task','view'),(2,'testcase','browse'),(2,'testcase','bugs'),(2,'testcase','export'),(2,'testcase','groupCase'),(2,'testcase','index'),(2,'testcase','view'),(2,'testsuite','browse'),(2,'testsuite','index'),(2,'testsuite','view'),(2,'testtask','browse'),(2,'testtask','cases'),(2,'testtask','create'),(2,'testtask','edit'),(2,'testtask','groupCase'),(2,'testtask','index'),(2,'testtask','results'),(2,'testtask','view'),(2,'todo','activate'),(2,'todo','assignTo'),(2,'todo','batchClose'),(2,'todo','batchCreate'),(2,'todo','batchEdit'),(2,'todo','batchFinish'),(2,'todo','close'),(2,'todo','create'),(2,'todo','createCycle'),(2,'todo','delete'),(2,'todo','edit'),(2,'todo','export'),(2,'todo','finish'),(2,'todo','import2Today'),(2,'todo','view'),(2,'user','bug'),(2,'user','deleteContacts'),(2,'user','dynamic'),(2,'user','manageContacts'),(2,'user','profile'),(2,'user','project'),(2,'user','story'),(2,'user','task'),(2,'user','testCase'),(2,'user','testTask'),(2,'user','todo'),(2,'user','view'),(3,'action','editComment'),(3,'bug','activate'),(3,'bug','assignTo'),(3,'bug','batchActivate'),(3,'bug','batchChangeModule'),(3,'bug','batchClose'),(3,'bug','batchConfirm'),(3,'bug','batchCreate'),(3,'bug','batchEdit'),(3,'bug','batchResolve'),(3,'bug','browse'),(3,'bug','close'),(3,'bug','confirmBug'),(3,'bug','confirmStoryChange'),(3,'bug','create'),(3,'bug','deleteTemplate'),(3,'bug','edit'),(3,'bug','export'),(3,'bug','index'),(3,'bug','linkBugs'),(3,'bug','report'),(3,'bug','resolve'),(3,'bug','saveTemplate'),(3,'bug','unlinkBug'),(3,'bug','view'),(3,'build','create'),(3,'build','edit'),(3,'build','view'),(3,'company','browse'),(3,'company','dynamic'),(3,'company','index'),(3,'company','view'),(3,'doc','allLibs'),(3,'doc','browse'),(3,'doc','create'),(3,'doc','edit'),(3,'doc','index'),(3,'doc','objectLibs'),(3,'doc','showFiles'),(3,'doc','view'),(3,'file','download'),(3,'file','edit'),(3,'git','apiSync'),(3,'git','cat'),(3,'git','diff'),(3,'group','browse'),(3,'index','index'),(3,'misc','ping'),(3,'my','bug'),(3,'my','calendar'),(3,'my','changePassword'),(3,'my','deleteContacts'),(3,'my','dynamic'),(3,'my','editProfile'),(3,'my','index'),(3,'my','manageContacts'),(3,'my','profile'),(3,'my','project'),(3,'my','story'),(3,'my','task'),(3,'my','testCase'),(3,'my','testTask'),(3,'my','todo'),(3,'my','unbind'),(3,'product','all'),(3,'product','browse'),(3,'product','build'),(3,'product','doc'),(3,'product','dynamic'),(3,'product','index'),(3,'product','project'),(3,'product','roadmap'),(3,'product','view'),(3,'productplan','browse'),(3,'productplan','view'),(3,'project','all'),(3,'project','browse'),(3,'project','bug'),(3,'project','build'),(3,'project','burn'),(3,'project','computeBurn'),(3,'project','doc'),(3,'project','dynamic'),(3,'project','grouptask'),(3,'project','importBug'),(3,'project','importtask'),(3,'project','index'),(3,'project','kanban'),(3,'project','story'),(3,'project','task'),(3,'project','team'),(3,'project','testtask'),(3,'project','tree'),(3,'project','treestory'),(3,'project','treetask'),(3,'project','view'),(3,'qa','index'),(3,'release','browse'),(3,'release','export'),(3,'release','view'),(3,'repo','blame'),(3,'repo','browse'),(3,'repo','diff'),(3,'repo','download'),(3,'repo','log'),(3,'repo','revision'),(3,'repo','showSyncCommit'),(3,'repo','view'),(3,'report','bugAssign'),(3,'report','bugCreate'),(3,'report','index'),(3,'report','productSummary'),(3,'report','projectDeviation'),(3,'report','workload'),(3,'search','buildForm'),(3,'search','buildQuery'),(3,'search','deleteQuery'),(3,'search','saveQuery'),(3,'search','select'),(3,'story','bugs'),(3,'story','cases'),(3,'story','export'),(3,'story','report'),(3,'story','tasks'),(3,'story','view'),(3,'story','zeroCase'),(3,'svn','apiSync'),(3,'svn','cat'),(3,'svn','diff'),(3,'task','activate'),(3,'task','assignTo'),(3,'task','batchAssignTo'),(3,'task','batchChangeModule'),(3,'task','batchClose'),(3,'task','batchCreate'),(3,'task','batchEdit'),(3,'task','cancel'),(3,'task','close'),(3,'task','confirmStoryChange'),(3,'task','create'),(3,'task','deleteEstimate'),(3,'task','edit'),(3,'task','editEstimate'),(3,'task','export'),(3,'task','finish'),(3,'task','pause'),(3,'task','recordEstimate'),(3,'task','report'),(3,'task','restart'),(3,'task','start'),(3,'task','view'),(3,'testcase','batchCaseTypeChange'),(3,'testcase','batchChangeModule'),(3,'testcase','batchConfirmStoryChange'),(3,'testcase','batchCreate'),(3,'testcase','batchEdit'),(3,'testcase','batchReview'),(3,'testcase','browse'),(3,'testcase','bugs'),(3,'testcase','confirmChange'),(3,'testcase','confirmStoryChange'),(3,'testcase','create'),(3,'testcase','createBug'),(3,'testcase','edit'),(3,'testcase','export'),(3,'testcase','exportTemplet'),(3,'testcase','groupCase'),(3,'testcase','import'),(3,'testcase','index'),(3,'testcase','linkCases'),(3,'testcase','review'),(3,'testcase','showImport'),(3,'testcase','unlinkCase'),(3,'testcase','view'),(3,'testreport','browse'),(3,'testreport','view'),(3,'testsuite','browse'),(3,'testsuite','create'),(3,'testsuite','delete'),(3,'testsuite','edit'),(3,'testsuite','index'),(3,'testsuite','linkCase'),(3,'testsuite','unlinkCase'),(3,'testsuite','view'),(3,'testtask','activate'),(3,'testtask','batchAssign'),(3,'testtask','batchRun'),(3,'testtask','block'),(3,'testtask','browse'),(3,'testtask','cases'),(3,'testtask','close'),(3,'testtask','create'),(3,'testtask','edit'),(3,'testtask','groupCase'),(3,'testtask','index'),(3,'testtask','linkcase'),(3,'testtask','report'),(3,'testtask','results'),(3,'testtask','runcase'),(3,'testtask','start'),(3,'testtask','unlinkcase'),(3,'testtask','view'),(3,'todo','activate'),(3,'todo','assignTo'),(3,'todo','batchClose'),(3,'todo','batchCreate'),(3,'todo','batchEdit'),(3,'todo','batchFinish'),(3,'todo','close'),(3,'todo','create'),(3,'todo','createCycle'),(3,'todo','delete'),(3,'todo','edit'),(3,'todo','export'),(3,'todo','finish'),(3,'todo','import2Today'),(3,'todo','view'),(3,'user','bug'),(3,'user','deleteContacts'),(3,'user','dynamic'),(3,'user','manageContacts'),(3,'user','profile'),(3,'user','project'),(3,'user','story'),(3,'user','task'),(3,'user','testCase'),(3,'user','testTask'),(3,'user','todo'),(3,'user','view'),(4,'action','editComment'),(4,'action','hideAll'),(4,'action','hideOne'),(4,'action','trash'),(4,'action','undelete'),(4,'admin','index'),(4,'bug','activate'),(4,'bug','assignTo'),(4,'bug','batchActivate'),(4,'bug','batchAssignTo'),(4,'bug','batchChangeModule'),(4,'bug','batchClose'),(4,'bug','batchConfirm'),(4,'bug','batchCreate'),(4,'bug','batchEdit'),(4,'bug','batchResolve'),(4,'bug','browse'),(4,'bug','close'),(4,'bug','confirmBug'),(4,'bug','confirmStoryChange'),(4,'bug','create'),(4,'bug','delete'),(4,'bug','deleteTemplate'),(4,'bug','edit'),(4,'bug','export'),(4,'bug','index'),(4,'bug','linkBugs'),(4,'bug','report'),(4,'bug','resolve'),(4,'bug','saveTemplate'),(4,'bug','unlinkBug'),(4,'bug','view'),(4,'build','batchUnlinkBug'),(4,'build','batchUnlinkStory'),(4,'build','create'),(4,'build','delete'),(4,'build','edit'),(4,'build','linkBug'),(4,'build','linkStory'),(4,'build','unlinkBug'),(4,'build','unlinkStory'),(4,'build','view'),(4,'company','browse'),(4,'company','dynamic'),(4,'company','index'),(4,'company','view'),(4,'compile','browse'),(4,'compile','logs'),(4,'doc','allLibs'),(4,'doc','browse'),(4,'doc','create'),(4,'doc','createLib'),(4,'doc','delete'),(4,'doc','deleteFile'),(4,'doc','deleteLib'),(4,'doc','edit'),(4,'doc','editLib'),(4,'doc','index'),(4,'doc','objectLibs'),(4,'doc','showFiles'),(4,'doc','view'),(4,'extension','browse'),(4,'extension','obtain'),(4,'extension','structure'),(4,'file','delete'),(4,'file','download'),(4,'file','edit'),(4,'file','uploadImages'),(4,'git','apiSync'),(4,'git','cat'),(4,'git','diff'),(4,'group','browse'),(4,'index','index'),(4,'jenkins','browse'),(4,'jenkins','create'),(4,'jenkins','delete'),(4,'jenkins','edit'),(4,'job','browse'),(4,'job','create'),(4,'job','delete'),(4,'job','edit'),(4,'job','exec'),(4,'misc','ping'),(4,'my','bug'),(4,'my','calendar'),(4,'my','changePassword'),(4,'my','deleteContacts'),(4,'my','dynamic'),(4,'my','editProfile'),(4,'my','index'),(4,'my','manageContacts'),(4,'my','profile'),(4,'my','project'),(4,'my','story'),(4,'my','task'),(4,'my','testCase'),(4,'my','testTask'),(4,'my','todo'),(4,'my','unbind'),(4,'product','all'),(4,'product','browse'),(4,'product','build'),(4,'product','doc'),(4,'product','dynamic'),(4,'product','index'),(4,'product','project'),(4,'product','roadmap'),(4,'product','view'),(4,'productplan','browse'),(4,'productplan','view'),(4,'project','activate'),(4,'project','all'),(4,'project','batchedit'),(4,'project','batchUnlinkStory'),(4,'project','browse'),(4,'project','bug'),(4,'project','build'),(4,'project','burn'),(4,'project','close'),(4,'project','computeBurn'),(4,'project','create'),(4,'project','delete'),(4,'project','doc'),(4,'project','dynamic'),(4,'project','edit'),(4,'project','grouptask'),(4,'project','importBug'),(4,'project','importPlanStories'),(4,'project','importtask'),(4,'project','index'),(4,'project','kanban'),(4,'project','linkStory'),(4,'project','manageMembers'),(4,'project','manageProducts'),(4,'project','order'),(4,'project','putoff'),(4,'project','start'),(4,'project','story'),(4,'project','suspend'),(4,'project','task'),(4,'project','team'),(4,'project','testtask'),(4,'project','tree'),(4,'project','treestory'),(4,'project','treetask'),(4,'project','unlinkMember'),(4,'project','unlinkStory'),(4,'project','updateOrder'),(4,'project','view'),(4,'qa','index'),(4,'release','browse'),(4,'release','export'),(4,'release','view'),(4,'repo','blame'),(4,'repo','browse'),(4,'repo','create'),(4,'repo','delete'),(4,'repo','diff'),(4,'repo','download'),(4,'repo','edit'),(4,'repo','log'),(4,'repo','maintain'),(4,'repo','revision'),(4,'repo','setRules'),(4,'repo','showSyncCommit'),(4,'repo','view'),(4,'report','bugAssign'),(4,'report','bugCreate'),(4,'report','index'),(4,'report','productSummary'),(4,'report','projectDeviation'),(4,'report','workload'),(4,'search','buildForm'),(4,'search','buildQuery'),(4,'search','deleteQuery'),(4,'search','saveQuery'),(4,'search','select'),(4,'story','bugs'),(4,'story','cases'),(4,'story','export'),(4,'story','report'),(4,'story','tasks'),(4,'story','view'),(4,'story','zeroCase'),(4,'svn','apiSync'),(4,'svn','cat'),(4,'svn','diff'),(4,'task','activate'),(4,'task','assignTo'),(4,'task','batchAssignTo'),(4,'task','batchChangeModule'),(4,'task','batchClose'),(4,'task','batchCreate'),(4,'task','batchEdit'),(4,'task','cancel'),(4,'task','close'),(4,'task','confirmStoryChange'),(4,'task','create'),(4,'task','delete'),(4,'task','deleteEstimate'),(4,'task','edit'),(4,'task','editEstimate'),(4,'task','export'),(4,'task','finish'),(4,'task','pause'),(4,'task','recordEstimate'),(4,'task','report'),(4,'task','restart'),(4,'task','start'),(4,'task','view'),(4,'testcase','browse'),(4,'testcase','bugs'),(4,'testcase','createBug'),(4,'testcase','export'),(4,'testcase','groupCase'),(4,'testcase','index'),(4,'testcase','view'),(4,'testsuite','browse'),(4,'testsuite','index'),(4,'testsuite','view'),(4,'testtask','batchAssign'),(4,'testtask','browse'),(4,'testtask','cases'),(4,'testtask','create'),(4,'testtask','edit'),(4,'testtask','groupCase'),(4,'testtask','index'),(4,'testtask','results'),(4,'testtask','view'),(4,'todo','activate'),(4,'todo','assignTo'),(4,'todo','batchClose'),(4,'todo','batchCreate'),(4,'todo','batchEdit'),(4,'todo','batchFinish'),(4,'todo','close'),(4,'todo','create'),(4,'todo','createCycle'),(4,'todo','delete'),(4,'todo','edit'),(4,'todo','export'),(4,'todo','finish'),(4,'todo','import2Today'),(4,'todo','view'),(4,'tree','browse'),(4,'tree','browseTask'),(4,'tree','delete'),(4,'tree','edit'),(4,'tree','fix'),(4,'tree','manageChild'),(4,'tree','updateOrder'),(4,'user','bug'),(4,'user','deleteContacts'),(4,'user','dynamic'),(4,'user','manageContacts'),(4,'user','profile'),(4,'user','project'),(4,'user','story'),(4,'user','task'),(4,'user','testCase'),(4,'user','testTask'),(4,'user','todo'),(4,'user','view'),(5,'action','editComment'),(5,'action','hideAll'),(5,'action','hideOne'),(5,'action','trash'),(5,'action','undelete'),(5,'admin','index'),(5,'branch','delete'),(5,'branch','manage'),(5,'branch','sort'),(5,'bug','activate'),(5,'bug','assignTo'),(5,'bug','batchActivate'),(5,'bug','batchAssignTo'),(5,'bug','batchChangeModule'),(5,'bug','batchClose'),(5,'bug','batchConfirm'),(5,'bug','batchCreate'),(5,'bug','batchEdit'),(5,'bug','batchResolve'),(5,'bug','browse'),(5,'bug','close'),(5,'bug','confirmBug'),(5,'bug','confirmStoryChange'),(5,'bug','create'),(5,'bug','deleteTemplate'),(5,'bug','edit'),(5,'bug','export'),(5,'bug','index'),(5,'bug','linkBugs'),(5,'bug','report'),(5,'bug','resolve'),(5,'bug','saveTemplate'),(5,'bug','unlinkBug'),(5,'bug','view'),(5,'build','view'),(5,'company','browse'),(5,'company','dynamic'),(5,'company','index'),(5,'company','view'),(5,'compile','browse'),(5,'compile','logs'),(5,'doc','allLibs'),(5,'doc','browse'),(5,'doc','create'),(5,'doc','createLib'),(5,'doc','delete'),(5,'doc','deleteFile'),(5,'doc','deleteLib'),(5,'doc','edit'),(5,'doc','editLib'),(5,'doc','index'),(5,'doc','objectLibs'),(5,'doc','showFiles'),(5,'doc','view'),(5,'extension','browse'),(5,'extension','obtain'),(5,'extension','structure'),(5,'file','delete'),(5,'file','download'),(5,'file','edit'),(5,'file','uploadImages'),(5,'git','apiSync'),(5,'git','cat'),(5,'git','diff'),(5,'group','browse'),(5,'index','index'),(5,'jenkins','browse'),(5,'jenkins','create'),(5,'jenkins','delete'),(5,'jenkins','edit'),(5,'job','browse'),(5,'job','create'),(5,'job','delete'),(5,'job','edit'),(5,'job','exec'),(5,'misc','ping'),(5,'my','bug'),(5,'my','calendar'),(5,'my','changePassword'),(5,'my','deleteContacts'),(5,'my','dynamic'),(5,'my','editProfile'),(5,'my','index'),(5,'my','manageContacts'),(5,'my','profile'),(5,'my','project'),(5,'my','story'),(5,'my','task'),(5,'my','testCase'),(5,'my','testTask'),(5,'my','todo'),(5,'my','unbind'),(5,'product','all'),(5,'product','batchEdit'),(5,'product','browse'),(5,'product','build'),(5,'product','close'),(5,'product','create'),(5,'product','delete'),(5,'product','doc'),(5,'product','dynamic'),(5,'product','edit'),(5,'product','index'),(5,'product','order'),(5,'product','project'),(5,'product','roadmap'),(5,'product','updateOrder'),(5,'product','view'),(5,'productplan','batchUnlinkBug'),(5,'productplan','batchUnlinkStory'),(5,'productplan','browse'),(5,'productplan','create'),(5,'productplan','delete'),(5,'productplan','edit'),(5,'productplan','linkBug'),(5,'productplan','linkStory'),(5,'productplan','unlinkBug'),(5,'productplan','unlinkStory'),(5,'productplan','view'),(5,'project','activate'),(5,'project','all'),(5,'project','batchedit'),(5,'project','batchUnlinkStory'),(5,'project','browse'),(5,'project','bug'),(5,'project','build'),(5,'project','burn'),(5,'project','close'),(5,'project','computeBurn'),(5,'project','create'),(5,'project','delete'),(5,'project','doc'),(5,'project','dynamic'),(5,'project','edit'),(5,'project','grouptask'),(5,'project','importBug'),(5,'project','importPlanStories'),(5,'project','importtask'),(5,'project','index'),(5,'project','kanban'),(5,'project','linkStory'),(5,'project','manageMembers'),(5,'project','manageProducts'),(5,'project','order'),(5,'project','putoff'),(5,'project','start'),(5,'project','story'),(5,'project','suspend'),(5,'project','task'),(5,'project','team'),(5,'project','testtask'),(5,'project','tree'),(5,'project','treestory'),(5,'project','treetask'),(5,'project','unlinkMember'),(5,'project','unlinkStory'),(5,'project','updateOrder'),(5,'project','view'),(5,'qa','index'),(5,'release','batchUnlinkBug'),(5,'release','batchUnlinkStory'),(5,'release','browse'),(5,'release','changeStatus'),(5,'release','create'),(5,'release','delete'),(5,'release','edit'),(5,'release','export'),(5,'release','linkBug'),(5,'release','linkStory'),(5,'release','unlinkBug'),(5,'release','unlinkStory'),(5,'release','view'),(5,'repo','blame'),(5,'repo','browse'),(5,'repo','create'),(5,'repo','delete'),(5,'repo','diff'),(5,'repo','download'),(5,'repo','edit'),(5,'repo','log'),(5,'repo','maintain'),(5,'repo','revision'),(5,'repo','setRules'),(5,'repo','showSyncCommit'),(5,'repo','view'),(5,'report','bugAssign'),(5,'report','bugCreate'),(5,'report','index'),(5,'report','productSummary'),(5,'report','projectDeviation'),(5,'report','workload'),(5,'search','buildForm'),(5,'search','buildQuery'),(5,'search','deleteQuery'),(5,'search','saveQuery'),(5,'search','select'),(5,'story','activate'),(5,'story','batchChangeBranch'),(5,'story','batchChangeModule'),(5,'story','batchChangePlan'),(5,'story','batchChangeStage'),(5,'story','batchClose'),(5,'story','batchCreate'),(5,'story','batchEdit'),(5,'story','batchReview'),(5,'story','bugs'),(5,'story','cases'),(5,'story','change'),(5,'story','close'),(5,'story','create'),(5,'story','delete'),(5,'story','edit'),(5,'story','export'),(5,'story','linkStory'),(5,'story','report'),(5,'story','review'),(5,'story','tasks'),(5,'story','unlinkStory'),(5,'story','view'),(5,'story','zeroCase'),(5,'svn','apiSync'),(5,'svn','cat'),(5,'svn','diff'),(5,'task','confirmStoryChange'),(5,'task','deleteEstimate'),(5,'task','editEstimate'),(5,'task','export'),(5,'task','recordEstimate'),(5,'task','report'),(5,'task','view'),(5,'testcase','browse'),(5,'testcase','bugs'),(5,'testcase','createBug'),(5,'testcase','export'),(5,'testcase','groupCase'),(5,'testcase','importFromLib'),(5,'testcase','index'),(5,'testcase','view'),(5,'testsuite','batchCreateCase'),(5,'testsuite','browse'),(5,'testsuite','createCase'),(5,'testsuite','createLib'),(5,'testsuite','exportTemplet'),(5,'testsuite','import'),(5,'testsuite','index'),(5,'testsuite','library'),(5,'testsuite','libView'),(5,'testsuite','showImport'),(5,'testsuite','view'),(5,'testtask','browse'),(5,'testtask','cases'),(5,'testtask','groupCase'),(5,'testtask','index'),(5,'testtask','results'),(5,'testtask','view'),(5,'todo','activate'),(5,'todo','assignTo'),(5,'todo','batchClose'),(5,'todo','batchCreate'),(5,'todo','batchEdit'),(5,'todo','batchFinish'),(5,'todo','close'),(5,'todo','create'),(5,'todo','createCycle'),(5,'todo','delete'),(5,'todo','edit'),(5,'todo','export'),(5,'todo','finish'),(5,'todo','import2Today'),(5,'todo','view'),(5,'tree','browse'),(5,'tree','browseTask'),(5,'tree','delete'),(5,'tree','edit'),(5,'tree','fix'),(5,'tree','manageChild'),(5,'tree','updateOrder'),(5,'user','bug'),(5,'user','deleteContacts'),(5,'user','dynamic'),(5,'user','manageContacts'),(5,'user','profile'),(5,'user','project'),(5,'user','story'),(5,'user','task'),(5,'user','testCase'),(5,'user','testTask'),(5,'user','todo'),(5,'user','view'),(6,'action','editComment'),(6,'action','hideAll'),(6,'action','hideOne'),(6,'action','trash'),(6,'action','undelete'),(6,'admin','index'),(6,'bug','activate'),(6,'bug','assignTo'),(6,'bug','batchActivate'),(6,'bug','batchAssignTo'),(6,'bug','batchChangeModule'),(6,'bug','batchClose'),(6,'bug','batchConfirm'),(6,'bug','batchCreate'),(6,'bug','batchEdit'),(6,'bug','batchResolve'),(6,'bug','browse'),(6,'bug','close'),(6,'bug','confirmBug'),(6,'bug','confirmStoryChange'),(6,'bug','create'),(6,'bug','delete'),(6,'bug','deleteTemplate'),(6,'bug','edit'),(6,'bug','export'),(6,'bug','index'),(6,'bug','linkBugs'),(6,'bug','report'),(6,'bug','resolve'),(6,'bug','saveTemplate'),(6,'bug','unlinkBug'),(6,'bug','view'),(6,'build','batchUnlinkBug'),(6,'build','batchUnlinkStory'),(6,'build','create'),(6,'build','delete'),(6,'build','edit'),(6,'build','linkBug'),(6,'build','linkStory'),(6,'build','unlinkBug'),(6,'build','unlinkStory'),(6,'build','view'),(6,'company','browse'),(6,'company','dynamic'),(6,'company','index'),(6,'company','view'),(6,'doc','allLibs'),(6,'doc','browse'),(6,'doc','create'),(6,'doc','createLib'),(6,'doc','delete'),(6,'doc','deleteFile'),(6,'doc','deleteLib'),(6,'doc','edit'),(6,'doc','editLib'),(6,'doc','index'),(6,'doc','objectLibs'),(6,'doc','showFiles'),(6,'doc','view'),(6,'extension','browse'),(6,'extension','obtain'),(6,'extension','structure'),(6,'file','delete'),(6,'file','download'),(6,'file','edit'),(6,'file','uploadImages'),(6,'git','apiSync'),(6,'git','cat'),(6,'git','diff'),(6,'group','browse'),(6,'index','index'),(6,'misc','ping'),(6,'my','bug'),(6,'my','calendar'),(6,'my','changePassword'),(6,'my','deleteContacts'),(6,'my','dynamic'),(6,'my','editProfile'),(6,'my','index'),(6,'my','manageContacts'),(6,'my','profile'),(6,'my','project'),(6,'my','story'),(6,'my','task'),(6,'my','testCase'),(6,'my','testTask'),(6,'my','todo'),(6,'my','unbind'),(6,'product','all'),(6,'product','browse'),(6,'product','build'),(6,'product','doc'),(6,'product','dynamic'),(6,'product','index'),(6,'product','project'),(6,'product','roadmap'),(6,'product','view'),(6,'productplan','browse'),(6,'productplan','view'),(6,'project','activate'),(6,'project','all'),(6,'project','batchedit'),(6,'project','batchUnlinkStory'),(6,'project','browse'),(6,'project','bug'),(6,'project','build'),(6,'project','burn'),(6,'project','close'),(6,'project','computeBurn'),(6,'project','create'),(6,'project','delete'),(6,'project','doc'),(6,'project','dynamic'),(6,'project','edit'),(6,'project','grouptask'),(6,'project','importBug'),(6,'project','importPlanStories'),(6,'project','importtask'),(6,'project','index'),(6,'project','kanban'),(6,'project','linkStory'),(6,'project','manageMembers'),(6,'project','manageProducts'),(6,'project','order'),(6,'project','putoff'),(6,'project','start'),(6,'project','story'),(6,'project','suspend'),(6,'project','task'),(6,'project','team'),(6,'project','testtask'),(6,'project','tree'),(6,'project','treestory'),(6,'project','treetask'),(6,'project','unlinkMember'),(6,'project','unlinkStory'),(6,'project','updateOrder'),(6,'project','view'),(6,'qa','index'),(6,'release','browse'),(6,'release','export'),(6,'release','view'),(6,'repo','blame'),(6,'repo','browse'),(6,'repo','diff'),(6,'repo','download'),(6,'repo','log'),(6,'repo','revision'),(6,'repo','showSyncCommit'),(6,'repo','view'),(6,'report','bugAssign'),(6,'report','bugCreate'),(6,'report','index'),(6,'report','productSummary'),(6,'report','projectDeviation'),(6,'report','workload'),(6,'search','buildForm'),(6,'search','buildQuery'),(6,'search','deleteQuery'),(6,'search','saveQuery'),(6,'search','select'),(6,'story','bugs'),(6,'story','cases'),(6,'story','export'),(6,'story','report'),(6,'story','tasks'),(6,'story','view'),(6,'story','zeroCase'),(6,'svn','apiSync'),(6,'svn','cat'),(6,'svn','diff'),(6,'task','activate'),(6,'task','assignTo'),(6,'task','batchAssignTo'),(6,'task','batchChangeModule'),(6,'task','batchClose'),(6,'task','batchCreate'),(6,'task','batchEdit'),(6,'task','cancel'),(6,'task','close'),(6,'task','confirmStoryChange'),(6,'task','create'),(6,'task','delete'),(6,'task','deleteEstimate'),(6,'task','edit'),(6,'task','editEstimate'),(6,'task','export'),(6,'task','finish'),(6,'task','pause'),(6,'task','recordEstimate'),(6,'task','report'),(6,'task','restart'),(6,'task','start'),(6,'task','view'),(6,'testcase','browse'),(6,'testcase','bugs'),(6,'testcase','export'),(6,'testcase','groupCase'),(6,'testcase','index'),(6,'testcase','view'),(6,'testsuite','browse'),(6,'testsuite','index'),(6,'testsuite','view'),(6,'testtask','browse'),(6,'testtask','cases'),(6,'testtask','create'),(6,'testtask','edit'),(6,'testtask','groupCase'),(6,'testtask','index'),(6,'testtask','results'),(6,'testtask','view'),(6,'todo','activate'),(6,'todo','assignTo'),(6,'todo','batchClose'),(6,'todo','batchCreate'),(6,'todo','batchEdit'),(6,'todo','batchFinish'),(6,'todo','close'),(6,'todo','create'),(6,'todo','createCycle'),(6,'todo','delete'),(6,'todo','edit'),(6,'todo','export'),(6,'todo','finish'),(6,'todo','import2Today'),(6,'todo','view'),(6,'tree','browse'),(6,'tree','browseTask'),(6,'tree','delete'),(6,'tree','edit'),(6,'tree','fix'),(6,'tree','manageChild'),(6,'tree','updateOrder'),(6,'user','bug'),(6,'user','deleteContacts'),(6,'user','dynamic'),(6,'user','manageContacts'),(6,'user','profile'),(6,'user','project'),(6,'user','story'),(6,'user','task'),(6,'user','testCase'),(6,'user','testTask'),(6,'user','todo'),(6,'user','view'),(7,'action','editComment'),(7,'action','hideAll'),(7,'action','hideOne'),(7,'action','trash'),(7,'action','undelete'),(7,'admin','index'),(7,'branch','delete'),(7,'branch','manage'),(7,'branch','sort'),(7,'bug','activate'),(7,'bug','assignTo'),(7,'bug','batchActivate'),(7,'bug','batchChangeModule'),(7,'bug','batchClose'),(7,'bug','batchConfirm'),(7,'bug','batchCreate'),(7,'bug','batchEdit'),(7,'bug','batchResolve'),(7,'bug','browse'),(7,'bug','close'),(7,'bug','confirmBug'),(7,'bug','confirmStoryChange'),(7,'bug','create'),(7,'bug','delete'),(7,'bug','deleteTemplate'),(7,'bug','edit'),(7,'bug','export'),(7,'bug','index'),(7,'bug','linkBugs'),(7,'bug','report'),(7,'bug','resolve'),(7,'bug','saveTemplate'),(7,'bug','unlinkBug'),(7,'bug','view'),(7,'build','view'),(7,'company','browse'),(7,'company','dynamic'),(7,'company','index'),(7,'company','view'),(7,'doc','allLibs'),(7,'doc','browse'),(7,'doc','create'),(7,'doc','createLib'),(7,'doc','delete'),(7,'doc','deleteFile'),(7,'doc','deleteLib'),(7,'doc','edit'),(7,'doc','editLib'),(7,'doc','index'),(7,'doc','objectLibs'),(7,'doc','showFiles'),(7,'doc','view'),(7,'extension','browse'),(7,'extension','obtain'),(7,'extension','structure'),(7,'file','delete'),(7,'file','download'),(7,'file','edit'),(7,'file','uploadImages'),(7,'git','apiSync'),(7,'git','cat'),(7,'git','diff'),(7,'group','browse'),(7,'index','index'),(7,'misc','ping'),(7,'my','bug'),(7,'my','calendar'),(7,'my','changePassword'),(7,'my','deleteContacts'),(7,'my','dynamic'),(7,'my','editProfile'),(7,'my','index'),(7,'my','manageContacts'),(7,'my','profile'),(7,'my','project'),(7,'my','story'),(7,'my','task'),(7,'my','testCase'),(7,'my','testTask'),(7,'my','todo'),(7,'my','unbind'),(7,'product','all'),(7,'product','batchEdit'),(7,'product','browse'),(7,'product','build'),(7,'product','close'),(7,'product','create'),(7,'product','delete'),(7,'product','doc'),(7,'product','dynamic'),(7,'product','edit'),(7,'product','index'),(7,'product','order'),(7,'product','project'),(7,'product','roadmap'),(7,'product','updateOrder'),(7,'product','view'),(7,'productplan','batchUnlinkBug'),(7,'productplan','batchUnlinkStory'),(7,'productplan','browse'),(7,'productplan','create'),(7,'productplan','delete'),(7,'productplan','edit'),(7,'productplan','linkBug'),(7,'productplan','linkStory'),(7,'productplan','unlinkBug'),(7,'productplan','unlinkStory'),(7,'productplan','view'),(7,'project','all'),(7,'project','browse'),(7,'project','bug'),(7,'project','build'),(7,'project','burn'),(7,'project','doc'),(7,'project','dynamic'),(7,'project','grouptask'),(7,'project','importPlanStories'),(7,'project','index'),(7,'project','kanban'),(7,'project','linkStory'),(7,'project','manageProducts'),(7,'project','story'),(7,'project','task'),(7,'project','team'),(7,'project','testtask'),(7,'project','tree'),(7,'project','treestory'),(7,'project','treetask'),(7,'project','unlinkStory'),(7,'project','view'),(7,'qa','index'),(7,'release','batchUnlinkBug'),(7,'release','batchUnlinkStory'),(7,'release','browse'),(7,'release','changeStatus'),(7,'release','create'),(7,'release','delete'),(7,'release','edit'),(7,'release','export'),(7,'release','linkBug'),(7,'release','linkStory'),(7,'release','unlinkBug'),(7,'release','unlinkStory'),(7,'release','view'),(7,'repo','blame'),(7,'repo','browse'),(7,'repo','diff'),(7,'repo','download'),(7,'repo','log'),(7,'repo','revision'),(7,'repo','showSyncCommit'),(7,'repo','view'),(7,'report','bugAssign'),(7,'report','bugCreate'),(7,'report','index'),(7,'report','productSummary'),(7,'report','projectDeviation'),(7,'report','workload'),(7,'search','buildForm'),(7,'search','buildQuery'),(7,'search','deleteQuery'),(7,'search','saveQuery'),(7,'search','select'),(7,'story','activate'),(7,'story','assignTo'),(7,'story','batchAssignTo'),(7,'story','batchChangeBranch'),(7,'story','batchChangeModule'),(7,'story','batchChangePlan'),(7,'story','batchChangeStage'),(7,'story','batchClose'),(7,'story','batchCreate'),(7,'story','batchEdit'),(7,'story','batchReview'),(7,'story','bugs'),(7,'story','cases'),(7,'story','change'),(7,'story','close'),(7,'story','create'),(7,'story','delete'),(7,'story','edit'),(7,'story','export'),(7,'story','linkStory'),(7,'story','report'),(7,'story','review'),(7,'story','tasks'),(7,'story','unlinkStory'),(7,'story','view'),(7,'story','zeroCase'),(7,'svn','apiSync'),(7,'svn','cat'),(7,'svn','diff'),(7,'task','confirmStoryChange'),(7,'task','deleteEstimate'),(7,'task','editEstimate'),(7,'task','export'),(7,'task','recordEstimate'),(7,'task','report'),(7,'task','view'),(7,'testcase','browse'),(7,'testcase','bugs'),(7,'testcase','createBug'),(7,'testcase','export'),(7,'testcase','groupCase'),(7,'testcase','importFromLib'),(7,'testcase','index'),(7,'testcase','view'),(7,'testsuite','batchCreateCase'),(7,'testsuite','browse'),(7,'testsuite','createCase'),(7,'testsuite','createLib'),(7,'testsuite','exportTemplet'),(7,'testsuite','import'),(7,'testsuite','index'),(7,'testsuite','library'),(7,'testsuite','libView'),(7,'testsuite','showImport'),(7,'testsuite','view'),(7,'testtask','browse'),(7,'testtask','cases'),(7,'testtask','groupCase'),(7,'testtask','index'),(7,'testtask','results'),(7,'testtask','view'),(7,'todo','activate'),(7,'todo','assignTo'),(7,'todo','batchClose'),(7,'todo','batchCreate'),(7,'todo','batchEdit'),(7,'todo','batchFinish'),(7,'todo','close'),(7,'todo','create'),(7,'todo','createCycle'),(7,'todo','delete'),(7,'todo','edit'),(7,'todo','export'),(7,'todo','finish'),(7,'todo','import2Today'),(7,'todo','view'),(7,'tree','browse'),(7,'tree','browseTask'),(7,'tree','delete'),(7,'tree','edit'),(7,'tree','fix'),(7,'tree','manageChild'),(7,'tree','updateOrder'),(7,'user','bug'),(7,'user','deleteContacts'),(7,'user','dynamic'),(7,'user','manageContacts'),(7,'user','profile'),(7,'user','project'),(7,'user','story'),(7,'user','task'),(7,'user','testCase'),(7,'user','testTask'),(7,'user','todo'),(7,'user','view'),(8,'action','editComment'),(8,'action','hideAll'),(8,'action','hideOne'),(8,'action','trash'),(8,'action','undelete'),(8,'admin','index'),(8,'bug','activate'),(8,'bug','assignTo'),(8,'bug','batchActivate'),(8,'bug','batchAssignTo'),(8,'bug','batchChangeModule'),(8,'bug','batchClose'),(8,'bug','batchConfirm'),(8,'bug','batchCreate'),(8,'bug','batchEdit'),(8,'bug','batchResolve'),(8,'bug','browse'),(8,'bug','close'),(8,'bug','confirmBug'),(8,'bug','confirmStoryChange'),(8,'bug','create'),(8,'bug','delete'),(8,'bug','deleteTemplate'),(8,'bug','edit'),(8,'bug','export'),(8,'bug','index'),(8,'bug','linkBugs'),(8,'bug','report'),(8,'bug','resolve'),(8,'bug','saveTemplate'),(8,'bug','unlinkBug'),(8,'bug','view'),(8,'build','create'),(8,'build','delete'),(8,'build','edit'),(8,'build','view'),(8,'company','browse'),(8,'company','dynamic'),(8,'company','index'),(8,'company','view'),(8,'doc','allLibs'),(8,'doc','browse'),(8,'doc','create'),(8,'doc','createLib'),(8,'doc','delete'),(8,'doc','deleteFile'),(8,'doc','deleteLib'),(8,'doc','edit'),(8,'doc','editLib'),(8,'doc','index'),(8,'doc','objectLibs'),(8,'doc','showFiles'),(8,'doc','view'),(8,'extension','browse'),(8,'extension','obtain'),(8,'extension','structure'),(8,'file','delete'),(8,'file','download'),(8,'file','edit'),(8,'file','uploadImages'),(8,'git','apiSync'),(8,'git','cat'),(8,'git','diff'),(8,'group','browse'),(8,'index','index'),(8,'misc','ping'),(8,'my','bug'),(8,'my','calendar'),(8,'my','changePassword'),(8,'my','deleteContacts'),(8,'my','dynamic'),(8,'my','editProfile'),(8,'my','index'),(8,'my','manageContacts'),(8,'my','profile'),(8,'my','project'),(8,'my','story'),(8,'my','task'),(8,'my','testCase'),(8,'my','testTask'),(8,'my','todo'),(8,'my','unbind'),(8,'product','all'),(8,'product','browse'),(8,'product','build'),(8,'product','doc'),(8,'product','dynamic'),(8,'product','index'),(8,'product','project'),(8,'product','roadmap'),(8,'product','view'),(8,'productplan','browse'),(8,'productplan','view'),(8,'project','all'),(8,'project','bug'),(8,'project','build'),(8,'project','burn'),(8,'project','doc'),(8,'project','dynamic'),(8,'project','grouptask'),(8,'project','importBug'),(8,'project','importtask'),(8,'project','index'),(8,'project','kanban'),(8,'project','story'),(8,'project','task'),(8,'project','team'),(8,'project','testtask'),(8,'project','tree'),(8,'project','treestory'),(8,'project','treetask'),(8,'project','view'),(8,'qa','index'),(8,'release','browse'),(8,'release','export'),(8,'release','view'),(8,'repo','blame'),(8,'repo','browse'),(8,'repo','diff'),(8,'repo','download'),(8,'repo','log'),(8,'repo','revision'),(8,'repo','showSyncCommit'),(8,'repo','view'),(8,'report','bugAssign'),(8,'report','bugCreate'),(8,'report','index'),(8,'report','productSummary'),(8,'report','projectDeviation'),(8,'report','workload'),(8,'search','buildForm'),(8,'search','buildQuery'),(8,'search','deleteQuery'),(8,'search','saveQuery'),(8,'search','select'),(8,'story','bugs'),(8,'story','cases'),(8,'story','export'),(8,'story','report'),(8,'story','tasks'),(8,'story','view'),(8,'story','zeroCase'),(8,'svn','apiSync'),(8,'svn','cat'),(8,'svn','diff'),(8,'task','activate'),(8,'task','assignTo'),(8,'task','batchAssignTo'),(8,'task','batchChangeModule'),(8,'task','batchClose'),(8,'task','batchCreate'),(8,'task','batchEdit'),(8,'task','cancel'),(8,'task','close'),(8,'task','confirmStoryChange'),(8,'task','create'),(8,'task','delete'),(8,'task','deleteEstimate'),(8,'task','edit'),(8,'task','editEstimate'),(8,'task','export'),(8,'task','finish'),(8,'task','pause'),(8,'task','recordEstimate'),(8,'task','report'),(8,'task','restart'),(8,'task','start'),(8,'task','view'),(8,'testcase','batchCaseTypeChange'),(8,'testcase','batchChangeModule'),(8,'testcase','batchConfirmStoryChange'),(8,'testcase','batchCreate'),(8,'testcase','batchDelete'),(8,'testcase','batchEdit'),(8,'testcase','batchReview'),(8,'testcase','browse'),(8,'testcase','bugs'),(8,'testcase','confirmChange'),(8,'testcase','confirmStoryChange'),(8,'testcase','create'),(8,'testcase','createBug'),(8,'testcase','delete'),(8,'testcase','edit'),(8,'testcase','export'),(8,'testcase','exportTemplet'),(8,'testcase','groupCase'),(8,'testcase','import'),(8,'testcase','importFromLib'),(8,'testcase','index'),(8,'testcase','linkCases'),(8,'testcase','review'),(8,'testcase','showImport'),(8,'testcase','unlinkCase'),(8,'testcase','view'),(8,'testreport','browse'),(8,'testreport','create'),(8,'testreport','delete'),(8,'testreport','edit'),(8,'testreport','view'),(8,'testsuite','batchCreateCase'),(8,'testsuite','browse'),(8,'testsuite','create'),(8,'testsuite','createCase'),(8,'testsuite','createLib'),(8,'testsuite','delete'),(8,'testsuite','edit'),(8,'testsuite','exportTemplet'),(8,'testsuite','import'),(8,'testsuite','index'),(8,'testsuite','library'),(8,'testsuite','libView'),(8,'testsuite','linkCase'),(8,'testsuite','showImport'),(8,'testsuite','unlinkCase'),(8,'testsuite','view'),(8,'testtask','activate'),(8,'testtask','batchAssign'),(8,'testtask','batchRun'),(8,'testtask','block'),(8,'testtask','browse'),(8,'testtask','cases'),(8,'testtask','close'),(8,'testtask','create'),(8,'testtask','delete'),(8,'testtask','edit'),(8,'testtask','groupCase'),(8,'testtask','index'),(8,'testtask','linkcase'),(8,'testtask','report'),(8,'testtask','results'),(8,'testtask','runcase'),(8,'testtask','start'),(8,'testtask','unlinkcase'),(8,'testtask','view'),(8,'todo','activate'),(8,'todo','assignTo'),(8,'todo','batchClose'),(8,'todo','batchCreate'),(8,'todo','batchEdit'),(8,'todo','batchFinish'),(8,'todo','close'),(8,'todo','create'),(8,'todo','createCycle'),(8,'todo','delete'),(8,'todo','edit'),(8,'todo','export'),(8,'todo','finish'),(8,'todo','import2Today'),(8,'todo','view'),(8,'tree','browse'),(8,'tree','browseTask'),(8,'tree','delete'),(8,'tree','edit'),(8,'tree','fix'),(8,'tree','manageChild'),(8,'tree','updateOrder'),(8,'user','bug'),(8,'user','deleteContacts'),(8,'user','dynamic'),(8,'user','manageContacts'),(8,'user','profile'),(8,'user','project'),(8,'user','story'),(8,'user','task'),(8,'user','testCase'),(8,'user','testTask'),(8,'user','todo'),(8,'user','view'),(9,'action','editComment'),(9,'action','hideAll'),(9,'action','hideOne'),(9,'action','trash'),(9,'action','undelete'),(9,'admin','index'),(9,'bug','browse'),(9,'bug','export'),(9,'bug','index'),(9,'bug','report'),(9,'bug','view'),(9,'build','view'),(9,'company','browse'),(9,'company','dynamic'),(9,'company','edit'),(9,'company','index'),(9,'company','view'),(9,'dept','browse'),(9,'dept','delete'),(9,'dept','manageChild'),(9,'dept','updateOrder'),(9,'doc','allLibs'),(9,'doc','browse'),(9,'doc','create'),(9,'doc','createLib'),(9,'doc','delete'),(9,'doc','deleteFile'),(9,'doc','deleteLib'),(9,'doc','edit'),(9,'doc','editLib'),(9,'doc','index'),(9,'doc','objectLibs'),(9,'doc','showFiles'),(9,'doc','view'),(9,'extension','browse'),(9,'extension','obtain'),(9,'extension','structure'),(9,'file','delete'),(9,'file','download'),(9,'file','edit'),(9,'file','uploadImages'),(9,'git','apiSync'),(9,'git','cat'),(9,'git','diff'),(9,'group','browse'),(9,'index','index'),(9,'misc','ping'),(9,'my','bug'),(9,'my','calendar'),(9,'my','changePassword'),(9,'my','deleteContacts'),(9,'my','dynamic'),(9,'my','editProfile'),(9,'my','index'),(9,'my','manageContacts'),(9,'my','profile'),(9,'my','project'),(9,'my','story'),(9,'my','task'),(9,'my','testCase'),(9,'my','testTask'),(9,'my','todo'),(9,'my','unbind'),(9,'product','all'),(9,'product','browse'),(9,'product','build'),(9,'product','doc'),(9,'product','dynamic'),(9,'product','index'),(9,'product','project'),(9,'product','roadmap'),(9,'product','view'),(9,'productplan','browse'),(9,'productplan','view'),(9,'project','all'),(9,'project','browse'),(9,'project','bug'),(9,'project','build'),(9,'project','burn'),(9,'project','computeBurn'),(9,'project','doc'),(9,'project','dynamic'),(9,'project','grouptask'),(9,'project','index'),(9,'project','kanban'),(9,'project','story'),(9,'project','task'),(9,'project','team'),(9,'project','tree'),(9,'project','treestory'),(9,'project','treetask'),(9,'project','view'),(9,'qa','index'),(9,'release','browse'),(9,'release','export'),(9,'release','view'),(9,'repo','blame'),(9,'repo','browse'),(9,'repo','diff'),(9,'repo','download'),(9,'repo','log'),(9,'repo','revision'),(9,'repo','showSyncCommit'),(9,'repo','view'),(9,'report','bugAssign'),(9,'report','bugCreate'),(9,'report','index'),(9,'report','productSummary'),(9,'report','projectDeviation'),(9,'report','workload'),(9,'search','buildForm'),(9,'search','buildQuery'),(9,'search','deleteQuery'),(9,'search','saveQuery'),(9,'search','select'),(9,'story','bugs'),(9,'story','cases'),(9,'story','export'),(9,'story','report'),(9,'story','review'),(9,'story','tasks'),(9,'story','view'),(9,'story','zeroCase'),(9,'svn','apiSync'),(9,'svn','cat'),(9,'svn','diff'),(9,'task','deleteEstimate'),(9,'task','editEstimate'),(9,'task','export'),(9,'task','recordEstimate'),(9,'task','report'),(9,'task','view'),(9,'testcase','browse'),(9,'testcase','bugs'),(9,'testcase','export'),(9,'testcase','groupCase'),(9,'testcase','index'),(9,'testcase','view'),(9,'testsuite','browse'),(9,'testsuite','index'),(9,'testsuite','view'),(9,'testtask','browse'),(9,'testtask','cases'),(9,'testtask','groupCase'),(9,'testtask','index'),(9,'testtask','results'),(9,'testtask','view'),(9,'todo','activate'),(9,'todo','assignTo'),(9,'todo','batchClose'),(9,'todo','batchCreate'),(9,'todo','batchEdit'),(9,'todo','batchFinish'),(9,'todo','close'),(9,'todo','create'),(9,'todo','createCycle'),(9,'todo','delete'),(9,'todo','edit'),(9,'todo','export'),(9,'todo','finish'),(9,'todo','import2Today'),(9,'todo','view'),(9,'user','batchCreate'),(9,'user','batchEdit'),(9,'user','bug'),(9,'user','create'),(9,'user','delete'),(9,'user','deleteContacts'),(9,'user','dynamic'),(9,'user','edit'),(9,'user','manageContacts'),(9,'user','profile'),(9,'user','project'),(9,'user','story'),(9,'user','task'),(9,'user','testCase'),(9,'user','testTask'),(9,'user','todo'),(9,'user','unbind'),(9,'user','unlock'),(9,'user','view'),(10,'action','editComment'),(10,'bug','activate'),(10,'bug','batchChangeModule'),(10,'bug','browse'),(10,'bug','close'),(10,'bug','create'),(10,'bug','edit'),(10,'bug','index'),(10,'bug','linkBugs'),(10,'bug','report'),(10,'bug','unlinkBug'),(10,'bug','view'),(10,'build','view'),(10,'company','browse'),(10,'company','dynamic'),(10,'company','index'),(10,'company','view'),(10,'doc','allLibs'),(10,'doc','browse'),(10,'doc','index'),(10,'doc','objectLibs'),(10,'doc','showFiles'),(10,'doc','view'),(10,'file','download'),(10,'index','index'),(10,'misc','ping'),(10,'my','calendar'),(10,'my','changePassword'),(10,'my','dynamic'),(10,'my','editProfile'),(10,'my','index'),(10,'my','profile'),(10,'my','task'),(10,'my','todo'),(10,'my','unbind'),(10,'product','all'),(10,'product','browse'),(10,'product','build'),(10,'product','doc'),(10,'product','dynamic'),(10,'product','index'),(10,'product','roadmap'),(10,'product','view'),(10,'productplan','browse'),(10,'productplan','view'),(10,'project','all'),(10,'project','browse'),(10,'project','bug'),(10,'project','build'),(10,'project','burn'),(10,'project','doc'),(10,'project','dynamic'),(10,'project','grouptask'),(10,'project','index'),(10,'project','kanban'),(10,'project','story'),(10,'project','task'),(10,'project','team'),(10,'project','testtask'),(10,'project','tree'),(10,'project','treestory'),(10,'project','treetask'),(10,'project','view'),(10,'qa','index'),(10,'release','browse'),(10,'release','view'),(10,'repo','blame'),(10,'repo','browse'),(10,'repo','diff'),(10,'repo','download'),(10,'repo','log'),(10,'repo','revision'),(10,'repo','showSyncCommit'),(10,'repo','view'),(10,'report','bugAssign'),(10,'report','bugCreate'),(10,'report','index'),(10,'report','productSummary'),(10,'report','projectDeviation'),(10,'report','workload'),(10,'search','buildForm'),(10,'search','buildQuery'),(10,'search','deleteQuery'),(10,'search','saveQuery'),(10,'story','bugs'),(10,'story','cases'),(10,'story','tasks'),(10,'story','view'),(10,'task','deleteEstimate'),(10,'task','editEstimate'),(10,'task','recordEstimate'),(10,'task','view'),(10,'testsuite','browse'),(10,'testsuite','index'),(10,'testsuite','view'),(10,'todo','activate'),(10,'todo','assignTo'),(10,'todo','batchClose'),(10,'todo','batchCreate'),(10,'todo','batchEdit'),(10,'todo','batchFinish'),(10,'todo','close'),(10,'todo','create'),(10,'todo','createCycle'),(10,'todo','delete'),(10,'todo','edit'),(10,'todo','export'),(10,'todo','finish'),(10,'todo','import2Today'),(10,'todo','view'),(10,'user','bug'),(10,'user','dynamic'),(10,'user','profile'),(10,'user','project'),(10,'user','story'),(10,'user','task'),(10,'user','testCase'),(10,'user','testTask'),(10,'user','todo'),(10,'user','view'),(11,'bug','browse'),(11,'bug','index'),(11,'bug','report'),(11,'bug','view'),(11,'build','view'),(11,'company','browse'),(11,'company','dynamic'),(11,'company','index'),(11,'company','view'),(11,'doc','allLibs'),(11,'doc','browse'),(11,'doc','index'),(11,'doc','objectLibs'),(11,'doc','showFiles'),(11,'doc','view'),(11,'file','download'),(11,'git','cat'),(11,'git','diff'),(11,'group','browse'),(11,'index','index'),(11,'misc','ping'),(11,'my','index'),(11,'product','all'),(11,'product','browse'),(11,'product','build'),(11,'product','doc'),(11,'product','dynamic'),(11,'product','index'),(11,'product','roadmap'),(11,'product','view'),(11,'productplan','browse'),(11,'productplan','view'),(11,'project','all'),(11,'project','browse'),(11,'project','bug'),(11,'project','build'),(11,'project','burn'),(11,'project','doc'),(11,'project','dynamic'),(11,'project','grouptask'),(11,'project','index'),(11,'project','kanban'),(11,'project','story'),(11,'project','task'),(11,'project','team'),(11,'project','testtask'),(11,'project','tree'),(11,'project','treestory'),(11,'project','treetask'),(11,'project','view'),(11,'qa','index'),(11,'release','browse'),(11,'release','view'),(11,'report','bugAssign'),(11,'report','bugCreate'),(11,'report','index'),(11,'report','productSummary'),(11,'report','projectDeviation'),(11,'report','workload'),(11,'search','buildForm'),(11,'search','buildQuery'),(11,'story','bugs'),(11,'story','cases'),(11,'story','tasks'),(11,'story','view'),(11,'svn','cat'),(11,'svn','diff'),(11,'task','recordEstimate'),(11,'task','view'),(11,'testcase','browse'),(11,'testcase','bugs'),(11,'testcase','groupCase'),(11,'testcase','index'),(11,'testcase','view'),(11,'testsuite','browse'),(11,'testsuite','index'),(11,'testsuite','view'),(11,'testtask','browse'),(11,'testtask','cases'),(11,'testtask','groupCase'),(11,'testtask','index'),(11,'testtask','results'),(11,'testtask','view'),(11,'user','bug'),(11,'user','dynamic'),(11,'user','profile'),(11,'user','project'),(11,'user','story'),(11,'user','task'),(11,'user','testCase'),(11,'user','testTask'),(11,'user','todo'),(11,'user','view'),(12,'my','limited');
/*!40000 ALTER TABLE `zt_grouppriv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_history`
--

DROP TABLE IF EXISTS `zt_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_history` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `action` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `field` varchar(30) NOT NULL DEFAULT '',
  `old` text NOT NULL,
  `new` text NOT NULL,
  `diff` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `action` (`action`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_history`
--

LOCK TABLES `zt_history` WRITE;
/*!40000 ALTER TABLE `zt_history` DISABLE KEYS */;
INSERT INTO `zt_history` VALUES (1,14,'version','1','2',''),(2,14,'spec','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />','001- <del>作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。<br /></del>\n001+ <ins>作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。<br /></ins>'),(3,41,'consumed','0','1',''),(4,41,'status','wait','doing',''),(6,42,'consumed','1','7',''),(7,42,'left','10','0',''),(8,42,'assignedTo','dev1','projectManager',''),(9,42,'status','doing','done',''),(10,42,'finishedBy','','dev1',''),(11,42,'finishedDate','','2012-06-05 10:38:00',''),(13,43,'consumed','0','6',''),(14,43,'left','8','0',''),(15,43,'assignedTo','dev1','projectManager',''),(16,43,'status','wait','done',''),(17,43,'finishedBy','','dev1',''),(18,43,'finishedDate','','2012-06-05 10:39:14',''),(20,44,'canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(21,45,'canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(22,46,'canceledDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(23,47,'closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(24,48,'closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(25,49,'closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(26,50,'closedDate','2012-06-05 10:41:12','2012-06-05 10:41:20',''),(27,51,'status','wait','done',''),(28,51,'consumed','0','8',''),(29,51,'left','10','0',''),(30,51,'finishedBy','','dev1',''),(31,51,'finishedDate','','2012-06-05 10:41:20',''),(32,52,'finishedDate','2012-06-05 10:38:00','2012-06-05 10:41:20',''),(33,55,'status','closed','done',''),(34,55,'consumed','0','5',''),(35,55,'left','8','0',''),(36,55,'finishedBy','','dev2',''),(37,55,'closedBy','dev1','',''),(38,55,'closedReason','done','',''),(39,55,'finishedDate','','2012-06-05 10:42:56',''),(40,55,'closedDate','2012-06-05 10:41:20','',''),(41,56,'status','closed','done',''),(42,56,'consumed','0','8',''),(43,56,'left','8','0',''),(44,56,'finishedBy','','dev2',''),(45,56,'closedBy','dev1','',''),(46,56,'closedReason','done','',''),(47,56,'finishedDate','','2012-06-05 10:42:56',''),(48,56,'closedDate','2012-06-05 10:41:20','',''),(49,59,'status','closed','done',''),(50,59,'consumed','0','5',''),(51,59,'left','8','0',''),(52,59,'finishedBy','dev1','dev3',''),(53,59,'closedBy','dev1','',''),(54,59,'closedReason','done','',''),(55,59,'finishedDate','','2012-06-05 10:43:32',''),(56,59,'closedDate','2012-06-05 10:41:20','',''),(57,60,'status','closed','done',''),(58,60,'consumed','0','5',''),(59,60,'left','8','0',''),(60,60,'finishedBy','dev1','dev3',''),(61,60,'closedBy','dev1','',''),(62,60,'closedReason','done','',''),(63,60,'finishedDate','','2012-06-05 10:43:32',''),(64,60,'closedDate','2012-06-05 10:41:20','',''),(65,82,'title','首页的测试用例','售后服务的测试用例','001- <del>首页的测试用例</del>\n001+ <ins>售后服务的测试用例</ins>'),(66,82,'story','1','4',''),(67,93,'build','','trunk','');
/*!40000 ALTER TABLE `zt_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_holiday`
--

DROP TABLE IF EXISTS `zt_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_holiday` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `type` enum('holiday','working') NOT NULL DEFAULT 'holiday',
  `desc` text NOT NULL,
  `year` char(4) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_holiday`
--

LOCK TABLES `zt_holiday` WRITE;
/*!40000 ALTER TABLE `zt_holiday` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_chat`
--

DROP TABLE IF EXISTS `zt_im_chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_chat` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` char(40) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT 'group',
  `admins` varchar(255) NOT NULL DEFAULT '',
  `committers` varchar(255) NOT NULL DEFAULT '',
  `subject` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `public` enum('0','1') NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastActiveTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dismissDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `gid` (`gid`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `public` (`public`),
  KEY `createdBy` (`createdBy`),
  KEY `editedBy` (`editedBy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_chat`
--

LOCK TABLES `zt_im_chat` WRITE;
/*!40000 ALTER TABLE `zt_im_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_chatuser`
--

DROP TABLE IF EXISTS `zt_im_chatuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_chatuser` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `cgid` char(40) NOT NULL DEFAULT '',
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `order` smallint(5) NOT NULL DEFAULT '0',
  `star` enum('0','1') NOT NULL DEFAULT '0',
  `hide` enum('0','1') NOT NULL DEFAULT '0',
  `mute` enum('0','1') NOT NULL DEFAULT '0',
  `freeze` enum('0','1') NOT NULL DEFAULT '0',
  `join` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `chatuser` (`cgid`,`user`),
  KEY `cgid` (`cgid`),
  KEY `user` (`user`),
  KEY `order` (`order`),
  KEY `star` (`star`),
  KEY `hide` (`hide`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_chatuser`
--

LOCK TABLES `zt_im_chatuser` WRITE;
/*!40000 ALTER TABLE `zt_im_chatuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_chatuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_client`
--

DROP TABLE IF EXISTS `zt_im_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_client` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `version` char(30) NOT NULL DEFAULT '',
  `desc` varchar(100) NOT NULL DEFAULT '',
  `changeLog` text NOT NULL,
  `strategy` varchar(10) NOT NULL DEFAULT '',
  `downloads` text NOT NULL,
  `createdDate` datetime NOT NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `status` enum('released','wait') NOT NULL DEFAULT 'wait',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_client`
--

LOCK TABLES `zt_im_client` WRITE;
/*!40000 ALTER TABLE `zt_im_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_conference`
--

DROP TABLE IF EXISTS `zt_im_conference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_conference` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` char(24) NOT NULL DEFAULT '',
  `cgid` char(40) NOT NULL DEFAULT '',
  `status` enum('closed','open') NOT NULL DEFAULT 'closed',
  `participants` text NOT NULL,
  `openedBy` mediumint(8) NOT NULL DEFAULT '0',
  `openedDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_conference`
--

LOCK TABLES `zt_im_conference` WRITE;
/*!40000 ALTER TABLE `zt_im_conference` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_conference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_conferenceaction`
--

DROP TABLE IF EXISTS `zt_im_conferenceaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_conferenceaction` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` char(24) NOT NULL DEFAULT '',
  `type` enum('create','join','leave','close') NOT NULL DEFAULT 'create',
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_conferenceaction`
--

LOCK TABLES `zt_im_conferenceaction` WRITE;
/*!40000 ALTER TABLE `zt_im_conferenceaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_conferenceaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_message`
--

DROP TABLE IF EXISTS `zt_im_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_message` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` char(40) NOT NULL DEFAULT '',
  `cgid` char(40) NOT NULL DEFAULT '',
  `user` varchar(30) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order` bigint(8) unsigned NOT NULL,
  `type` enum('normal','broadcast','notify') NOT NULL DEFAULT 'normal',
  `content` text NOT NULL,
  `contentType` enum('text','plain','emotion','image','file','object','code') NOT NULL DEFAULT 'text',
  `data` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mgid` (`gid`),
  KEY `mcgid` (`cgid`),
  KEY `muser` (`user`),
  KEY `mtype` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_message`
--

LOCK TABLES `zt_im_message` WRITE;
/*!40000 ALTER TABLE `zt_im_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_messagestatus`
--

DROP TABLE IF EXISTS `zt_im_messagestatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_messagestatus` (
  `user` mediumint(8) NOT NULL DEFAULT '0',
  `message` int(11) unsigned NOT NULL,
  `status` enum('waiting','sent','readed','deleted') NOT NULL DEFAULT 'waiting',
  UNIQUE KEY `user` (`user`,`message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_messagestatus`
--

LOCK TABLES `zt_im_messagestatus` WRITE;
/*!40000 ALTER TABLE `zt_im_messagestatus` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_messagestatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_im_queue`
--

DROP TABLE IF EXISTS `zt_im_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_im_queue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(30) NOT NULL,
  `content` text NOT NULL,
  `addDate` datetime NOT NULL,
  `processDate` datetime NOT NULL,
  `result` text NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_im_queue`
--

LOCK TABLES `zt_im_queue` WRITE;
/*!40000 ALTER TABLE `zt_im_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_im_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_issue`
--

DROP TABLE IF EXISTS `zt_issue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_issue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `resolvedBy` varchar(30) NOT NULL,
  `PRJ` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `pri` char(30) NOT NULL,
  `severity` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `resolution` char(30) NOT NULL,
  `resolutionComment` text NOT NULL,
  `objectID` varchar(255) NOT NULL,
  `resolvedDate` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `activateBy` varchar(30) NOT NULL,
  `activateDate` date NOT NULL,
  `closeBy` varchar(30) NOT NULL,
  `closedDate` date NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedBy` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_issue`
--

LOCK TABLES `zt_issue` WRITE;
/*!40000 ALTER TABLE `zt_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_issue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_jenkins`
--

DROP TABLE IF EXISTS `zt_jenkins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_jenkins` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_jenkins`
--

LOCK TABLES `zt_jenkins` WRITE;
/*!40000 ALTER TABLE `zt_jenkins` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_jenkins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_job`
--

DROP TABLE IF EXISTS `zt_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_job` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `frame` varchar(20) NOT NULL,
  `jkHost` mediumint(8) unsigned NOT NULL,
  `jkJob` varchar(500) NOT NULL,
  `triggerType` varchar(255) NOT NULL,
  `svnDir` varchar(255) NOT NULL,
  `atDay` varchar(255) DEFAULT NULL,
  `atTime` varchar(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `lastExec` datetime DEFAULT NULL,
  `lastStatus` varchar(255) DEFAULT NULL,
  `lastTag` varchar(255) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_job`
--

LOCK TABLES `zt_job` WRITE;
/*!40000 ALTER TABLE `zt_job` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_lang`
--

DROP TABLE IF EXISTS `zt_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_lang` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `key` varchar(60) NOT NULL,
  `value` text NOT NULL,
  `system` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang` (`lang`,`module`,`section`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_lang`
--

LOCK TABLES `zt_lang` WRITE;
/*!40000 ALTER TABLE `zt_lang` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_lang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_log`
--

DROP TABLE IF EXISTS `zt_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL,
  `data` text NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `objectType` (`objectType`),
  KEY `obejctID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_log`
--

LOCK TABLES `zt_log` WRITE;
/*!40000 ALTER TABLE `zt_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_module`
--

DROP TABLE IF EXISTS `zt_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_module` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` char(60) NOT NULL DEFAULT '',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `path` char(255) NOT NULL DEFAULT '',
  `grade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `collector` text NOT NULL,
  `short` varchar(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `root` (`root`),
  KEY `type` (`type`),
  KEY `path` (`path`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_module`
--

LOCK TABLES `zt_module` WRITE;
/*!40000 ALTER TABLE `zt_module` DISABLE KEYS */;
INSERT INTO `zt_module` VALUES (1,1,0,'首页',0,',1,',1,10,'story','','','','0'),(2,1,0,'新闻中心',0,',2,',1,20,'story','','','','0'),(3,1,0,'成果展示',0,',3,',1,30,'story','','','','0'),(4,1,0,'售后服务',0,',4,',1,40,'story','','','','0'),(5,1,0,'诚聘英才',0,',5,',1,50,'story','','','','0'),(6,1,0,'合作洽谈',0,',6,',1,60,'story','','','','0'),(7,1,0,'关于我们',0,',7,',1,70,'story','','','','0'),(8,1,0,'首页',0,',8,',1,10,'bug','','','','0'),(9,1,0,'新闻中心',0,',9,',1,20,'bug','','','','0'),(10,1,0,'成果展示',0,',10,',1,30,'bug','','','','0'),(11,1,0,'售后服务',0,',11,',1,40,'bug','','','','0'),(12,1,0,'诚聘英才',0,',12,',1,50,'bug','','','','0'),(13,1,0,'合作洽谈',0,',13,',1,60,'bug','','','','0'),(14,1,0,'关于我们',0,',14,',1,70,'bug','','','','0');
/*!40000 ALTER TABLE `zt_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_notify`
--

DROP TABLE IF EXISTS `zt_notify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_notify` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(50) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `action` mediumint(9) NOT NULL,
  `toList` varchar(255) NOT NULL,
  `ccList` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `sendTime` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `failReason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_notify`
--

LOCK TABLES `zt_notify` WRITE;
/*!40000 ALTER TABLE `zt_notify` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_notify` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_oauth`
--

DROP TABLE IF EXISTS `zt_oauth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_oauth` (
  `account` varchar(30) NOT NULL,
  `openID` varchar(255) NOT NULL,
  `providerType` varchar(30) NOT NULL,
  `providerID` mediumint(8) unsigned NOT NULL,
  KEY `account` (`account`),
  KEY `providerType` (`providerType`),
  KEY `providerID` (`providerID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_oauth`
--

LOCK TABLES `zt_oauth` WRITE;
/*!40000 ALTER TABLE `zt_oauth` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_oauth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_planstory`
--

DROP TABLE IF EXISTS `zt_planstory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_planstory` (
  `plan` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL,
  `order` mediumint(9) NOT NULL,
  UNIQUE KEY `plan_story` (`plan`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_planstory`
--

LOCK TABLES `zt_planstory` WRITE;
/*!40000 ALTER TABLE `zt_planstory` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_planstory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_product`
--

DROP TABLE IF EXISTS `zt_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `line` mediumint(8) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'normal',
  `status` varchar(30) NOT NULL DEFAULT '',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `PO` varchar(30) NOT NULL,
  `QD` varchar(30) NOT NULL,
  `RD` varchar(30) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `createdVersion` varchar(20) NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acl` (`acl`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_product`
--

LOCK TABLES `zt_product` WRITE;
/*!40000 ALTER TABLE `zt_product` DISABLE KEYS */;
INSERT INTO `zt_product` VALUES (1,'公司企业网站建设','companyWebsite',0,'normal','normal','','建立公司企业网站，可以更好对外展示。<br />','productManager','testManager','productManager','open','','productManager','2012-06-05 09:57:07','',5,'0'),(2,'企业内部工时管理系统','workhourManage',0,'normal','normal','','','productManager','testManager','productManager','open','','productManager','2012-06-05 11:15:20','5.2.1',10,'0');
/*!40000 ALTER TABLE `zt_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_productplan`
--

DROP TABLE IF EXISTS `zt_productplan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_productplan` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `parent` mediumint(9) NOT NULL DEFAULT '0',
  `title` varchar(90) NOT NULL,
  `desc` text NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `order` text NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `end` (`end`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_productplan`
--

LOCK TABLES `zt_productplan` WRITE;
/*!40000 ALTER TABLE `zt_productplan` DISABLE KEYS */;
INSERT INTO `zt_productplan` VALUES (1,1,0,0,'1.0版本','开发出企业网站1.0版本。<br />','2000-01-01','2015-01-01','','0');
/*!40000 ALTER TABLE `zt_productplan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_project`
--

DROP TABLE IF EXISTS `zt_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_project` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `isCat` enum('1','0') NOT NULL DEFAULT '0',
  `catID` mediumint(8) unsigned NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'sprint',
  `parent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `days` smallint(5) unsigned NOT NULL,
  `status` varchar(10) NOT NULL,
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `statge` enum('1','2','3','4','5') NOT NULL DEFAULT '1',
  `pri` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `desc` text NOT NULL,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `openedVersion` varchar(20) NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `canceledBy` varchar(30) NOT NULL DEFAULT '',
  `canceledDate` datetime NOT NULL,
  `PO` varchar(30) NOT NULL DEFAULT '',
  `PM` varchar(30) NOT NULL DEFAULT '',
  `QD` varchar(30) NOT NULL DEFAULT '',
  `RD` varchar(30) NOT NULL DEFAULT '',
  `team` varchar(90) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `begin` (`begin`),
  KEY `end` (`end`),
  KEY `status` (`status`),
  KEY `acl` (`acl`),
  KEY `order` (`order`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_project`
--

LOCK TABLES `zt_project` WRITE;
/*!40000 ALTER TABLE `zt_project` DISABLE KEYS */;
INSERT INTO `zt_project` VALUES (1,'0',0,'sprint',0,'企业网站第一期','coWeb1','2012-06-05','2012-12-04',184,'done','','1','1','开发企业网站的基本雏形。<br />','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','productManager','projectManager','testManager','productManager','公司开发团队','open','',5,'0'),(2,'0',0,'sprint',0,'企业网站第二期','coWebsite2','2013-06-05','2014-06-04',365,'wait','','1','1','','','0000-00-00 00:00:00','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','productManager','projectManager','testManager','productManager','公司开发团队','open','',10,'0');
/*!40000 ALTER TABLE `zt_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_projectproduct`
--

DROP TABLE IF EXISTS `zt_projectproduct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_projectproduct` (
  `project` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `plan` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`project`,`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_projectproduct`
--

LOCK TABLES `zt_projectproduct` WRITE;
/*!40000 ALTER TABLE `zt_projectproduct` DISABLE KEYS */;
INSERT INTO `zt_projectproduct` VALUES (1,1,0,0),(2,1,0,0);
/*!40000 ALTER TABLE `zt_projectproduct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_projectspec`
--

DROP TABLE IF EXISTS `zt_projectspec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_projectspec` (
  `project` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `milestone` enum('0','1') NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  UNIQUE KEY `project` (`project`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_projectspec`
--

LOCK TABLES `zt_projectspec` WRITE;
/*!40000 ALTER TABLE `zt_projectspec` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_projectspec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_projectstory`
--

DROP TABLE IF EXISTS `zt_projectstory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_projectstory` (
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` smallint(6) NOT NULL DEFAULT '1',
  `order` smallint(6) unsigned NOT NULL,
  UNIQUE KEY `project` (`project`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_projectstory`
--

LOCK TABLES `zt_projectstory` WRITE;
/*!40000 ALTER TABLE `zt_projectstory` DISABLE KEYS */;
INSERT INTO `zt_projectstory` VALUES (1,1,4,1,0),(1,1,3,2,0),(1,1,2,1,0),(1,1,1,1,0);
/*!40000 ALTER TABLE `zt_projectstory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_relation`
--

DROP TABLE IF EXISTS `zt_relation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_relation` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `project` mediumint(8) NOT NULL,
  `AType` char(30) NOT NULL,
  `AID` mediumint(8) NOT NULL,
  `AVersion` char(30) NOT NULL,
  `relation` char(30) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` mediumint(8) NOT NULL,
  `BVersion` char(30) NOT NULL,
  `extra` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`relation`,`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_relation`
--

LOCK TABLES `zt_relation` WRITE;
/*!40000 ALTER TABLE `zt_relation` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_relation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_release`
--

DROP TABLE IF EXISTS `zt_release`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_release` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `build` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `marker` enum('0','1') NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `leftBugs` text NOT NULL,
  `desc` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'normal',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `build` (`build`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_release`
--

LOCK TABLES `zt_release` WRITE;
/*!40000 ALTER TABLE `zt_release` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_release` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_repo`
--

DROP TABLE IF EXISTS `zt_repo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_repo` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `encoding` varchar(20) NOT NULL,
  `SCM` varchar(10) NOT NULL,
  `client` varchar(100) NOT NULL,
  `commits` mediumint(8) unsigned NOT NULL,
  `account` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `encrypt` varchar(30) NOT NULL DEFAULT 'plain',
  `acl` text NOT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `lastSync` datetime NOT NULL,
  `desc` text NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_repo`
--

LOCK TABLES `zt_repo` WRITE;
/*!40000 ALTER TABLE `zt_repo` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_repo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_repobranch`
--

DROP TABLE IF EXISTS `zt_repobranch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_repobranch` (
  `repo` mediumint(8) unsigned NOT NULL,
  `revision` mediumint(8) unsigned NOT NULL,
  `branch` varchar(255) NOT NULL,
  UNIQUE KEY `repo_revision_branch` (`repo`,`revision`,`branch`),
  KEY `branch` (`branch`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_repobranch`
--

LOCK TABLES `zt_repobranch` WRITE;
/*!40000 ALTER TABLE `zt_repobranch` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_repobranch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_repofiles`
--

DROP TABLE IF EXISTS `zt_repofiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_repofiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo` mediumint(8) unsigned NOT NULL,
  `revision` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `parent` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `action` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`),
  KEY `parent` (`parent`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_repofiles`
--

LOCK TABLES `zt_repofiles` WRITE;
/*!40000 ALTER TABLE `zt_repofiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_repofiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_repohistory`
--

DROP TABLE IF EXISTS `zt_repohistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_repohistory` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `repo` mediumint(9) NOT NULL,
  `revision` varchar(40) NOT NULL,
  `commit` mediumint(8) unsigned NOT NULL,
  `comment` text NOT NULL,
  `committer` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_repohistory`
--

LOCK TABLES `zt_repohistory` WRITE;
/*!40000 ALTER TABLE `zt_repohistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_repohistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_risk`
--

DROP TABLE IF EXISTS `zt_risk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_risk` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `source` char(30) NOT NULL,
  `category` char(30) NOT NULL,
  `strategy` char(30) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `impact` char(30) NOT NULL,
  `probability` char(30) NOT NULL,
  `rate` char(30) NOT NULL,
  `pri` char(30) NOT NULL,
  `identifiedDate` date NOT NULL,
  `prevention` text NOT NULL,
  `remedy` text NOT NULL,
  `plannedClosedDate` date NOT NULL,
  `actualClosedDate` date NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `resolution` text NOT NULL,
  `resolvedBy` varchar(30) NOT NULL,
  `activateBy` varchar(30) NOT NULL,
  `activateDate` date NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `cancelBy` varchar(30) NOT NULL,
  `cancelDate` date NOT NULL,
  `cancelReason` char(30) NOT NULL,
  `hangupBy` varchar(30) NOT NULL,
  `hangupDate` date NOT NULL,
  `trackedBy` varchar(30) NOT NULL,
  `trackedDate` date NOT NULL,
  `assignedDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_risk`
--

LOCK TABLES `zt_risk` WRITE;
/*!40000 ALTER TABLE `zt_risk` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_risk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_score`
--

DROP TABLE IF EXISTS `zt_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_score` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT '',
  `method` varchar(30) NOT NULL,
  `desc` varchar(250) NOT NULL DEFAULT '',
  `before` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `after` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `model` (`module`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_score`
--

LOCK TABLES `zt_score` WRITE;
/*!40000 ALTER TABLE `zt_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_stage`
--

DROP TABLE IF EXISTS `zt_stage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_stage` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `percent` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_stage`
--

LOCK TABLES `zt_stage` WRITE;
/*!40000 ALTER TABLE `zt_stage` DISABLE KEYS */;
INSERT INTO `zt_stage` VALUES (1,'需求','10','request','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),(2,'设计','10','design','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),(3,'开发','50','dev','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),(4,'测试','15','qa','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),(5,'发布','10','release','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),(6,'总结评审','5','review','admin','2020-02-08 21:08:45','admin','2020-02-12 13:50:27','0');
/*!40000 ALTER TABLE `zt_stage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_story`
--

DROP TABLE IF EXISTS `zt_story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_story` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(9) NOT NULL DEFAULT '0',
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plan` text,
  `source` varchar(20) NOT NULL,
  `sourceNote` varchar(255) NOT NULL,
  `fromBug` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'story',
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `estimate` float unsigned NOT NULL,
  `status` enum('','changed','active','draft','closed') NOT NULL DEFAULT '',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL,
  `stage` enum('','wait','planned','projected','developing','developed','testing','tested','verified','released','closed') NOT NULL DEFAULT 'wait',
  `stagedBy` char(30) NOT NULL,
  `mailto` text,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NOT NULL,
  `reviewedBy` varchar(255) NOT NULL,
  `reviewedDate` date NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `toBug` mediumint(8) unsigned NOT NULL,
  `childStories` varchar(255) NOT NULL,
  `linkStories` varchar(255) NOT NULL,
  `duplicateStory` mediumint(8) unsigned NOT NULL,
  `version` smallint(6) NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `status` (`status`),
  KEY `assignedTo` (`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_story`
--

LOCK TABLES `zt_story` WRITE;
/*!40000 ALTER TABLE `zt_story` DISABLE KEYS */;
INSERT INTO `zt_story` VALUES (1,0,1,0,1,'1','po','',0,'首页设计和开发','','',1,1,'active','','','developed','','','productManager','2012-06-05 10:09:49','productManager','0000-00-00 00:00:00','productManager','2012-06-05 10:25:19','productManager, ','2012-06-05','','0000-00-00 00:00:00','',0,'','',0,1,'0'),(2,0,1,0,2,'1','po','',0,'新闻中心的设计和开发。','','',1,1,'active','','','developed','','','productManager','2012-06-05 10:16:37','productManager','2012-06-05 10:16:37','productManager','2012-06-05 10:25:33','productManager, ','2012-06-05','','0000-00-00 00:00:00','',0,'','',0,1,'0'),(3,0,1,0,3,'1','po','',0,'成果展示的设计和开发','','',1,0,'active','','','developed','','','productManager','2012-06-05 10:18:10','productManager','2012-06-05 10:18:10','productManager','2012-06-05 10:25:38','productManager, ','2012-06-05','','0000-00-00 00:00:00','',0,'','',0,2,'0'),(4,0,1,0,4,'1','po','',0,'售后服务的设计和开发','','',1,1,'active','','','projected','','','productManager','2012-06-05 10:20:16','productManager','2012-06-05 10:20:16','productManager','2012-06-05 10:25:42','productManager, ','2012-06-05','','0000-00-00 00:00:00','',0,'','',0,1,'0'),(5,0,1,0,5,'1','po','',0,'诚聘英才的设计和开发','','',1,1,'draft','','','planned','','','productManager','2012-06-05 10:21:39','productManager','2012-06-05 10:21:39','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','',0,'','',0,1,'0'),(6,0,1,0,6,'1','po','',0,'合作洽谈的设计和开发','','',1,1,'draft','','','planned','','','productManager','2012-06-05 10:23:11','productManager','2012-06-05 10:23:11','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','',0,'','',0,1,'0'),(7,0,1,0,7,'1','po','',0,'关于我们的设计和开发','','',1,1,'draft','','','planned','','','productManager','2012-06-05 10:24:19','productManager','2012-06-05 10:24:19','','0000-00-00 00:00:00','','0000-00-00','','0000-00-00 00:00:00','',0,'','',0,1,'0');
/*!40000 ALTER TABLE `zt_story` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_storySpec`
--

DROP TABLE IF EXISTS `zt_storySpec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_storySpec` (
  `company` mediumint(8) unsigned NOT NULL,
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(90) NOT NULL,
  `spec` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`),
  KEY `company` (`company`),
  KEY `company_2` (`company`),
  KEY `company_3` (`company`),
  KEY `company_4` (`company`),
  KEY `company_5` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_storySpec`
--

LOCK TABLES `zt_storySpec` WRITE;
/*!40000 ALTER TABLE `zt_storySpec` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_storySpec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_storyspec`
--

DROP TABLE IF EXISTS `zt_storyspec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_storyspec` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(255) NOT NULL,
  `spec` text NOT NULL,
  `verify` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_storyspec`
--

LOCK TABLES `zt_storyspec` WRITE;
/*!40000 ALTER TABLE `zt_storyspec` DISABLE KEYS */;
INSERT INTO `zt_storyspec` VALUES (1,1,'首页设计和开发','作为一名本公司的用户，我希望可以在首页看到该公司网站的基本内容，例如最新动态、部分成果展示、联系信息、工商信息等。<br />','开发并通过验收<br />'),(2,1,'新闻中心的设计和开发。','作为一名本公司的用户，我希望可以在新闻中心看到该公司网站的企业新闻，这样可以通过新闻了解企业的最新动态。<br />',''),(3,1,'成果展示的设计和开发','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的企业新闻，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />',''),(3,2,'成果展示的设计和开发','&nbsp;作为一名本公司的用户，我希望可以在成果展示看到该公司网站的吹产品，这样可以方便我进行了解该公司的产品并进行购买。&nbsp;<br />',''),(4,1,'售后服务的设计和开发','作为一名本公司的用户，我希望可以在售后服务看到该公司网站的售后服务，这样可以方便我联系该公司来解决我的问题。&nbsp;<br />',''),(5,1,'诚聘英才的设计和开发','作为一名求职者，我希望可以在诚聘英才里看到该公司的招聘信息，这样可以方便我应聘该公司。&nbsp;<br />',''),(6,1,'合作洽谈的设计和开发','作为一名合作商，我希望可以在合作洽谈里看到该公司对外的合作内容，这样可以方便我和该公司进行合作洽谈。&nbsp;<br />',''),(7,1,'关于我们的设计和开发','我希望可以在关于我们里看到该公司的基本信息，这样可以方便我了解该公司。<br />','');
/*!40000 ALTER TABLE `zt_storyspec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_storystage`
--

DROP TABLE IF EXISTS `zt_storystage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_storystage` (
  `story` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `stage` varchar(50) NOT NULL,
  `stagedBy` char(30) NOT NULL,
  UNIQUE KEY `story_branch` (`story`,`branch`),
  KEY `story` (`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_storystage`
--

LOCK TABLES `zt_storystage` WRITE;
/*!40000 ALTER TABLE `zt_storystage` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_storystage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_suitecase`
--

DROP TABLE IF EXISTS `zt_suitecase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_suitecase` (
  `suite` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `suitecase` (`suite`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_suitecase`
--

LOCK TABLES `zt_suitecase` WRITE;
/*!40000 ALTER TABLE `zt_suitecase` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_suitecase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_task`
--

DROP TABLE IF EXISTS `zt_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_task` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) NOT NULL DEFAULT '0',
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `module` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `story` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `storyVersion` smallint(6) NOT NULL DEFAULT '1',
  `fromBug` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `estimate` float unsigned NOT NULL,
  `consumed` float unsigned NOT NULL,
  `left` float unsigned NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('wait','doing','done','pause','cancel','closed') NOT NULL DEFAULT 'wait',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL,
  `mailto` text,
  `desc` text NOT NULL,
  `openedBy` varchar(30) NOT NULL,
  `openedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `estStarted` date NOT NULL,
  `realStarted` date NOT NULL,
  `finishedBy` varchar(30) NOT NULL,
  `finishedDate` datetime NOT NULL,
  `finishedList` text NOT NULL,
  `canceledBy` varchar(30) NOT NULL,
  `canceledDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `project` (`project`),
  KEY `story` (`story`),
  KEY `assignedTo` (`assignedTo`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_task`
--

LOCK TABLES `zt_task` WRITE;
/*!40000 ALTER TABLE `zt_task` DISABLE KEYS */;
INSERT INTO `zt_task` VALUES (1,0,1,0,1,1,0,'首页页面的设计','design',1,10,7,0,'0000-00-00','done','','','','首页页面的设计<br />','projectManager','2012-06-05 10:32:03','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','2012-06-05 10:41:20','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0'),(2,0,1,0,1,1,0,'首页的开发','devel',1,10,8,0,'0000-00-00','done','','','','首页的开发<br />','projectManager','2012-06-05 10:32:23','dev1','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','2012-06-05 10:41:20','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0'),(3,0,1,0,2,1,0,'新闻中心的设计','design',1,8,8,0,'0000-00-00','done','','','','新闻中心的设计<br />','projectManager','2012-06-05 10:33:01','dev2','2012-06-05 10:42:56','0000-00-00','0000-00-00','dev2','2012-06-05 10:42:56','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev2','2012-06-05 10:42:56','0'),(4,0,1,0,2,1,0,'新闻中心的开发','devel',1,8,5,0,'0000-00-00','done','','','','新闻中心的开发<br />','projectManager','2012-06-05 10:33:21','dev2','2012-06-05 10:42:56','0000-00-00','0000-00-00','dev2','2012-06-05 10:42:56','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev2','2012-06-05 10:42:56','0'),(5,0,1,0,3,2,0,'成果展示的设计','design',1,8,5,0,'0000-00-00','done','','','','成果展示的设计<br />','projectManager','2012-06-05 10:33:44','dev3','2012-06-05 10:43:32','0000-00-00','0000-00-00','dev3','2012-06-05 10:43:32','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev3','2012-06-05 10:43:32','0'),(6,0,1,0,3,2,0,'成果展示的开发','devel',1,8,5,0,'0000-00-00','done','','','','成果展示的开发<br />','projectManager','2012-06-05 10:33:59','dev3','2012-06-05 10:43:32','0000-00-00','0000-00-00','dev3','2012-06-05 10:43:32','','','0000-00-00 00:00:00','','0000-00-00 00:00:00','','dev3','2012-06-05 10:43:32','0'),(7,0,1,0,4,1,0,'售后服务的设计','design',1,8,0,8,'0000-00-00','cancel','','','','售后服务的设计<br />','projectManager','2012-06-05 10:34:25','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0'),(8,0,1,0,4,1,0,'售后服务的开发','devel',1,8,6,0,'0000-00-00','cancel','','','','售后服务的开发<br />','projectManager','2012-06-05 10:34:45','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','dev1','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0'),(9,0,1,0,4,1,0,'售后服务的开发','devel',1,8,0,8,'0000-00-00','cancel','','','','售后服务的开发<br />','projectManager','2012-06-05 10:35:01','projectManager','2012-06-05 10:41:20','0000-00-00','0000-00-00','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','','0000-00-00 00:00:00','','dev1','2012-06-05 10:41:20','0');
/*!40000 ALTER TABLE `zt_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_taskestimate`
--

DROP TABLE IF EXISTS `zt_taskestimate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_taskestimate` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `left` float unsigned NOT NULL DEFAULT '0',
  `consumed` float unsigned NOT NULL,
  `account` char(30) NOT NULL DEFAULT '',
  `work` text,
  PRIMARY KEY (`id`),
  KEY `task` (`task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_taskestimate`
--

LOCK TABLES `zt_taskestimate` WRITE;
/*!40000 ALTER TABLE `zt_taskestimate` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_taskestimate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_taskspec`
--

DROP TABLE IF EXISTS `zt_taskspec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_taskspec` (
  `task` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `estStarted` date NOT NULL,
  `deadline` date NOT NULL,
  UNIQUE KEY `task` (`task`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_taskspec`
--

LOCK TABLES `zt_taskspec` WRITE;
/*!40000 ALTER TABLE `zt_taskspec` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_taskspec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_team`
--

DROP TABLE IF EXISTS `zt_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_team` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `root` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` enum('project','task') NOT NULL DEFAULT 'project',
  `account` char(30) NOT NULL DEFAULT '',
  `role` char(30) NOT NULL DEFAULT '',
  `limited` char(8) NOT NULL DEFAULT 'no',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `days` smallint(5) unsigned NOT NULL,
  `hours` float(2,1) unsigned NOT NULL DEFAULT '0.0',
  `estimate` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `consumed` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `left` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `order` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `team` (`root`,`type`,`account`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_team`
--

LOCK TABLES `zt_team` WRITE;
/*!40000 ALTER TABLE `zt_team` DISABLE KEYS */;
INSERT INTO `zt_team` VALUES (1,1,'project','dev3','研发','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(2,1,'project','productManager','产品经理','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(3,1,'project','tester2','测试','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(4,1,'project','tester1','测试','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(5,2,'project','projectManager','项目经理','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(6,2,'project','tester2','测试','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(7,2,'project','tester1','测试','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(8,2,'project','dev3','研发','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(9,2,'project','dev2','研发','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(10,2,'project','dev1','研发','no','2013-02-20',365,7.0,0.00,0.00,0.00,0),(11,1,'project','dev1','研发','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(12,1,'project','dev2','研发','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(13,1,'project','projectManager','项目经理','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(14,1,'project','testManager','测试主管','no','2013-02-20',184,7.0,0.00,0.00,0.00,0),(15,2,'project','productManager','产品经理','no','2013-02-20',365,7.0,0.00,0.00,0.00,0);
/*!40000 ALTER TABLE `zt_team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testResult`
--

DROP TABLE IF EXISTS `zt_testResult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testResult` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `run` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  `caseResult` char(30) NOT NULL,
  `stepResults` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `run` (`run`),
  KEY `case` (`case`,`version`),
  KEY `company` (`company`),
  KEY `company_2` (`company`),
  KEY `company_3` (`company`),
  KEY `company_4` (`company`),
  KEY `company_5` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testResult`
--

LOCK TABLES `zt_testResult` WRITE;
/*!40000 ALTER TABLE `zt_testResult` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_testResult` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testRun`
--

DROP TABLE IF EXISTS `zt_testRun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testRun` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `assignedTo` char(30) NOT NULL DEFAULT '',
  `lastRun` datetime NOT NULL,
  `lastResult` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task` (`task`,`case`),
  KEY `company` (`company`),
  KEY `company_2` (`company`),
  KEY `company_3` (`company`),
  KEY `company_4` (`company`),
  KEY `company_5` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testRun`
--

LOCK TABLES `zt_testRun` WRITE;
/*!40000 ALTER TABLE `zt_testRun` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_testRun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testTask`
--

DROP TABLE IF EXISTS `zt_testTask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testTask` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `name` char(90) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `build` char(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `desc` text NOT NULL,
  `status` char(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `company` (`company`),
  KEY `company_2` (`company`),
  KEY `company_3` (`company`),
  KEY `company_4` (`company`),
  KEY `company_5` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testTask`
--

LOCK TABLES `zt_testTask` WRITE;
/*!40000 ALTER TABLE `zt_testTask` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_testTask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testreport`
--

DROP TABLE IF EXISTS `zt_testreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `tasks` varchar(255) NOT NULL,
  `builds` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `owner` char(30) NOT NULL,
  `members` text NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `cases` text NOT NULL,
  `report` text NOT NULL,
  `objectType` varchar(20) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testreport`
--

LOCK TABLES `zt_testreport` WRITE;
/*!40000 ALTER TABLE `zt_testreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_testreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testresult`
--

DROP TABLE IF EXISTS `zt_testresult`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testresult` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `run` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  `job` mediumint(8) unsigned NOT NULL,
  `compile` mediumint(8) unsigned NOT NULL,
  `caseResult` char(30) NOT NULL,
  `stepResults` text NOT NULL,
  `lastRunner` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  `duration` float NOT NULL,
  `xml` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `case` (`case`),
  KEY `version` (`version`),
  KEY `run` (`run`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testresult`
--

LOCK TABLES `zt_testresult` WRITE;
/*!40000 ALTER TABLE `zt_testresult` DISABLE KEYS */;
INSERT INTO `zt_testresult` VALUES (1,4,1,1,0,0,'pass','a:1:{i:1;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:00',0,''),(2,3,2,1,0,0,'pass','a:1:{i:2;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:05',0,''),(3,2,3,1,0,0,'pass','a:1:{i:3;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:07',0,''),(4,1,4,1,0,0,'pass','a:1:{i:4;a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}','testManager','2012-06-05 11:11:08',0,'');
/*!40000 ALTER TABLE `zt_testresult` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testrun`
--

DROP TABLE IF EXISTS `zt_testrun`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testrun` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `case` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `assignedTo` char(30) NOT NULL DEFAULT '',
  `lastRunner` varchar(30) NOT NULL,
  `lastRunDate` datetime NOT NULL,
  `lastRunResult` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task` (`task`,`case`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testrun`
--

LOCK TABLES `zt_testrun` WRITE;
/*!40000 ALTER TABLE `zt_testrun` DISABLE KEYS */;
INSERT INTO `zt_testrun` VALUES (1,1,4,1,'','testManager','2012-06-05 11:11:08','pass','done'),(2,1,3,1,'','testManager','2012-06-05 11:11:07','pass','done'),(3,1,2,1,'','testManager','2012-06-05 11:11:05','pass','done'),(4,1,1,1,'','testManager','2012-06-05 11:11:00','pass','done');
/*!40000 ALTER TABLE `zt_testrun` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testsuite`
--

DROP TABLE IF EXISTS `zt_testsuite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testsuite` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product` (`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testsuite`
--

LOCK TABLES `zt_testsuite` WRITE;
/*!40000 ALTER TABLE `zt_testsuite` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_testsuite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_testtask`
--

DROP TABLE IF EXISTS `zt_testtask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_testtask` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(90) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `build` char(30) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `mailto` text,
  `desc` text NOT NULL,
  `report` text NOT NULL,
  `status` enum('blocked','doing','wait','done') NOT NULL DEFAULT 'wait',
  `auto` varchar(10) NOT NULL DEFAULT 'no',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `build` (`build`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_testtask`
--

LOCK TABLES `zt_testtask` WRITE;
/*!40000 ALTER TABLE `zt_testtask` DISABLE KEYS */;
INSERT INTO `zt_testtask` VALUES (1,'企业网站第一期测试任务',1,1,'trunk','testManager',0,'2012-06-05','2013-06-21',NULL,'','','wait','no','','0');
/*!40000 ALTER TABLE `zt_testtask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_todo`
--

DROP TABLE IF EXISTS `zt_todo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `date` date NOT NULL,
  `begin` smallint(4) unsigned zerofill NOT NULL,
  `end` smallint(4) unsigned zerofill NOT NULL,
  `type` char(10) NOT NULL,
  `cycle` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `idvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `name` char(150) NOT NULL,
  `desc` text NOT NULL,
  `status` enum('wait','doing','done','closed') NOT NULL DEFAULT 'wait',
  `private` tinyint(1) NOT NULL,
  `config` varchar(255) NOT NULL,
  `assignedTo` varchar(30) NOT NULL DEFAULT '',
  `assignedBy` varchar(30) NOT NULL DEFAULT '',
  `assignedDate` datetime NOT NULL,
  `finishedBy` varchar(30) NOT NULL DEFAULT '',
  `finishedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL DEFAULT '',
  `closedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `assignedTo` (`assignedTo`),
  KEY `finishedBy` (`finishedBy`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_todo`
--

LOCK TABLES `zt_todo` WRITE;
/*!40000 ALTER TABLE `zt_todo` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_todo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_translation`
--

DROP TABLE IF EXISTS `zt_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_translation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lang` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `referer` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `translator` char(30) NOT NULL,
  `translatedTime` datetime NOT NULL,
  `reviewer` char(30) NOT NULL,
  `reviewedTime` datetime NOT NULL,
  `reason` text NOT NULL,
  `version` varchar(20) NOT NULL,
  `mode` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lang_module_key_mode` (`lang`,`module`,`key`,`mode`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_translation`
--

LOCK TABLES `zt_translation` WRITE;
/*!40000 ALTER TABLE `zt_translation` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_user`
--

DROP TABLE IF EXISTS `zt_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dept` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `role` char(10) NOT NULL DEFAULT '',
  `realname` varchar(100) NOT NULL DEFAULT '',
  `nickname` char(60) NOT NULL DEFAULT '',
  `commiter` varchar(100) NOT NULL,
  `avatar` char(30) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  `gender` enum('f','m') NOT NULL DEFAULT 'f',
  `email` char(90) NOT NULL DEFAULT '',
  `skype` char(90) NOT NULL DEFAULT '',
  `qq` char(20) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `phone` char(20) NOT NULL DEFAULT '',
  `weixin` varchar(90) NOT NULL DEFAULT '',
  `dingding` varchar(90) NOT NULL DEFAULT '',
  `slack` varchar(90) NOT NULL DEFAULT '',
  `whatsapp` varchar(90) NOT NULL DEFAULT '',
  `address` char(120) NOT NULL DEFAULT '',
  `zipcode` char(10) NOT NULL DEFAULT '',
  `join` date NOT NULL DEFAULT '0000-00-00',
  `visits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `last` int(10) unsigned NOT NULL DEFAULT '0',
  `fails` tinyint(5) NOT NULL DEFAULT '0',
  `locked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ranzhi` char(30) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  `scoreLevel` int(11) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `clientStatus` enum('online','away','busy','offline') NOT NULL DEFAULT 'offline',
  `clientLang` varchar(10) NOT NULL DEFAULT 'zh-cn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `dept` (`dept`),
  KEY `email` (`email`),
  KEY `commiter` (`commiter`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_user`
--

LOCK TABLES `zt_user` WRITE;
/*!40000 ALTER TABLE `zt_user` DISABLE KEYS */;
INSERT INTO `zt_user` VALUES (1,0,'admin','6da8a7c05e2aa1774828500a8370feea','','admin','','','','0000-00-00','f','','','','','','','','','','','','0000-00-00',1,'10.0.2.2',1605850609,0,'0000-00-00 00:00:00','',0,0,'0','offline','zh-cn'),(2,5,'productManager','e10adc3949ba59abbe56e057f20f883e','po','产品经理','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',3,'192.168.0.8',1338866083,0,'0000-00-00 00:00:00','',0,0,'0','offline','zh-cn'),(3,6,'projectManager','e10adc3949ba59abbe56e057f20f883e','pm','项目经理','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',3,'192.168.0.8',1338865876,0,'0000-00-00 00:00:00','',0,0,'0','offline','zh-cn'),(4,2,'dev1','e10adc3949ba59abbe56e057f20f883e','dev','开发甲','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',1,'192.168.0.8',1338863860,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(5,2,'dev2','e10adc3949ba59abbe56e057f20f883e','dev','开发乙','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',1,'192.168.0.8',1338864116,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(6,2,'dev3','e10adc3949ba59abbe56e057f20f883e','dev','开发丙','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',1,'192.168.0.8',1338864187,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(7,3,'tester1','e10adc3949ba59abbe56e057f20f883e','qa','测试甲','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',3,'192.168.0.8',1338865739,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(8,3,'tester2','e10adc3949ba59abbe56e057f20f883e','qa','测试乙','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',2,'192.168.0.8',1338865450,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(9,3,'tester3','e10adc3949ba59abbe56e057f20f883e','qa','测试丙','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',1,'192.168.0.8',1338865125,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn'),(10,1,'testManager','e10adc3949ba59abbe56e057f20f883e','qd','测试经理','','','','0000-00-00','m','','','','','','','','','','','','0000-00-00',6,'192.168.0.8',1338865842,0,'0000-00-00 00:00:00','',0,0,'1','offline','zh-cn');
/*!40000 ALTER TABLE `zt_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_userQuery`
--

DROP TABLE IF EXISTS `zt_userQuery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_userQuery` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `account` char(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(90) NOT NULL,
  `form` text NOT NULL,
  `sql` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company` (`company`),
  KEY `account` (`account`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_userQuery`
--

LOCK TABLES `zt_userQuery` WRITE;
/*!40000 ALTER TABLE `zt_userQuery` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_userQuery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_userTPL`
--

DROP TABLE IF EXISTS `zt_userTPL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_userTPL` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `account` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `company` (`company`),
  KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_userTPL`
--

LOCK TABLES `zt_userTPL` WRITE;
/*!40000 ALTER TABLE `zt_userTPL` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_userTPL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_usercontact`
--

DROP TABLE IF EXISTS `zt_usercontact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_usercontact` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `listName` varchar(60) NOT NULL,
  `userList` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_usercontact`
--

LOCK TABLES `zt_usercontact` WRITE;
/*!40000 ALTER TABLE `zt_usercontact` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_usercontact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_usergroup`
--

DROP TABLE IF EXISTS `zt_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_usergroup` (
  `account` char(30) NOT NULL DEFAULT '',
  `group` mediumint(8) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_usergroup`
--

LOCK TABLES `zt_usergroup` WRITE;
/*!40000 ALTER TABLE `zt_usergroup` DISABLE KEYS */;
INSERT INTO `zt_usergroup` VALUES ('dev1',2),('dev2',2),('dev3',2),('productManager',5),('projectManager',4),('tester1',3),('tester2',3),('tester3',3),('testManager',8);
/*!40000 ALTER TABLE `zt_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_userquery`
--

DROP TABLE IF EXISTS `zt_userquery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_userquery` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(90) NOT NULL,
  `form` text NOT NULL,
  `sql` text NOT NULL,
  `shortcut` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_userquery`
--

LOCK TABLES `zt_userquery` WRITE;
/*!40000 ALTER TABLE `zt_userquery` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_userquery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_usertpl`
--

DROP TABLE IF EXISTS `zt_usertpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_usertpl` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `public` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_usertpl`
--

LOCK TABLES `zt_usertpl` WRITE;
/*!40000 ALTER TABLE `zt_usertpl` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_usertpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_userview`
--

DROP TABLE IF EXISTS `zt_userview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_userview` (
  `account` char(30) NOT NULL,
  `products` mediumtext NOT NULL,
  `projects` mediumtext NOT NULL,
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_userview`
--

LOCK TABLES `zt_userview` WRITE;
/*!40000 ALTER TABLE `zt_userview` DISABLE KEYS */;
INSERT INTO `zt_userview` VALUES ('admin','','');
/*!40000 ALTER TABLE `zt_userview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_webhook`
--

DROP TABLE IF EXISTS `zt_webhook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_webhook` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(15) NOT NULL DEFAULT 'default',
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `secret` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL DEFAULT 'application/json',
  `sendType` enum('sync','async') NOT NULL DEFAULT 'sync',
  `products` text NOT NULL,
  `projects` text NOT NULL,
  `params` varchar(100) NOT NULL,
  `actions` text NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_webhook`
--

LOCK TABLES `zt_webhook` WRITE;
/*!40000 ALTER TABLE `zt_webhook` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_webhook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_weeklyreport`
--

DROP TABLE IF EXISTS `zt_weeklyreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_weeklyreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
  `weekStart` date NOT NULL,
  `pv` float(9,2) NOT NULL,
  `ev` float(9,2) NOT NULL,
  `ac` float(9,2) NOT NULL,
  `sv` float(9,2) NOT NULL,
  `cv` float(9,2) NOT NULL,
  `staff` smallint(5) unsigned NOT NULL,
  `progress` varchar(255) NOT NULL,
  `workload` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `week` (`PRJ`,`weekStart`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_weeklyreport`
--

LOCK TABLES `zt_weeklyreport` WRITE;
/*!40000 ALTER TABLE `zt_weeklyreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_weeklyreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zt_workestimation`
--

DROP TABLE IF EXISTS `zt_workestimation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_workestimation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
  `scale` mediumint(8) unsigned NOT NULL,
  `productivity` smallint(3) unsigned NOT NULL,
  `duration` mediumint(8) unsigned NOT NULL,
  `unitLaborCost` mediumint(8) unsigned NOT NULL,
  `totalLaborCost` mediumint(8) unsigned NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `dayHour` float(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_workestimation`
--

LOCK TABLES `zt_workestimation` WRITE;
/*!40000 ALTER TABLE `zt_workestimation` DISABLE KEYS */;
/*!40000 ALTER TABLE `zt_workestimation` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-02-20 13:58:01
