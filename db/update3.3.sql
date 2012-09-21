ALTER TABLE  `zt_user` ADD  `fails` TINYINT( 5 ) NOT NULL DEFAULT  '0' AFTER  `last` ,
ADD  `locked` DATE NOT NULL DEFAULT  '0000-00-00' AFTER  `fails`;
