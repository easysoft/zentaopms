CREATE TABLE IF NOT EXISTS `zt_actionproduct` (
  `action` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE INDEX `action_product` ON `zt_actionproduct`(`action`, `product`);

ALTER TABLE `zt_actionrecent` ADD INDEX `vision_date` (`vision`, `date`);
ALTER TABLE `zt_actionrecent` DROP INDEX `date`;

ALTER TABLE `zt_action` ADD INDEX `vision_date` (`vision`, `date`);
ALTER TABLE `zt_action` DROP INDEX `date`;
