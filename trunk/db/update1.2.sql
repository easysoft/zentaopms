-- 2010-09-12 doc table.
UPDATE `zt_doc` SET `type` = 'file';
ALTER TABLE `zt_doc` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `digest`;
