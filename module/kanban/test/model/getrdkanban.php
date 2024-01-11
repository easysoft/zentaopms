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

title=测试 kanbanModel->getRDKanban();
timeout=0
cid=1

- 获取执行1的看板信息
 - 第0条的id属性 @1
 - 第0条的title属性 @泳道1
- 获取执行2的看板信息
 - 第0条的id属性 @7
 - 第0条的title属性 @任务7
- 获取执行3的看板信息
 - 第0条的id属性 @9
 - 第0条的title属性 @未开始
- 获取执行1的看板信息,传入列类型 @0
- 获取执行2的看板信息,传入列类型
 - 第0条的id属性 @2
 - 第0条的title属性 @泳道2
- 获取执行3的看板信息,传入列类型 @0

*/
$executionIDList = array(1, 2, 3);

$kanban = new kanbanTest();

r($kanban->getRDKanbanTest($executionIDList[0])[0]['items'][0]['data']['lanes'])          && p('0:id,title') && e('1,泳道1');  // 获取执行1的看板信息
r($kanban->getRDKanbanTest($executionIDList[1])[0]['items'][0]['data']['items'][7][7])    && p('0:id,title') && e('7,任务7');  // 获取执行2的看板信息
r($kanban->getRDKanbanTest($executionIDList[2])[0]['items'][0]['data']['cols'])           && p('0:id,title') && e('9,未开始'); // 获取执行3的看板信息
r($kanban->getRDKanbanTest($executionIDList[0], 'story')[0]['items'])                     && p('')           && e('0');        // 获取执行1的看板信息,传入列类型
r($kanban->getRDKanbanTest($executionIDList[1], 'task')[0]['items'][0]['data']['lanes'])  && p('0:id,title') && e('2,泳道2');  // 获取执行2的看板信息,传入列类型
r($kanban->getRDKanbanTest($executionIDList[2], 'bug')[0]['items'])                       && p('')           && e('0');        // 获取执行3的看板信息,传入列类型
