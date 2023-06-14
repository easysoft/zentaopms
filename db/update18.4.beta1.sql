INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES ('0','*','*','*','*','moduleName=misc&methodName=cleanCache', '清理缓存文件','zentao', 1, 'normal');

ALTER TABLE `zt_product`
ADD `draftStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `reviewer`,
ADD `activeStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `draftStories`,
ADD `changingStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `activeStories`,
ADD `reviewingStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `changingStories`,
ADD `finishedStories` mediumint NOT NULL DEFAULT '0' AFTER `reviewingStories`,
ADD `closedStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `finishedStories`,
ADD `totalStories` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedStories`,
ADD `unresolvedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `totalStories`,
ADD `closedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `unresolvedBugs`,
ADD `fixedBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `closedBugs`,
ADD `totalBugs` mediumint(8) NOT NULL DEFAULT '0' AFTER `fixedBugs`,
ADD `plans` mediumint(8) NOT NULL DEFAULT '0' AFTER `totalBugs`,
ADD `releases` mediumint(8) NOT NULL DEFAULT '0' AFTER `plans`;
