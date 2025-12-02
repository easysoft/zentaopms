#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5');
$execution->type->range('project,sprint{2},waterfall{2},kanban{1}');
$execution->parent->range('0,1{3},2{2}');
$execution->status->range('wait');
$execution->gen(6);
su('admin');

/**
title=测试executionModel->startTest();
cid=16367
pid=1

- 敏捷执行开始
 - 第status条的old属性 @wait
 - 第status条的new属性 @doing
- 瀑布阶段开始
 - 第status条的old属性 @wait
 - 第status条的new属性 @doing
- 看板执行开始
 - 第status条的old属性 @wait
 - 第status条的new属性 @doing
- 子瀑布开启获取父瀑布状态属性status @doing

*/

$executionIDList = array(2, 3, 4, 5);

$execution = new executionTest();
r($execution->startTest($executionIDList[0]))                && p('status:old,new') && e('wait,doing'); // 敏捷执行开始
r($execution->startTest($executionIDList[1]))                && p('status:old,new') && e('wait,doing'); // 瀑布阶段开始
r($execution->startTest($executionIDList[2]))                && p('status:old,new') && e('wait,doing'); // 看板执行开始
r($execution->startTest($executionIDList[3], array(), true)) && p('status')        && e('doing');       // 子瀑布开启获取父瀑布状态
