UPDATE `zt_ai_miniprogram` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_prompt` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_promptrole` SET `model` = 0 WHERE `model` IS NULL;

ALTER TABLE `zt_ai_miniprogram` MODIFY `model` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_prompt` MODIFY `model` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_promptrole` MODIFY `model` varchar(255) NOT NULL DEFAULT '';

UPDATE `zt_ai_miniprogram` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_prompt` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_promptrole` SET `model` = '' WHERE `model` = '0';
