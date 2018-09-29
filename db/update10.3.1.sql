CREATE TABLE IF NOT EXISTS `zt_userview` (
  `account` char(30) NOT NULL,
  `products` text NOT NULL,
  `projects` text NOT NULL,
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_entry` ADD `account` VARCHAR(30) NOT NULL DEFAULT '' AFTER `code`;
UPDATE `zt_doc` set editedDate=addedDate, editedBy=addedBy where editedBy = '';
