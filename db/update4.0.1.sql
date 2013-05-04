ALTER TABLE  `zt_user` CHANGE  `msn`  `skype` CHAR( 90 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO `zt_groupPriv`(`company`, `group`, `module`, `method`) SELECT * FROM `zt_groupPriv` WHERE `module`='company' and `method`='edit'
