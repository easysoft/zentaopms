#!/usr/bin/env php
<?php

/**

title=测试 convertTao::getJiraUser();
timeout=0
cid=15854

- 步骤1：正常情况，返回1条记录 @1
- 步骤5：验证返回结果中AID为aabbccdd的记录的键值属性aabbccdd @user1
- 步骤5：验证返回结果中AID为eeffgghh的记录的键值属性eeffgghh @user2
- 步骤5：验证返回结果中AID为ddd的记录的键值属性ddd @~~
- 步骤5：验证返回结果中AID为fff的记录的键值属性fff @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 创建临时表
global $tester;
$sql = <<<EOT
CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL,
  `AID` char(100) NOT NULL,
  `BType` char(30) NOT NULL,
  `BID` char(100) NOT NULL,
  `extra` char(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relation` (`AType`,`BType`,`AID`,`BID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOT;

try {
    $tester->dbh->exec($sql);
} catch (Exception $e) {
    // 表可能已存在，忽略错误
}

su('admin');
$convertTest = new convertTaoTest();

try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('aabbccdd', 'juser', 'user1', 'zuser', ''), ('10001', 'jissue', '1', 'zbug', '')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

r(count($convertTest->getJiraUserTest())) && p() && e('1'); // 步骤1：正常情况，返回1条记录

try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('aabbccdd', 'juser', 'user1', 'zuser', ''), ('eeffgghh', 'juser', 'user2', 'zuser', '')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}
r($convertTest->getJiraUserTest()) && p('aabbccdd') && e('user1'); // 步骤5：验证返回结果中AID为aabbccdd的记录的键值
r($convertTest->getJiraUserTest()) && p('eeffgghh') && e('user2'); // 步骤5：验证返回结果中AID为eeffgghh的记录的键值
r($convertTest->getJiraUserTest()) && p('ddd')      && e('~~');    // 步骤5：验证返回结果中AID为ddd的记录的键值
r($convertTest->getJiraUserTest()) && p('fff')      && e('~~');    // 步骤5：验证返回结果中AID为fff的记录的键值
