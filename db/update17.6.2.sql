CREATE TABLE IF NOT EXISTS `zt_ticket` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `module` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL,
  `desc` text NOT NULL,
  `openedBuild` varchar(255) NOT NULL,
  `feedback` mediumint(8) NOT NULL,
  `assignedTo` varchar(255) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `realStarted` datetime NOT NULL,
  `startedBy` varchar(255) NOT NULL,
  `startedDate` datetime NOT NULL,
  `deadline` date NOT NULL,
  `pri` tinyint unsigned NOT NULL DEFAULT '0',
  `estimate` float unsigned NOT NULL,
  `left` float unsigned NOT NULL,
  `status` varchar(30) NOT NULL,
  `openedBy` varchar(30) NOT NULL,
  `openedDate` datetime NOT NULL,
  `activatedCount` int(10) NOT NULL,
  `activatedBy` varchar(30) NOT NULL,
  `activatedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `finishedBy` varchar(30) NOT NULL,
  `finishedDate` datetime NOT NULL,
  `resolvedBy` varchar(30) NOT NULL,
  `resolvedDate` datetime NOT NULL,
  `resolution` varchar(1000) NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `repeatTicket` mediumint(8) NOT NULL DEFAULT '0',
  `mailto` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  key `product` (`product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_ticketsource` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ticketId` mediumint(8) unsigned NOT NULL,
  `customer` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `notifyEmail` varchar(100) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  key `ticketId` (`ticketId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_ticketrelation` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `ticketId` mediumint unsigned NOT NULL,
  `objectId` mediumint NOT NULL,
  `objectType` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticketId` (`ticketId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_product` ADD `ticket` varchar(30) NOT NULL AFTER `feedback`;
ALTER TABLE `zt_kanban` ADD `colWidth` smallint(4) NOT NULL DEFAULT '264' AFTER `fluidBoard`;
ALTER TABLE `zt_kanban` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
ALTER TABLE `zt_kanban` ADD `maxColWidth` smallint(4) NOT NULL DEFAULT '384' AFTER `minColWidth`;
ALTER TABLE `zt_project` ADD `colWidth` smallint(4) NOT NULL DEFAULT '264' AFTER `fluidBoard`;
ALTER TABLE `zt_project` ADD `minColWidth` smallint(4) NOT NULL DEFAULT '200' AFTER `colWidth`;
ALTER TABLE `zt_project` ADD `maxColWidth` smallint(4) NOT NULL DEFAULT '384' AFTER `minColWidth`;

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', 'global', 'syncProduct', '{"feedback":{},"ticket":{}}');

ALTER TABLE `zt_feedback` ADD `pri` tinyint unsigned NOT NULL DEFAULT 2 AFTER `desc`;
ALTER TABLE `zt_feedback` ADD `source` varchar(255) NOT NULL AFTER `notifyEmail`;
