ALTER TABLE `zt_workestimation` 
MODIFY COLUMN `scale` decimal(10, 2) UNSIGNED NOT NULL AFTER `PRJ`,
MODIFY COLUMN `productivity` decimal(10, 2) UNSIGNED NOT NULL AFTER `scale`,
MODIFY COLUMN `duration` decimal(10, 2) UNSIGNED NOT NULL AFTER `productivity`,
MODIFY COLUMN `unitLaborCost` decimal(10, 2) UNSIGNED NOT NULL AFTER `duration`,
MODIFY COLUMN `totalLaborCost` decimal(10, 2) UNSIGNED NOT NULL AFTER `unitLaborCost`,
MODIFY COLUMN `dayHour` decimal(10, 2) NULL DEFAULT NULL AFTER `deleted`;

ALTER TABLE `zt_project` ADD `storyConcept` smallint(5) unsigned NOT NULL AFTER `days`;
ALTER TABLE `zt_product` ADD `storyConcept` smallint(5) NOT NULL AFTER `RD`;

INSERT INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-cn', 'custom', 'URList', '1', '用户需求', '1'),
('zh-cn', 'custom', 'URList', '2', '用需', '1'),
('zh-cn', 'custom', 'URList', '3', '需求', '1'),
('zh-cn', 'custom', 'URList', '4', '史诗', '1'),
('zh-cn', 'custom', 'SRList', '1', '软件需求', '1'),
('zh-cn', 'custom', 'SRList', '2', '软需', '1'),
('zh-cn', 'custom', 'SRList', '3', '故事', '1'),
('zh-cn', 'custom', 'SRList', '4', '故事', '1');

TRUNCATE TABLE `zt_block`;
DELETE FROM `zt_config` WHERE `key` = 'blockInited';
