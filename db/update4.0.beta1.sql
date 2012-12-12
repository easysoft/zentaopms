INSERT INTO `zt_groupPriv` (`company`, `group`, `module`, `method`) VALUES
(1, 1, 'bug', 'batchEdit'),
(1, 2, 'bug', 'batchEdit'),
(1, 3, 'bug', 'batchEdit'),
(1, 4, 'bug', 'batchEdit'),
(1, 5, 'bug', 'batchEdit'),
(1, 1, 'testcase', 'batchEdit'),
(1, 2, 'testcase', 'batchEdit'),
(1, 3, 'testcase', 'batchEdit'),
(1, 4, 'testcase', 'batchEdit'),
(1, 5, 'testcase', 'batchEdit'),
(1, 1, 'story', 'batchEdit'),
(1, 2, 'story', 'batchEdit'),
(1, 3, 'story', 'batchEdit'),
(1, 4, 'story', 'batchEdit'),
(1, 5, 'story', 'batchEdit'),
(1, 1, 'todo', 'batchEdit'),
(1, 2, 'todo', 'batchEdit'),
(1, 3, 'todo', 'batchEdit'),
(1, 4, 'todo', 'batchEdit'),
(1, 5, 'todo', 'batchEdit');

ALTER TABLE  `zt_testtask` ADD  `pri` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `owner`;
