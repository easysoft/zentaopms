ALTER TABLE `zt_story` ADD `parent` mediumint NOT NULL DEFAULT '0' AFTER `id`;

CREATE TABLE `zt_relation` (
  `id` int(8) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  `program` mediumint(8) NOT NULL,
  `product` mediumint(8) NOT NULL,
  `project` mediumint(8) NOT NULL,
  `AType` char(30) NOT NULL,
  `AID` mediumint(8) NOT NULL,
  `AVersion` char(30) NOT NULL,
  `relation` char(30) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` mediumint(8) NOT NULL,
  `BVersion` char(30) NOT NULL,
  `extra` char(30) NOT NULL,
  UNIQUE KEY `relation` (`relation`,`AType`,`BType`, `AID`, `BID`)
) ENGINE='MyISAM' DEFAULT CHARSET=utf8;
