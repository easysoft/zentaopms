ALTER TABLE `zt_feedback` ADD `repeatFeedback` mediumint(8) NOT NULL DEFAULT 0 AFTER `feedbackBy`;

ALTER TABLE `zt_assetlib` ADD `order` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `desc`;
ALTER TABLE `zt_testsuite` ADD `order` SMALLINT  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `type`;