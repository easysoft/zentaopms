DROP TABLE IF EXISTS `zt_pipeline`;
CREATE TABLE `zt_pipeline` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `account` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  `private` char(32) NOT NULL DEFAULT '',
  `instanceID` int unsigned NOT NULL DEFAULT 0,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `deleted` tinyint unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
