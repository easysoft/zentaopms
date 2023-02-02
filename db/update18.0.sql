ALTER TABLE `zt_image` ADD `localName` VARCHAR(64) NOT NULL AFTER `name`;
ALTER TABLE `zt_image` ADD `restoreDate` datetime NOT NULL AFTER `createdDate`;
