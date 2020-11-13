ALTER TABLE `zt_workestimation` 
MODIFY COLUMN `scale` decimal(10, 2) UNSIGNED NOT NULL AFTER `PRJ`,
MODIFY COLUMN `productivity` decimal(10, 2) UNSIGNED NOT NULL AFTER `scale`,
MODIFY COLUMN `duration` decimal(10, 2) UNSIGNED NOT NULL AFTER `productivity`,
MODIFY COLUMN `unitLaborCost` decimal(10, 2) UNSIGNED NOT NULL AFTER `duration`,
MODIFY COLUMN `totalLaborCost` decimal(10, 2) UNSIGNED NOT NULL AFTER `unitLaborCost`,
MODIFY COLUMN `dayHour` decimal(10, 2) NULL DEFAULT NULL AFTER `deleted`;
