#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('project')->config('execution')->gen(100)->fixPath();
zdTable('user')->gen(100);

su('admin');

/**

title=测试 projectTao::getstats4kanban
timeout=0
cid=1

- 获取到的看板分组数量 @2

- 获取到的非我负责的看板数量 @1

- 获取到的我负责的看板数量 @1

- 获取到的进行中的执行数量 @4

- 获取非我负责的第一个未开始项目的详情
 - 属性name @敏捷项目65
 - 属性status @wait

- 获取非我负责的第一个进行中项目的详情
 - 属性name @瀑布项目2
 - 属性status @doing

- 获取我负责的第一个未开始项目的详情
 - 属性name @瀑布项目66
 - 属性status @wait

- 获取我负责的第一个进行中项目的详情
 - 属性name @敏捷项目1
 - 属性status @doing

- 获取进行中的ID为100的执行详情
 - 属性name @看板92
 - 属性status @doing

*/

global $tester;
$tester->loadModel('project');
list($kanbanGroup, $ongoingExecutions) = $tester->project->getStats4Kanban();

r(count($kanbanGroup))          && p() && e('2'); // 获取到的看板分组数量
r(count($kanbanGroup['other'])) && p() && e('1'); // 获取到的非我负责的看板数量
r(count($kanbanGroup['my']))    && p() && e('1'); // 获取到的我负责的看板数量
r(count($ongoingExecutions))    && p() && e('4'); // 获取到的进行中的执行数量

r($kanbanGroup['other'][0]['wait'][0])  && p('name,status') && e('敏捷项目65,wait'); // 获取非我负责的第一个未开始项目的详情
r($kanbanGroup['other'][0]['doing'][0]) && p('name,status') && e('瀑布项目2,doing'); // 获取非我负责的第一个进行中项目的详情
r($kanbanGroup['my'][0]['wait'][0])     && p('name,status') && e('瀑布项目66,wait'); // 获取我负责的第一个未开始项目的详情
r($kanbanGroup['my'][0]['doing'][0])    && p('name,status') && e('敏捷项目1,doing'); // 获取我负责的第一个进行中项目的详情
r($ongoingExecutions[100])              && p('name,status') && e('看板92,doing');    // 获取进行中的ID为100的执行详情
