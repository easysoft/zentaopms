ALTER TABLE `zt_task` ADD `isParent` tinyint(1) NOT NULL DEFAULT 0 after `parent`;
ALTER TABLE `zt_task` ADD `path` text NULL after `isParent`;

UPDATE `zt_task` SET `path` = concat(',', id, ',') WHERE `parent` <= 0;
UPDATE `zt_task` SET `path` = concat(',', parent, ',', id, ',') WHERE `parent` > 0;

UPDATE `zt_task` SET `isParent` = 1 WHERE `parent` = -1;
UPDATE `zt_task` SET `parent`   = 0 WHERE `parent` = -1;
