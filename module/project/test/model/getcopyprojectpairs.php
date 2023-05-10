#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('user1');

zdTable('project')->config('project')->gen(8);
zdTable('user')->config('user')->gen(3);
zdTable('userview')->config('userview')->gen(1);

/**

title=测试 projectModel->getCopyProjectPairs();
timeout=0
cid=1

- 测试非看板项目，项目名称带'测试项目1',返回空值 @0

- 根据'测试',返回带权限项目名称带有测试的看板项目数组键值 @1

- 根据'kanban',返回带权限项目名称带有kanban的看板项目数组总数 @2

- 根据'kanban',返回带权限项目名称带有kanban的看板项目数组键值 @3

- 根据'nopriv',无权限项目名称带有nopriv的看板项目，返回空值 @0

- 根据'123',测试检索项目名称带有数字的看板项目，返回看板项目数组键值 @6

*/

global $tester;
$tester->loadModel('project');

$test1 = 'scrum项目1';
$test2 = '项目看板测试1';
$test3 = 'kanban';
$test4 = 'nopriv';
$test5 = '123';

$project1 = $tester->project->getCopyProjectPairs($test1, 'kanban');
$project2 = $tester->project->getCopyProjectPairs($test2, 'kanban');
$project3 = $tester->project->getCopyProjectPairs($test3, 'kanban');
$project4 = $tester->project->getCopyProjectPairs($test4, 'kanban');
$project5 = $tester->project->getCopyProjectPairs($test5, 'kanban');

r($project1)        && p() && e('0'); //测试非看板项目，项目名称带'测试项目1',返回空值
r($project2[0])     && p() && e('1'); //根据'测试',返回带权限项目名称带有测试的看板项目数组键值
r(count($project3)) && p() && e('2'); //根据'kanban',返回带权限项目名称带有kanban的看板项目数组总数
r($project3[1])     && p() && e('3'); //根据'kanban',返回带权限项目名称带有kanban的看板项目数组键值
r($project4)        && p() && e('0'); //根据'nopriv',无权限项目名称带有nopriv的看板项目，返回空值
r($project5[0])     && p() && e('6'); //根据'123',测试检索项目名称带有数字的看板项目，返回看板项目数组键值