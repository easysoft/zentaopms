CREATE TABLE IF NOT EXISTS `zt_deliverable` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(30) NULL,
  `method` varchar(30) NULL,
  `model` text NULL,
  `type` enum('doc','file') NULL DEFAULT 'file',
  `desc` text NULL,
  `files` varchar(255) NULL,
  `createdBy` varchar(30) NULL,
  `createdDate` date NULL,
  `lastEditedBy` varchar(30) NULL,
  `lastEditedDate` date NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_workflowgroup` ADD COLUMN `deliverable` text NULL AFTER `editedDate`;
ALTER TABLE `zt_project` ADD COLUMN `taskDateLimit` varchar(5) NOT NULL DEFAULT 'auto' AFTER `linkType`;
ALTER TABLE `zt_project` ADD COLUMN `deliverable` text NULL AFTER `maxColWidth`;

ALTER TABLE `zt_task`   ADD COLUMN `docs` text NULL AFTER `fromIssue`;
ALTER TABLE `zt_story`  ADD COLUMN `docs` text NULL AFTER `linkRequirements`;
ALTER TABLE `zt_design` ADD COLUMN `docs` text NULL AFTER `storyVersion`;
