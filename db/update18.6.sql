ALTER TABLE `zt_doc` ADD `editedList` text NULL AFTER `editingDate`;

-- DROP TABLE IF EXISTS `zt_actionlatest`;
CREATE TABLE IF NOT EXISTS `zt_actionlatest` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` text NULL,
  `project` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `execution` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `actor` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(80) NOT NULL DEFAULT '',
  `date` datetime NULL,
  `comment` text NULL,
  `extra` text NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `efforted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `date`     ON `zt_actionlatest`(`date`);
CREATE INDEX `actor`    ON `zt_actionlatest`(`actor`);
CREATE INDEX `project`  ON `zt_actionlatest`(`project`);
CREATE INDEX `action`   ON `zt_actionlatest`(`action`);
CREATE INDEX `objectID` ON `zt_actionlatest`(`objectID`);

INSERT INTO `zt_actionlatest`(`objectType`,`objectID`,`product`,`project`,`execution`,`actor`,`action`,`date`,`comment`,`extra`,`read`,`vision`,`efforted`)
SELECT `objectType`,`objectID`,`product`,`project`,`execution`,`actor`,`action`,`date`,`comment`,`extra`,`read`,`vision`,`efforted` FROM `zt_action`
WHERE `date` >= DATE(DATE_SUB(NOW(), INTERVAL 1 MONTH));

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('15', '0', '*', '*', '*', 'moduleName=action&methodName=cleanActions', '清除超过一个月的动态',    'zentao', 1, 'normal');

ALTER TABLE `zt_notify`
MODIFY COLUMN `toList` text NOT NULL AFTER `action`,
MODIFY COLUMN `subject` text NOT NULL AFTER `ccList`,
DROP INDEX `objectType_toList_status`,
ADD INDEX `objectType`(`objectType` ASC),
ADD INDEX `status`(`status` ASC);

CREATE INDEX deleted ON zt_bug (deleted);
CREATE INDEX product_status_deleted ON zt_bug (product,status,deleted);

UPDATE `zt_cron` SET `m` = '*/1' WHERE `command` in ('moduleName=mail&methodName=asyncSend', 'moduleName=webhook&methodName=asyncSend') and `type` = 'zentao';
