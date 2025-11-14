#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zenData('user')->gen(5);
su('admin');

zenData('project')->loadYaml('execution', true)->gen(30);

/**

title=测试executionModel->fetchPairs();
timeout=0
cid=16293

- 获取系统内全部执行的数量 @26
- 获取系统内全部非影子执行的数量 @24
- 获取系统内全部有权限执行的数量 @24
- 获取敏捷项目下的执行的数量 @3
- 获取不存在项目下的执行的数量 @0
- 获取系统内全部迭代的数量 @3
- 获取系统内全部阶段的数量 @18
- 获取系统内全部看板的数量 @3
- 获取系统内全部执行属性101 @迭代5
- 获取系统内全部非影子执行属性101 @迭代5
- 获取系统内全部有权限执行属性101 @迭代5
- 获取敏捷项目下的执行属性101 @迭代5
- 获取敏捷项目下的执行 @0
- 获取系统内全部迭代属性101 @迭代5
- 获取系统内全部阶段属性106 @阶段10
- 获取系统内全部看板属性124 @看板28

*/

$projectIdList = array(0, 11, 1);
$typeList      = array('all', 'sprint', 'stage', 'kanban');

global $tester;
$executionModel = $tester->loadModel('execution');
$allExecutions             = $executionModel->fetchPairs($projectIdList[0], $typeList[0], false, true);
$multipleExecutions        = $executionModel->fetchPairs($projectIdList[0], $typeList[0], true, true);
$canViewExecutions         = $executionModel->fetchPairs($projectIdList[0], $typeList[0], true, false);
$projectExecutions         = $executionModel->fetchPairs($projectIdList[1], $typeList[0], true, false);
$notExistProjectExecutions = $executionModel->fetchPairs($projectIdList[2], $typeList[0], true, false);
$sprints                   = $executionModel->fetchPairs($projectIdList[0], $typeList[1], true, false);
$stages                    = $executionModel->fetchPairs($projectIdList[0], $typeList[2], true, false);
$kanbans                   = $executionModel->fetchPairs($projectIdList[0], $typeList[3], true, false);

r(count($allExecutions))             && p() && e('26'); // 获取系统内全部执行的数量
r(count($multipleExecutions))        && p() && e('24'); // 获取系统内全部非影子执行的数量
r(count($canViewExecutions))         && p() && e('24'); // 获取系统内全部有权限执行的数量
r(count($projectExecutions))         && p() && e('3');  // 获取敏捷项目下的执行的数量
r(count($notExistProjectExecutions)) && p() && e('0');  // 获取不存在项目下的执行的数量
r(count($sprints))                   && p() && e('3');  // 获取系统内全部迭代的数量
r(count($stages))                    && p() && e('18'); // 获取系统内全部阶段的数量
r(count($kanbans))                   && p() && e('3');  // 获取系统内全部看板的数量

r($allExecutions)             && p('101') && e('迭代5');  // 获取系统内全部执行
r($multipleExecutions)        && p('101') && e('迭代5');  // 获取系统内全部非影子执行
r($canViewExecutions)         && p('101') && e('迭代5');  // 获取系统内全部有权限执行
r($projectExecutions)         && p('101') && e('迭代5');  // 获取敏捷项目下的执行
r($notExistProjectExecutions) && p()      && e('0');      // 获取敏捷项目下的执行
r($sprints)                   && p('101') && e('迭代5');  // 获取系统内全部迭代
r($stages)                    && p('106') && e('阶段10'); // 获取系统内全部阶段
r($kanbans)                   && p('124') && e('看板28'); // 获取系统内全部看板
