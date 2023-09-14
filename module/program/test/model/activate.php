#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/program.class.php';
zdTable('user')->gen(5);
su('admin');

$program = zdTable('project')->config('program');
$program->realEnd->range('`2023-09-11`');
$program->status->range('closed');
$program->gen(10);

/**

title=测试 programModel::activate();
timeout=0
cid=1

*/

$programID  = 1;
$errorDate  = array('begin' => '2023-09-15', 'end' => '2023-09-10');
$normalDate = array('begin' => '2023-09-12', 'end' => '2024-09-12');

$programTester = new programTest();
r($programTester->activateTest($programID, $errorDate))  && p('end')             && e('『计划完成』应当不小于『计划开始』。'); // 测试实际完成时间小于实际开始时间的情况
r($programTester->activateTest($programID, $normalDate)) && p('0:field,old,new') && e('status,closed,doing');                  // 测试激活一个项目集
