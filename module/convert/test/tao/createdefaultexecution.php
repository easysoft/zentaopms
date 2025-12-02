#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultExecution();
timeout=0
cid=15835

- 步骤1:正常情况,无团队成员 @1
- 步骤2:不同项目无团队成员 @1
- 步骤3:第三个项目无团队成员 @1
- 步骤4:第四个项目无团队成员 @1
- 步骤5:第五个项目无团队成员 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

global $tester;

/* Create jiratmprelation table if not exists. */
$sql = "CREATE TABLE IF NOT EXISTS `jiratmprelation`(
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `AType` char(30) NOT NULL DEFAULT '',
  `AID` char(100) NOT NULL DEFAULT '',
  `BType` char(30) NOT NULL DEFAULT '',
  `BID` char(100) NOT NULL DEFAULT '',
  `extra` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

try {
    $tester->dbh->exec($sql);
    $tester->dbh->exec('TRUNCATE TABLE jiratmprelation');
} catch (Exception $e) {
    // Ignore table creation error
}

$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->code->range('project{1-10}');
$table->desc->range('项目描述{1-10}');
$table->status->range('wait,doing,done');
$table->type->range('project');
$table->PM->range('admin');
$table->openedBy->range('admin');
$table->openedDate->range('`2024-01-01 00:00:00`');
$table->begin->range('`2024-01-01`');
$table->end->range('`2024-12-31`');
$table->gen(10);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->role->range('admin,dev,qa,pm,td');
$userTable->gen(5);

su('admin');

$convertTest = new convertTest();

r($convertTest->createDefaultExecutionTest(1001, 1, array())) && p() && e('1'); // 步骤1:正常情况,无团队成员
r($convertTest->createDefaultExecutionTest(1002, 2, array())) && p() && e('1'); // 步骤2:不同项目无团队成员
r($convertTest->createDefaultExecutionTest(1003, 3, array())) && p() && e('1'); // 步骤3:第三个项目无团队成员
r($convertTest->createDefaultExecutionTest(1004, 4, array())) && p() && e('1'); // 步骤4:第四个项目无团队成员
r($convertTest->createDefaultExecutionTest(1005, 5, array())) && p() && e('1'); // 步骤5:第五个项目无团队成员