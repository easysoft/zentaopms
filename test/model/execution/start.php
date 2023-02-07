#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-6');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('project,sprint{2},waterfall{2},kanban{1}');
$execution->parent->range('0,1{3},2{2}');
$execution->status->range('wait');
$execution->gen(6);
su('admin');

/**

title=测试executionModel->startTest();
cid=1
pid=1

敏捷执行开始             >> status,wait,doing
瀑布阶段开始             >> status,wait,doing
看板执行开始             >> status,wait,doing
重复执行开始             >> 此任务已被启动，不能重复启动！
子瀑布开启获取父瀑布状态 >> doing

*/

$executionIDList = array('2', '3', '4', '5');

$noRealBegan   = array('realBegan' => '');

$execution = new executionTest();
r($execution->startTest($executionIDList[0]))                && p('1:field,old,new') && e('status,wait,doing');              // 敏捷执行开始
r($execution->startTest($executionIDList[1]))                && p('1:field,old,new') && e('status,wait,doing');              // 瀑布阶段开始
r($execution->startTest($executionIDList[2]))                && p('1:field,old,new') && e('status,wait,doing');              // 看板执行开始
r($execution->startTest($executionIDList[0],$noRealBegan))   && p()                  && e('此任务已被启动，不能重复启动！'); // 重复执行开始
r($execution->startTest($executionIDList[3], array(), true)) && p('status')          && e('doing');                          // 子瀑布开启获取父瀑布状态
