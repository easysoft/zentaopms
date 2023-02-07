#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1,2,3');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint');
$execution->grade->range('1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(3);

/**

title=测试executionModel->saveState();
cid=1
pid=1

执行ID存在但无权访问 >> html
执行ID不存在 >> 0
执行ID存在，但不在可以查看的执行列表 >> 1
执行ID存在，且在可以查看的执行列表 >> 2
执行ID不存在，且有可以查看的执行列表 >> 2

*/

$executionTester = new executionTest();
ob_start();
$executionTester->saveStateTest(1);
$execution1 = ob_get_clean();
$execution2 = $executionTester->saveStateTest(0);
$execution3 = $executionTester->saveStateTest(1, array(1 => ''));
$execution4 = $executionTester->saveStateTest(3, array(2 => ''));
$execution5 = $executionTester->saveStateTest(0, array(2 => ''));

r(substr($execution1, 1, 4)) && p() && e('html'); // 执行ID存在但无权访问
r($execution2)               && p() && e('0');    // 执行ID不存在
r($execution3)               && p() && e('1');    // 执行ID存在，但不在可以查看的执行列表
r($execution4)               && p() && e('2');    // 执行ID存在，且在可以查看的执行列表
r($execution5)               && p() && e('2');    // 执行ID不存在，且有可以查看的执行列表
