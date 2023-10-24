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
-- Table structure for table `zt_product`
--

DROP TABLE IF EXISTS `zt_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zt_product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `program` mediumint(8) unsigned NOT NULL,
  `name` varchar(110) NOT NULL,
  `code` varchar(45) NOT NULL,
  `shadow` tinyint(1) unsigned NOT NULL,
  `bind` enum('0','1') NOT NULL DEFAULT '0',
  `line` mediumint(8) NOT NULL,
  `type` varchar(30) NOT NULL DEFAULT 'normal',
  `status` varchar(30) NOT NULL DEFAULT '',
  `subStatus` varchar(30) NOT NULL DEFAULT '',
  `desc` mediumtext NOT NULL,
  `PO` varchar(30) NOT NULL,
  `QD` varchar(30) NOT NULL,
  `RD` varchar(30) NOT NULL,
  `feedback` varchar(30) NOT NULL,
  `ticket` varchar(30) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `reviewer` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `createdVersion` varchar(20) NOT NULL,
  `order` mediumint(8) unsigned NOT NULL,
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acl` (`acl`),
  KEY `order` (`order`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zt_product`
--

LOCK TABLES `zt_product` WRITE;
/*!40000 ALTER TABLE `zt_product` DISABLE KEYS */;
INSERT INTO `zt_product` VALUES (1,0,'正常产品1','code1',0,'0',0,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po1','test1','dev1','','','open','','','po1','2023-01-14 00:00:00','15.6',5,'rnd','0'),(2,1,'正常产品2','code2',0,'0',1,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po2','test2','dev2','','','open','','','po2','2023-01-14 00:01:00','15.6',10,'rnd','0'),(3,2,'正常产品3','code3',0,'0',2,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po3','test3','dev3','','','open','','','po3','2023-01-14 00:02:00','15.6',15,'rnd','0'),(4,3,'正常产品4','code4',0,'0',3,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po4','test4','dev4','','','open','','','po4','2023-01-14 00:03:00','15.6',20,'rnd','0'),(5,4,'正常产品5','code5',0,'0',4,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po5','test5','dev5','','','open','','','po5','2023-01-14 00:04:00','15.6',25,'rnd','0'),(6,5,'正常产品6','code6',0,'0',5,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po6','test6','dev6','','','open','','','po6','2023-01-14 00:05:00','15.6',30,'rnd','0'),(7,6,'正常产品7','code7',0,'0',6,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po7','test7','dev7','','','open','','','po7','2023-01-14 00:06:00','15.6',35,'rnd','0'),(8,7,'正常产品8','code8',0,'0',7,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po8','test8','dev8','','','open','','','po8','2023-01-14 00:07:00','15.6',40,'rnd','0'),(9,8,'正常产品9','code9',0,'0',8,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po9','test9','dev9','','','open','','','po9','2023-01-14 00:08:00','15.6',45,'rnd','0'),(10,9,'正常产品10','code10',0,'0',9,'normal','normal','0','<div> <p><h1>一、禅道项目管理软件是做什么的？</h1> 禅道由 青岛易软天创网络科技有限公司开发，国产开源项目管理软件。它集产品管理、项目管理、质量管理、文档管理、组织管理和事务管理于一体，是一款专业的研发项目管理软件，完整覆盖了研发项目管理的核心流程。禅道管理思想注重实效，功能完备丰富，操作简洁高效，界面美观大方，搜索功能强大，统计报表丰富多样，软件架构合理，扩展灵活，有完善的API可以调用。禅道，专注研发项目管理 </p> <p>我是数字符号23@#$%#^$ </p> <p>我是英文dashcuscbrewg </p> </div>','po10','test10','dev10','','','open','','','po10','2023-01-14 00:09:00','15.6',50,'rnd','0');
/*!40000 ALTER TABLE `zt_product` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-14 14:04:25
