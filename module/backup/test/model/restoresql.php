#!/usr/bin/env php
<?php

/**

title=测试 backupModel::restoreSQL();
timeout=0
cid=15142

- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/backup_测试-2024.sql' 属性result @1
- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/test_backup.sql' 属性result @~~
- 执行backupTest模块的restoreSQLTest方法，参数是'/nonexistent/path/backup.sql' 属性result @~~
- 执行backupTest模块的restoreSQLTest方法，参数是'' 属性result @~~
- 执行backupTest模块的restoreSQLTest方法，参数是'/tmp/invalid.txt' 属性result @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$sql = <<<EOT
SET NAMES utf8mb4;
DROP TABLE IF EXISTS `jiratmprelation`;
CREATE TABLE `jiratmprelation` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
INSERT INTO `jiratmprelation`(`id`,`AType`,`AID`,`BType`,`BID`,`extra`) VALUES ('1','jcustomfield','customfield_10001','zworkflowfield','testfield1','testmodule'),
('2','jcustomfield','customfield_10002','zworkflowfield','jirafieldgiccdcegabcdi','testmodule'),
('3','jcustomfield','customfield_10004','zworkflowfield','jirafieldgiccdcegabjgf','testmodule');
EOT;

file_put_contents('/tmp/backup_测试-2024.sql', $sql);

$backupTest = new backupModelTest();

r($backupTest->restoreSQLTest('/tmp/backup_测试-2024.sql')) && p('result') && e('1');
r($backupTest->restoreSQLTest('/tmp/test_backup.sql')) && p('result') && e('~~');
r($backupTest->restoreSQLTest('/nonexistent/path/backup.sql')) && p('result') && e('~~');
r($backupTest->restoreSQLTest('')) && p('result') && e('~~');
r($backupTest->restoreSQLTest('/tmp/invalid.txt')) && p('result') && e('~~');