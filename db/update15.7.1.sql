Alter table zt_mr ADD `approvalStatus` char(30) NOT NULL,
ADD `needApproved` enum('0','1') NOT NULL DEFAULT '0',
ADD `needCI` enum('0','1') NOT NULL DEFAULT '0',
ADD `repoID` mediumint(8) unsigned NOT NULL,
ADD `jobID` mediumint(8) unsigned NOT NULL,
ADD `compileID` mediumint(8) unsigned NOT NULL,
ADD `compileStatus` char(30) NOT NULL;
