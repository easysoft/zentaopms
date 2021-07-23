ALTER TABLE `zt_job`
ADD `engine` varchar(20) NOT NULL AFTER `frame`,
CHANGE `jkHost` `server` mediumint(0) UNSIGNED NOT NULL AFTER `frame`,
CHANGE `jkJob` `pipeline` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `server`;

