ALTER TABLE `zt_task`   ADD COLUMN `docs` text NULL AFTER `fromIssue`;
ALTER TABLE `zt_storyspec`  ADD COLUMN `docs` text NULL AFTER `files`;
ALTER TABLE `zt_storyspec`  ADD COLUMN `docVersions` text NULL AFTER `docs`;
ALTER TABLE `zt_design` ADD COLUMN `docs` text NULL AFTER `storyVersion`;
