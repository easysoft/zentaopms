CREATE TABLE `zt_kanbancell` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `kanban` mediumint(8) NOT NULL,
  `lane` mediumint(8) NOT NULL,
  `column` mediumint(8) NOT NULL,
  `type` char(30) NOT NULL,
  `cards` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `card_group` (`kanban`,`type`,`lane`,`column`)
) ENGINE=MyISAM;

ALTER TABLE `zt_kanban` ADD `displayCards` smallint(6) NOT NULL default '0' AFTER `order`;
ALTER TABLE `zt_project` ADD `displayCards` smallint(6) NOT NULL default '0' AFTER `order`;

UPDATE `zt_grouppriv` SET `method` = 'taskKanban' WHERE `module` = 'execution' AND `method` = 'kanban';
