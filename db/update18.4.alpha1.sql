ALTER TABLE `zt_project`
ADD `progress` decimal(5,2) NOT NULL DEFAULT '0' AFTER `realDuration`,
ADD `estimate` float NOT NULL DEFAULT '0' AFTER `progress`,
ADD `left` float NOT NULL DEFAULT '0' AFTER `estimate`,
ADD `consumed` float NOT NULL DEFAULT '0' AFTER `left`;
