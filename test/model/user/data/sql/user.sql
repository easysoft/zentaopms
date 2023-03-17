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
-- Table structure for table `zt_user`
--

DROP TABLE IF EXISTS `zt_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `type` char(30) NOT NULL DEFAULT 'inside',
  `dept` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `account` char(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `role` char(10) NOT NULL DEFAULT '',
  `realname` varchar(100) NOT NULL DEFAULT '',
  `pinyin` varchar(255) NOT NULL DEFAULT '',
  `nickname` char(60) NOT NULL DEFAULT '',
  `commiter` varchar(100) NOT NULL,
  `avatar` text NOT NULL,
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
  `nature` text NOT NULL,
  `analysis` text NOT NULL,
  `strategy` text NOT NULL,
  `join` date NOT NULL DEFAULT '0000-00-00',
  `visits` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `visions` varchar(20) NOT NULL DEFAULT 'rnd,lite',
  `ip` char(15) NOT NULL DEFAULT '',
  `last` int(10) unsigned NOT NULL DEFAULT 0,
  `fails` tinyint(5) NOT NULL DEFAULT 0,
  `locked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `feedback` enum('0','1') NOT NULL DEFAULT '0',
  `ranzhi` char(30) NOT NULL DEFAULT '',
  `ldap` char(30) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `scoreLevel` int(11) NOT NULL DEFAULT 0,
  `resetToken` varchar(50) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `clientStatus` enum('online','away','busy','offline','meeting') NOT NULL DEFAULT 'offline',
  `clientLang` varchar(10) NOT NULL DEFAULT 'zh-cn',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `dept` (`dept`),
  KEY `email` (`email`),
  KEY `commiter` (`commiter`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_user`
--

LOCK TABLES `zt_user` WRITE;
/*!40000 ALTER TABLE `zt_user` DISABLE KEYS */;
INSERT INTO `zt_user` VALUES (1,1,'inside',1,'admin','a0933c1218a4e745bacdcf572b10eba7','qa','admin','','','admin','/home/z/tmp/1.png','1993-01-14','f','10001000@qq.com','Skype','419853297','','16240905423','','','slack','whatsApp','','','','','','0000-00-00',4501,'rnd,lite','',1671004035,0,'0000-00-00 00:00:00','0','admin','',9879,0,'','0','offline','zh-cn'),(2,1,'inside',1,'user1','a0933c1218a4e745bacdcf572b10eba7','qa','用户1','','','user1','/home/z/user/2.png','1993-03-15','m','10021002@163.com','Skype','915222619','','1989503692','','','slack','whatsApp','','','','','','0000-00-00',1114,'rnd,lite','',1671007635,2,'0000-00-00 00:00:00','0','user1','',7739,0,'','0','offline','zh-cn'),(3,1,'inside',1,'user2','a0933c1218a4e745bacdcf572b10eba7','qa','用户2','','','user2','/home/z/tmp/3.png','1993-05-14','f','10041004@gmail.com','Skype','924207575','','13345688731','','','slack','whatsApp','','','','','','0000-00-00',9759,'rnd,lite','',1671011235,3,'0000-00-00 00:00:00','0','user2','',2959,0,'','0','offline','zh-cn'),(4,1,'inside',1,'user3','a0933c1218a4e745bacdcf572b10eba7','qa','用户3','','','user3','/home/z/user/4.png','1993-07-13','m','10061006@qq.com','Skype','323845794','','13490126294','','','slack','whatsApp','','','','','','0000-00-00',1607,'rnd,lite','',1671014835,4,'0000-00-00 00:00:00','0','user3','',616,0,'','0','offline','zh-cn'),(5,1,'inside',1,'user4','a0933c1218a4e745bacdcf572b10eba7','qa','用户4','','','user4','/home/z/tmp/5.png','1993-09-11','f','10081008@163.com','Skype','826371933','','17564815807','','','slack','whatsApp','','','','','','0000-00-00',654,'rnd,lite','',1671018435,5,'0000-00-00 00:00:00','0','user4','',8966,0,'','0','offline','zh-cn'),(6,1,'inside',1,'user5','a0933c1218a4e745bacdcf572b10eba7','qa','用户5','','','user5','/home/z/user/6.png','1993-11-10','m','10101010@gmail.com','Skype','272590473','','13360855020','','','slack','whatsApp','','','','','','0000-00-00',9296,'rnd,lite','',1671022035,6,'0000-00-00 00:00:00','0','user5','',8924,0,'','0','offline','zh-cn'),(7,1,'inside',1,'user6','a0933c1218a4e745bacdcf572b10eba7','qa','用户6','','','user6','/home/z/tmp/7.png','1994-01-09','f','10121012@qq.com','Skype','646316961','','16677041853','','','slack','whatsApp','','','','','','0000-00-00',1786,'rnd,lite','',1671025635,7,'0000-00-00 00:00:00','0','user6','',717,0,'','0','offline','zh-cn'),(8,1,'inside',1,'user7','a0933c1218a4e745bacdcf572b10eba7','qa','用户7','','','user7','/home/z/user/8.png','1994-03-10','m','10141014@163.com','Skype','170882505','','16448943540','','','slack','whatsApp','','','','','','0000-00-00',561,'rnd,lite','',1671029235,8,'0000-00-00 00:00:00','0','user7','',1353,0,'','0','offline','zh-cn'),(9,1,'inside',1,'user8','a0933c1218a4e745bacdcf572b10eba7','qa','用户8','','','user8','/home/z/tmp/9.png','1994-05-09','f','10161016@gmail.com','Skype','458693556','','13926303965','','','slack','whatsApp','','','','','','0000-00-00',1871,'rnd,lite','',1671032835,9,'0000-00-00 00:00:00','0','user8','',9391,0,'','0','offline','zh-cn'),(10,1,'inside',1,'user9','dcf859ce8dd8f998bdfe4ae6c22c329e','qa','用户9','','','user9','/home/z/user/10.png','1994-07-08','m','10181018@qq.com','Skype','913491241','','15775942596','','','slack','whatsApp','','','','','','0000-00-00',7976,'rnd,lite','',1671036435,10,'0000-00-00 00:00:00','0','user9','',7843,0,'','0','offline','zh-cn');
/*!40000 ALTER TABLE `zt_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-14 15:53:41
