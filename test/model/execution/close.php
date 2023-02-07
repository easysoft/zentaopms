#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,1-5')->prefix('执行');
$execution->type->range('project,sprint,stage,kanban,stage{2}');
$execution->status->range('wait{4},suspended,closed,doing');
$execution->parent->range('0{2},1,0,1,5');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);

/**

title=测试executionModel->closeTest();
cid=1
pid=1

敏捷执行关闭 >> status,wait,closed
瀑布执行关闭 >> status,wait,closed
看板执行关闭 >> status,wait,closed
不输入实际完成时间校验 >> 『realEnd』不能为空。
子瀑布关闭获取父瀑布状态 >> closed

*/

$executionIDList = array(2, 3, 4, 6);
$noRealEnd       = array('realEnd' => '');

$executionTester = new executionTest();
r($executionTester->closeTest($executionIDList[0]))                && p('0:field,old,new') && e('status,wait,closed');    // 敏捷执行关闭
r($executionTester->closeTest($executionIDList[1]))                && p('0:field,old,new') && e('status,wait,closed');   // 瀑布执行关闭
r($executionTester->closeTest($executionIDList[2]))                && p('0:field,old,new') && e('status,wait,closed');   // 看板执行关闭
r($executionTester->closeTest($executionIDList[1],$noRealEnd))     && p('realEnd:0')       && e('『realEnd』不能为空。'); // 不输入实际完成时间校验
r($executionTester->closeTest($executionIDList[3], array(), true)) && p('status')          && e('closed');                // 子瀑布关闭获取父瀑布状态
