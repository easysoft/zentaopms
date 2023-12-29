#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGanttLinks()
cid=0

- 禅道版本为开源版，检查任务关系。 @0
- 检查第一条任务关系
 - 属性source @1-1
 - 属性target @1-2
 - 属性type @0
- 检查第二条任务关系
 - 属性source @2-2
 - 属性target @2-3
 - 属性type @2
- 检查第三条任务关系
 - 属性source @3-3
 - 属性target @3-4
 - 属性type @1
- 检查第四条任务关系
 - 属性source @1-11
 - 属性target @1-12
 - 属性type @1
- 检查第五条任务关系
 - 属性source @2-12
 - 属性target @2-13
 - 属性type @3
- 检查第六条任务关系
 - 属性source @3-13
 - 属性target @3-14
 - 属性type @0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$relationoftasks = zdTable('relationoftasks');
$relationoftasks->id->range('1-100');
$relationoftasks->execution->range('1-10');
$relationoftasks->pretask->range('1-100');
$relationoftasks->condition->range('end{2},begin{2}');
$relationoftasks->task->range('2-100');
$relationoftasks->action->range('begin,end');
$relationoftasks->gen(20);

global $tester;
$tester->loadModel('programplan');

$planIdList = array(1,2,3);

$tester->programplan->config->edition = 'open';
r($tester->programplan->buildGanttLinks($planIdList)) && p() && e("0"); //禅道版本为开源版，检查任务关系。

$tester->programplan->config->edition = 'max';
$links = $tester->programplan->buildGanttLinks($planIdList);
r($links[0]) && p('source,target,type') && e('1-1,1-2,0');    //检查第一条任务关系
r($links[1]) && p('source,target,type') && e('2-2,2-3,2');    //检查第二条任务关系
r($links[2]) && p('source,target,type') && e('3-3,3-4,1');    //检查第三条任务关系
r($links[3]) && p('source,target,type') && e('1-11,1-12,1');  //检查第四条任务关系
r($links[4]) && p('source,target,type') && e('2-12,2-13,3');  //检查第五条任务关系
r($links[5]) && p('source,target,type') && e('3-13,3-14,0');  //检查第六条任务关系
