#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/kanban.class.php';
su('admin');

zdTable('project')->config('kanbanexecution')->gen(5);
zdTable('kanbanregion')->config('rdkanbanregion')->gen(5);
zdTable('kanbangroup')->config('rdkanbangroup')->gen(20);
zdTable('kanbancolumn')->gen(20);
zdTable('kanbanlane')->config('rdkanbanlane')->gen(10);
zdTable('kanbancell')->config('rdkanbancell')->gen(20);
zdTable('task')->config('rdkanbantask')->gen(20);

/**

title=测试 kanbanModel->buildExecutionGroup();
timeout=0
cid=1

- 构造迭代的任务泳道组
 - 第0条的id属性 @1
 - 第0条的title属性 @泳道1
- 构造阶段的任务泳道组
 - 第0条的id属性 @2
 - 第0条的title属性 @泳道2
- 构造看板的任务泳道组
 - 第0条的id属性 @3
 - 第0条的title属性 @泳道3
- 构造迭代的需求泳道组
 - 第0条的id属性 @4
 - 第0条的title属性 @泳道4
- 构造阶段的需求泳道组
 - 第0条的id属性 @5
 - 第0条的title属性 @泳道5
- 构造看板的需求泳道组
 - 第0条的id属性 @6
 - 第0条的title属性 @泳道6
- 构造迭代的Bug泳道组
 - 第0条的id属性 @7
 - 第0条的title属性 @泳道7
- 构造阶段的Bug泳道组
 - 第0条的id属性 @8
 - 第0条的title属性 @泳道8
- 构造看板的Bug泳道组
 - 第0条的id属性 @9
 - 第0条的title属性 @泳道9

*/

$executionIdList = array(1, 2, 3);
$laneIdList      = array(1, 2, 3, 4, 5, 6, 7, 8 ,9);

$kanbanTester = new kanbanTest();
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[0])) && p('0:id,title') && e('1,泳道1'); // 构造迭代的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[1])) && p('0:id,title') && e('2,泳道2'); // 构造阶段的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[2])) && p('0:id,title') && e('3,泳道3'); // 构造看板的任务泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[3])) && p('0:id,title') && e('4,泳道4'); // 构造迭代的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[4])) && p('0:id,title') && e('5,泳道5'); // 构造阶段的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[5])) && p('0:id,title') && e('6,泳道6'); // 构造看板的需求泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[0], $laneIdList[6])) && p('0:id,title') && e('7,泳道7'); // 构造迭代的Bug泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[1], $laneIdList[7])) && p('0:id,title') && e('8,泳道8'); // 构造阶段的Bug泳道组
r($kanbanTester->buildExecutionGroupTest($executionIdList[2], $laneIdList[8])) && p('0:id,title') && e('9,泳道9'); // 构造看板的Bug泳道组