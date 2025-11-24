ALTER TABLE `zt_extension`
CHANGE `dirs` `dirs` mediumtext NOT NULL AFTER `depends`,
CHANGE `files` `files` mediumtext NOT NULL AFTER `dirs`;
