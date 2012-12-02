ALTER TABLE  `zt_user` ADD  `fails` TINYINT( 5 ) NOT NULL DEFAULT  '0' AFTER  `last` ,
ADD  `locked` DATE NOT NULL DEFAULT  '0000-00-00' AFTER  `fails`;
ALTER TABLE  `zt_user` CHANGE  `locked`  `locked` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00';

ALTER TABLE  `zt_case` CHANGE  `pri`  `pri` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '3';

ALTER TABLE  `zt_action` ADD  `read` ENUM(  '0',  '1' ) NOT NULL DEFAULT  '0';
UPDATE `zt_action` SET  `read` =  '1';

CREATE TABLE IF NOT EXISTS `zt_webapp` (
  `id` mediumint(9) NOT NULL auto_increment,
  `appid` mediumint(9) NOT NULL,
  `module` mediumint(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(30) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `target` varchar(50) NOT NULL,
  `size` varchar(20) NOT NULL,
  `desc` text NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `addType` varchar(20) NOT NULL default 'system',
  `views` mediumint(9) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
