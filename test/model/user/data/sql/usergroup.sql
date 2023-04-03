-- MySQL dump 10.19  Distrib 10.3.37-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: zentaotmp0104
-- ------------------------------------------------------
-- Server version	10.3.34-MariaDB-0ubuntu0.20.04.1

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
-- Table structure for table `zt_usergroup`
--

DROP TABLE IF EXISTS `zt_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_usergroup` (
  `account` char(30) NOT NULL DEFAULT '',
  `group` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `project` text NOT NULL,
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_usergroup`
--

LOCK TABLES `zt_usergroup` WRITE;
/*!40000 ALTER TABLE `zt_usergroup` DISABLE KEYS */;
INSERT INTO `zt_usergroup` VALUES ('dev1',3,''),('test10',3,''),('test100',3,''),('test11',3,''),('test12',3,''),('test13',3,''),('test14',3,''),('test15',3,''),('test16',3,''),('test17',3,''),('test18',3,''),('test19',3,''),('test2',3,''),('test20',3,''),('test21',3,''),('test22',3,''),('test23',3,''),('test24',3,''),('test25',3,''),('test26',3,''),('test27',3,''),('test28',3,''),('test29',3,''),('test3',3,''),('test30',3,''),('test31',3,''),('test32',3,''),('test33',3,''),('test34',3,''),('test35',3,''),('test36',3,''),('test37',3,''),('test38',3,''),('test39',3,''),('test4',3,''),('test40',3,''),('test41',3,''),('test42',3,''),('test43',3,''),('test44',3,''),('test45',3,''),('test46',3,''),('test47',3,''),('test48',3,''),('test49',3,''),('test5',3,''),('test50',3,''),('test51',3,''),('test52',3,''),('test53',3,''),('test54',3,''),('test55',3,''),('test56',3,''),('test57',3,''),('test58',3,''),('test59',3,''),('test6',3,''),('test60',3,''),('test61',3,''),('test62',3,''),('test63',3,''),('test64',3,''),('test65',3,''),('test66',3,''),('test67',3,''),('test68',3,''),('test69',3,''),('test7',3,''),('test70',3,''),('test71',3,''),('test72',3,''),('test73',3,''),('test74',3,''),('test75',3,''),('test76',3,''),('test77',3,''),('test78',3,''),('test79',3,''),('test8',3,''),('test80',3,''),('test81',3,''),('test82',3,''),('test83',3,''),('test84',3,''),('test85',3,''),('test86',3,''),('test87',3,''),('test88',3,''),('test89',3,''),('test9',3,''),('test90',3,''),('test91',3,''),('test92',3,''),('test93',3,''),('test94',3,''),('test95',3,''),('test96',3,''),('test97',3,''),('test98',3,''),('test99',3,'');
/*!40000 ALTER TABLE `zt_usergroup` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-09 11:16:59
