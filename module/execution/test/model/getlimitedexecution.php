#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(10);

$team = zenData('team');
$team->root->range('101-105');
$team->type->range('project{2},execution{3}');
$team->account->range('user1,user2,user3,user4,user5');
$team->limited->range('yes{4},no{1}');
$team->gen(5);

su('admin');

/**

title=测试 executionModel::getLimitedExecution();
timeout=0
cid=16326

- 测试管理员用户权限 @1
- 测试受限项目成员权限 @103
- 测试受限执行成员权限 @103,104
- 测试混合权限成员 @103
- 测试无权限普通用户 @

*/

$executionTest = new executionTest();

r($executionTest->getLimitedExecutionTest()) && p() && e('1');

su('user1');
r($executionTest->getLimitedExecutionTest()) && p() && e('103');

su('user3');
r($executionTest->getLimitedExecutionTest()) && p() && e('103,104');

su('user4');
r($executionTest->getLimitedExecutionTest()) && p() && e('103');

su('user9');
r($executionTest->getLimitedExecutionTest()) && p() && e('');