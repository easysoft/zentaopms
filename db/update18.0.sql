ALTER TABLE `zt_image` ADD `localName` VARCHAR(64) NOT NULL AFTER `name`;
ALTER TABLE `zt_image` ADD `restoreDate` datetime NOT NULL AFTER `createdDate`;

ALTER TABLE `zt_ticket` MODIFY `resolution` text NOT NULL;

ALTER TABLE `zt_chart` CHANGE `fields` `fields` mediumtext NULL AFTER `filters`;
