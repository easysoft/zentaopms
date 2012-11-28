ALTER TABLE  `zt_user` ADD  `fails` TINYINT( 5 ) NOT NULL DEFAULT  '0' AFTER  `last` ,
ADD  `locked` DATE NOT NULL DEFAULT  '0000-00-00' AFTER  `fails`;
ALTER TABLE  `zt_user` CHANGE  `locked`  `locked` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00';

ALTER TABLE `zt_case` CHANGE `pri` `pri` TINYINT( 3 ) UNSIGNED NOT NULL 

ALTER TABLE  `zt_action` ADD  `read` ENUM(  '0',  '1' ) NOT NULL DEFAULT  '0';
UPDATE `zt_action` SET  `read` =  '1'
