#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';

zdTable('project')->config('execution')->gen(10);
zdTable('user')->gen(5);

$team = zdTable('team');
$team->type->range('execution');
$team->root->range('101-103');
$team->gen(10);

su('admin');
/**

title=测试 executionModel->getLimitedExecution();
timeout=0
cid=1

*/

global $app;

$execution = new executionTest();
r($execution->getLimitedExecutionTest()) && p() && e('1');  // 判断管理员

su('user4');
r($execution->getLimitedExecutionTest()) && p() && e('103');  // 判断非管理员
