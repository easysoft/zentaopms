ALTER TABLE `zt_job`
ADD `engine` varchar(20) NOT NULL AFTER `frame`,
CHANGE `jkHost` `server` mediumint(0) UNSIGNED NOT NULL AFTER `frame`,
CHANGE `jkJob` `pipeline` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `server`;

UPDATE `zt_job` SET `engine` = 'jenkins' WHERE `engine` = '';
UPDATE `zt_cron` SET `remark` = '执行DevOps构建任务'     WHERE  `remark` = '执行Jenkins任务';
UPDATE `zt_cron` SET `remark` = '同步DevOps构建任务状态' WHERE  `remark` = '同步Jenkins任务状态';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES
(1,'doc','tableContents'),
(2,'doc','tableContents'),
(3,'doc','tableContents'),
(4,'doc','tableContents'),
(5,'doc','tableContents'),
(6,'doc','tableContents'),
(7,'doc','tableContents'),
(8,'doc','tableContents'),
(9,'doc','tableContents'),
(10,'doc','tableContents'),
(11,'doc','tableContents');
