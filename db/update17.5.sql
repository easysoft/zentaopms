UPDATE `zt_story` SET `status` = 'reviewing' WHERE `status` = 'changed';
ALTER TABLE `zt_story` MODIFY `status` enum('','changed','active','draft','closed','reviewing') NOT NULL DEFAULT '' AFTER `estimate`;
