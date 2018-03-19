ALTER TABLE `zt_user` CHANGE `realname` `realname` varchar(100) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `password`;
ALTER TABLE `zt_project` CHANGE `openedDate` `openedDate` datetime NOT NULL,
CHANGE `closedDate` `closedDate` datetime NOT NULL,
CHANGE `canceledDate` `canceledDate` datetime NOT NULL;
