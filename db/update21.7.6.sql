UPDATE `zt_ai_miniprogram` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_prompt` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_promptrole` SET `model` = 0 WHERE `model` IS NULL;

UPDATE `zt_ai_miniprogram` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_prompt` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_promptrole` SET `model` = '' WHERE `model` = '0';

ALTER TABLE `zt_ai_miniprogram` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_prompt` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_promptrole` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';
