ALTER TABLE  `zt_user` ADD  `fails` TINYINT( 5 ) NOT NULL AFTER  `maxLogin` ,
ADD  `locked` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `fails`;
