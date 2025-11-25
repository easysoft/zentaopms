ALTER TABLE `zt_story` CHANGE `plan` `plan` text NOT NULL AFTER `module`;
UPDATE `zt_story` SET `plan`='' WHERE `plan`='0';
