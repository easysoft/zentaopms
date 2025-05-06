ALTER TABLE `zt_task`   ADD COLUMN `docs` text NULL AFTER `fromIssue`;
ALTER TABLE `zt_story`  ADD COLUMN `docs` text NULL AFTER `linkRequirements`;
ALTER TABLE `zt_design` ADD COLUMN `docs` text NULL AFTER `storyVersion`;
