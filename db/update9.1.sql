ALTER TABLE `zt_extension`
CHANGE `dirs` `dirs` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `depends`,
CHANGE `files` `files` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `dirs`;
