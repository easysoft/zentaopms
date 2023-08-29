#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('story')->gen(10);
zdTable('bug')->gen(10);
zdTable('task')->config('task')->gen(10);
zdTable('kanbanlane')->config('kanbanlane')->gen(10);
zdTable('kanbancell')->config('kanbancell')->gen(30);

/**

title=测试 kanbanModel->buildExecutionGroup();
timeout=0
cid=1

*/

$executionIdList = array(1, 2, 3);
$laneIdList      = array(1, 2, 3, 4, 5, 6, 7, 8 ,9);

$kanbanTester = new kanbanTest();
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[0])) && p('0:laneID,name') && e('1,泳道1');  // 构造迭代的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[1])) && p('0:laneID,name') && e('2,泳道2'); // 构造阶段的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[2])) && p('0:laneID,name') && e('3,泳道3'); // 构造看板的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[3])) && p('0:laneID,name') && e('4,泳道2'); // 构造迭代的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[4])) && p('0:laneID,name') && e('5,泳道5'); // 构造阶段的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[5])) && p('0:laneID,name') && e('6,泳道6'); // 构造看板的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[6])) && p('0:laneID,name') && e('7,泳道7'); // 构造迭代的Bug泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[7])) && p('0:laneID,name') && e('8,泳道8'); // 构造阶段的Bug泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[8])) && p('0:laneID,name') && e('9,泳道9'); // 构造看板的Bug泳道组
