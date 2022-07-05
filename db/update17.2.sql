CREATE TABLE `zt_cfd` (
  `id` int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `execution` int(8) NOT NULL,
  `type` char(30) NOT NULL,
  `name` char(30) NOT NULL,
  `count` smallint NOT NULL,
  `date` date NOT NULL,
  UNIQUE KEY `execution_type_name_date` (`execution`,`type`,`name`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
