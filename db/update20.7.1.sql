DROP VIEW IF EXISTS `ztv_dayuserlogin`;
DROP VIEW IF EXISTS `ztv_dayeffort`;
DROP VIEW IF EXISTS `ztv_daystoryopen`;
DROP VIEW IF EXISTS `ztv_daystoryclose`;
DROP VIEW IF EXISTS `ztv_daytaskopen`;
DROP VIEW IF EXISTS `ztv_daytaskfinish`;
DROP VIEW IF EXISTS `ztv_daybugopen`;
DROP VIEW IF EXISTS `ztv_daybugresolve`;
DROP VIEW IF EXISTS `ztv_dayactions`;

CREATE VIEW `ztv_dayuserlogin` AS select COUNT(1) AS `userlogin`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'user') and (`zt_action`.`action` = 'login')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_dayeffort` AS select round(sum(`zt_effort`.`consumed`),1) AS `consumed`,`zt_effort`.`date` AS `date` from `zt_effort` group by `zt_effort`.`date`;
CREATE VIEW `ztv_daystoryopen` AS select COUNT(1) AS `storyopen`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'story') and (`zt_action`.`action` = 'opened')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_daystoryclose` AS select COUNT(1) AS `storyclose`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'story') and (`zt_action`.`action` = 'closed')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_daytaskopen` AS select COUNT(1) AS `taskopen`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'task') and (`zt_action`.`action` = 'opened')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_daytaskfinish` AS select COUNT(1) AS `taskfinish`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'task') and (`zt_action`.`action` = 'finished')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_daybugopen` AS select COUNT(1) AS `bugopen`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'bug') and (`zt_action`.`action` = 'opened')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_daybugresolve` AS select COUNT(1) AS `bugresolve`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` where ((`zt_action`.`objectType` = 'bug') and (`zt_action`.`action` = 'resolved')) group by CAST(`zt_action`.`date` AS DATE);
CREATE VIEW `ztv_dayactions` AS select COUNT(1) AS `actions`,CAST(`zt_action`.`date` AS DATE) AS `day` from `zt_action` group by CAST(`zt_action`.`date` AS DATE);

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'job', 'trigger' FROM `zt_grouppriv` WHERE `module` = 'job' AND `method` = 'create';

ALTER TABLE `zt_job` ADD `autoRun` enum('0', '1') NOT NULL DEFAULT '1' AFTER `engine`;
ALTER TABLE `zt_job` ADD `triggerActions` varchar(255) NOT NULL DEFAULT '' AFTER `comment`;
UPDATE `zt_job` SET `autoRun` = '0' WHERE `triggerType` != '';

UPDATE `zt_action` SET `action` = 'imported' WHERE `objectType` = 'job' AND `action` = 'created';
UPDATE `zt_actionrecent` SET `action` = 'imported' WHERE `objectType` = 'job' AND `action` = 'created';

ALTER TABLE `zt_compile` MODIFY `logs` longtext NULL;
