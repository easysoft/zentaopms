CREATE TABLE `zt_testtaskproduct` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product` int NOT NULL,
  `build` int NOT NULL,
  `task` int NOT NULL,
  `execution` int NOT NULL default '0',
  `project` int NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_build` (`product`,`build`,`task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_testtask` ADD `joint` enum('0','1') NOT NULL DEFAULT '0' AFTER `build`;
