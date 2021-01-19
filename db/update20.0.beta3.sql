INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES 
(1,  'product', 'dashboard'),
(2,  'product', 'dashboard'),
(3,  'product', 'dashboard'),
(4,  'product', 'dashboard'),
(5,  'product', 'dashboard'),
(6,  'product', 'dashboard'),
(7,  'product', 'dashboard'),
(8,  'product', 'dashboard'),
(9,  'product', 'dashboard'),
(10, 'product', 'dashboard'),
(11, 'product', 'dashboard');

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'unitList', 'CNY,USD');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'mainCurrency', 'CNY');

ALTER TABLE `zt_project` DROP `storyConcept`;
ALTER TABLE `zt_product` DROP `storyConcept`;

ALTER TABLE `zt_user` CHANGE `avatar` `avatar` text NOT NULL AFTER `commiter`;
