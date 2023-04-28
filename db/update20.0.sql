ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `module`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;

ALTER TABLE `zt_todo`  CHANGE `idvalue` `objectID` mediumint(8) unsigned default '0' NOT NULL AFTER `type`;

UPDATE `zt_block` SET `dashboard` = `module`;
UPDATE `zt_block` SET `module` = IF(`source` != '', `source`, `code`);

