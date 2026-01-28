#!/usr/bin/env php
<?php

/**

title=测试 convertTao::getIssueData();
timeout=0
cid=15854

- 步骤1：正常情况，返回2条记录 @2
- 步骤2：BID为空被过滤，返回1条记录 @1
- 步骤3：extra不为issue被过滤，返回1条记录 @1
- 步骤4：无符合条件记录，返回0条 @0
- 步骤5：验证返回结果中AID为1002的记录的键值第1002条的AID属性 @1002

*/

// 1. 导入依赖（路径固定，不可修改）
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

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 测试步骤1：正常情况 - 插入符合条件的数据
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('1001', 'jissue', '2001', 'zissue', 'issue'), ('1002', 'jissue', '2002', 'zissue', 'issue')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

r(count($convertTest->getIssueDataTest())) && p() && e('2'); // 步骤1：正常情况，返回2条记录

// 6. 测试步骤2：BID为空的记录应该被过滤
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('1001', 'jissue', '2001', 'zissue', 'issue'), ('1002', 'jissue', '', 'zissue', 'issue')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

r(count($convertTest->getIssueDataTest())) && p() && e('1'); // 步骤2：BID为空被过滤，返回1条记录

// 7. 测试步骤3：extra不等于issue的记录应该被过滤
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('1001', 'jissue', '2001', 'zissue', 'issue'), ('1002', 'jissue', '2002', 'zissue', 'other')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

r(count($convertTest->getIssueDataTest())) && p() && e('1'); // 步骤3：extra不为issue被过滤，返回1条记录

// 8. 测试步骤4：空结果情况
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('1001', 'jissue', '', 'zissue', 'issue'), ('1002', 'jissue', '2002', 'zissue', 'other')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

r(count($convertTest->getIssueDataTest())) && p() && e('0'); // 步骤4：无符合条件记录，返回0条

// 9. 测试步骤5：多条记录以AID为键
try {
    $tester->dbh->exec("DELETE FROM jiratmprelation");
    $tester->dbh->exec("INSERT INTO jiratmprelation(AID, AType, BID, BType, extra) VALUES ('1001', 'jissue', '2001', 'zissue', 'issue'), ('1002', 'jissue', '2002', 'zissue', 'issue'), ('1003', 'jissue', '2003', 'zissue', 'issue')");
} catch (Exception $e) {
    // 忽略可能的数据库错误
}

$result = $convertTest->getIssueDataTest();
r($result) && p('1002:AID') && e('1002'); // 步骤5：验证返回结果中AID为1002的记录的键值