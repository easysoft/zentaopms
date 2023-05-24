ALTER TABLE `zt_project`
ADD `progress` decimal(5,2) NOT NULL DEFAULT '0' AFTER `realDuration`,
ADD `estimate` float NOT NULL DEFAULT '0' AFTER `progress`,
ADD `left` float NOT NULL DEFAULT '0' AFTER `estimate`,
ADD `consumed` float NOT NULL DEFAULT '0' AFTER `left`,
ADD `teamCount` int NOT NULL DEFAULT '0' AFTER `consumed`;

REPLACE INTO zt_privrelation (`priv`, `type`, `relationPriv`) VALUES (59, 'depend', 41),(62, 'depend', 41),(60, 'depend', 41),(61, 'depend', 41),(56, 'depend', 41),(54, 'depend', 41),(53, 'depend', 41),(57, 'depend', 41),(55, 'depend', 41);
