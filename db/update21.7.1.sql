ALTER TABLE `zt_project`
ADD `isTpl` enum('0','1') NOT NULL DEFAULT '0' AFTER `project`;

ALTER TABLE `zt_task`
ADD `isTpl` enum('0','1') NOT NULL DEFAULT '0' AFTER `isParent`;