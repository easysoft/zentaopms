#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
/**

title=测试 projectModel->getProjectExecutionPairs();
cid=1

- 查看全部项目与执行键值对数组数量为6 @2
- 查看瀑布项目编号为3的阶段4 @0
- 查看瀑布项目编号为3的阶段5 @2
- 查看scrum项目编号为6的无执行 @5
- 查看状态为未完成执行的全部项目与执行键值对数组数量为6 @2
- 查看状态为未完成执行的瀑布项目编号为3的阶段4 @0
- 查看状态为未完成执行的瀑布项目编号为3的阶段5 @2
- 查看状态为未完成执行的scrum项目编号为6的无执行 @5
- 查看状态为进行中执行的全部项目与执行键值对数组数量为6 @1
- 查看状态为进行中执行的瀑布项目编号为3的阶段4 @0
- 查看状态为进行中执行的瀑布项目编号为3的阶段5 @2
- 查看状态为进行中执行的scrum项目编号为6的无执行 @0

*/

su('admin');
zdTable('project')->config('project')->gen(6);

global $tester;
$tester->loadModel('project');

$allProjectPairs            = $tester->project->getProjectExecutionPairs();
$undoneProjectPairs         = $tester->project->getProjectExecutionPairs('0', 'undone');
$doingProjectPairs          = $tester->project->getProjectExecutionPairs('0', 'doing');
$undoneMultipleProjectPairs = $tester->project->getProjectExecutionPairs('1', 'undone');
$doingMultipleProjectPairs  = $tester->project->getProjectExecutionPairs('1', 'doing');

r(count($allProjectPairs)) && p() && e('2'); // 查看全部项目与执行键值对数组数量为6
r($allProjectPairs[0])     && p() && e('0'); // 查看瀑布项目编号为3的阶段4
r($allProjectPairs[1])     && p() && e('2'); // 查看瀑布项目编号为3的阶段5
r($allProjectPairs[3])     && p() && e('5'); // 查看scrum项目编号为6的无执行

r(count($undoneProjectPairs)) && p() && e('2'); // 查看状态为未完成执行的全部项目与执行键值对数组数量为6
r($undoneProjectPairs[0])     && p() && e('0'); // 查看状态为未完成执行的瀑布项目编号为3的阶段4
r($undoneProjectPairs[1])     && p() && e('2'); // 查看状态为未完成执行的瀑布项目编号为3的阶段5
r($undoneProjectPairs[3])     && p() && e('5'); // 查看状态为未完成执行的scrum项目编号为6的无执行

r(count($doingProjectPairs)) && p() && e('1'); // 查看状态为进行中执行的全部项目与执行键值对数组数量为6
r($doingProjectPairs[0])     && p() && e('0'); // 查看状态为进行中执行的瀑布项目编号为3的阶段4
r($doingProjectPairs[1])     && p() && e('2'); // 查看状态为进行中执行的瀑布项目编号为3的阶段5
r($doingProjectPairs[3])     && p() && e('0'); // 查看状态为进行中执行的scrum项目编号为6的无执行

r(count($undoneMultipleProjectPairs)) && p() && e('0'); // 查看状态为未完成执行的全部项目与执行键值对数组数量为6
r($undoneMultipleProjectPairs[0])     && p() && e('0'); // 查看状态为未完成执行的瀑布项目编号为3的阶段4
r($undoneMultipleProjectPairs[1])     && p() && e('0'); // 查看状态为未完成执行的瀑布项目编号为3的阶段5
r($undoneMultipleProjectPairs[3])     && p() && e('0'); // 查看状态为未完成执行的scrum项目编号为6的无执行

r(count($doingMultipleProjectPairs)) && p() && e('0'); // 查看状态为进行中执行的全部项目与执行键值对数组数量为6
r($doingMultipleProjectPairs[0])     && p() && e('0'); // 查看状态为进行中执行的瀑布项目编号为3的阶段4
r($doingMultipleProjectPairs[1])     && p() && e('0'); // 查看状态为进行中执行的瀑布项目编号为3的阶段5
r($doingMultipleProjectPairs[3])     && p() && e('0'); // 查看状态为进行中执行的scrum项目编号为6的无执行
