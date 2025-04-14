CREATE TABLE IF NOT EXISTS `zt_deliverable` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(30) NULL,
  `method` varchar(30) NULL,
  `model` varchar(255) NULL,
  `type` enum('doc','file') NULL DEFAULT 'file',
  `desc` text NULL,
  `files` varchar(255) NULL,
  `createdBy` varchar(30) NULL,
  `createdDate` date NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;