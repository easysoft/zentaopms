ALTER TABLE `zt_story` CHANGE `plan` `plan` text COLLATE 'utf8_general_ci' NOT NULL AFTER `module`;
UPDATE `zt_story` SET `plan`='' WHERE `plan`='0';
