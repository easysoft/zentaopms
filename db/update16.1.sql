CREATE TABLE `zt_cardgroup` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `kanban` mediumint(8) NOT NULL,
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(8) NOT NULL,
  `lane` mediumint(8) NOT NULL,
  `column` mediumint(8) NOT NULL,
  `order` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_group` (`kanban`,`objectType`,`objectID`,`lane`,`column`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanban` ADD `displayCards` smallint(6) NOT NULL AFTER `order`;
ALTER TABLE `zt_kanbancard` DROP COLUMN `lane`;
ALTER TABLE `zt_kanbancard` DROP COLUMN `column`;
