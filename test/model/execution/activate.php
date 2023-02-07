#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

/**

title=测试executionModel->activateTest();
cid=1
pid=1

敏捷执行激活 >> status,suspended,doing
瀑布执行激活 >> status,suspended,doing
看板执行激活 >> status,suspended,doing
修改激活时间 >> begin
修改顺延 >> begin
子瀑布激活获取父瀑布状态 >> doing

*/

$executionIDList = array(1,2,3,4,5);
$readjustTask    = array('readjustTime' => '1', 'readjustTask' => '1');
$readjustTime    = array('readjustTime' => '1');

$executionTester = new executionTest();
r($executionTester->activateTest($executionIDList[0]))                && p('0:field,old,new') && e('status,suspended,doing'); // 敏捷执行激活
r($executionTester->activateTest($executionIDList[1]))                && p('0:field,old,new') && e('status,suspended,doing'); // 瀑布执行激活
r($executionTester->activateTest($executionIDList[2]))                && p('0:field,old,new') && e('status,suspended,doing'); // 看板执行激活
r($executionTester->activateTest($executionIDList[3],$readjustTime))  && p('1:field')         && e('begin');                  // 修改激活时间
r($executionTester->activateTest($executionIDList[4],$readjustTask))  && p('1:field')         && e('begin');                  // 修改顺延
r($executionTester->activateTest($executionIDList[3], array(), true)) && p('status')          && e('doing');                  // 子瀑布激活获取父瀑布状态
