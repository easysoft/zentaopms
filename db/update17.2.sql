CREATE TABLE `zt_cfd` (
  `id` int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `execution` int(8) NOT NULL,
  `type` char(30) NOT NULL,
  `name` char(30) NOT NULL,
  `count` smallint NOT NULL,
  `date` date NOT NULL,
  UNIQUE KEY `execution_type_name_date` (`execution`,`type`,`name`,`date`)
) ENGINE=MyISAM;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('30', '23', '*', '*', '*', 'moduleName=execution&methodName=computecfd', '更新累积流图', 'zentao', 1, 'normal', '0000-00-00 00:00:00');

ALTER TABLE `zt_doc` CHANGE `assignedDate` `assignedDate` datetime NOT NULL AFTER `assignedTo`;

UPDATE `zt_approval` SET  `createdDate` = '';
ALTER TABLE `zt_approval` CHANGE `createdDate` `createdDate` datetime NOT NULL AFTER `createdBy`;

ALTER TABLE `zt_reviewissue` ADD `approval` MEDIUMINT  NOT NULL  AFTER `review`;

ALTER TABLE `zt_approvalnode` ADD INDEX `idx_reviewed_date` (`reviewedDate`);

