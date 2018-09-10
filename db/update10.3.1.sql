CREATE TABLE `zt_userview` (
  `account` char(30) NOT NULL,
  `products` text NOT NULL,
  `projects` text NOT NULL,
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
