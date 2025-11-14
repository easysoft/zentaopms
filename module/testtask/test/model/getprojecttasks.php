#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project')->gen(100);

$testtask = zenData('testtask');
$testtask->id->range('1-500');
$testtask->project->range('1-100');
$testtask->product->range('1-100');
$testtask->execution->range('1-100');
$testtask->build->range('1-100');
$testtask->begin->range('2022\-10\-01');
$testtask->end->range('2022\-10\-31');
$testtask->name->prefix("测试单")->range('1-500');
$testtask->owner->range('user10');
$testtask->desc->range('测试单描述');
$testtask->gen(500);

/**

title=测试 testtaskModel->getProjectTasks();
timeout=0
cid=19185

- 查看项目11下的所有测试单数量 @5

- 查看项目20下的所有测试单数量 @5

- 查看项目100下的所有测试单数量 @0

- 查看项目11下的测试单1的详细信息
 - 第11条的name属性 @测试单11
 - 第11条的status属性 @done
 - 第11条的begin属性 @2022-10-01
 - 第11条的end属性 @2022-10-31

- 查看项目20下的所有测试单10的详细信息
 - 第20条的name属性 @测试单20
 - 第20条的status属性 @blocked
 - 第20条的begin属性 @2022-10-01
 - 第20条的end属性 @2022-10-31

*/

global $tester;
$tester->loadModel('testtask');

$project11Tasks  = $tester->testtask->getProjectTasks(11);
$project20Tasks  = $tester->testtask->getProjectTasks(20);
$project100Tasks = $tester->testtask->getProjectTasks(100);

r(count($project11Tasks))  && p()                           && e('5');                                      // 查看项目11下的所有测试单数量 
r(count($project20Tasks))  && p()                           && e('5');                                      // 查看项目20下的所有测试单数量
r(count($project100Tasks)) && p()                           && e('0');                                      // 查看项目100下的所有测试单数量
r($project11Tasks)         && p('11:name,status,begin,end') && e('测试单11,done,2022-10-01,2022-10-31');    // 查看项目11下的测试单1的详细信息
r($project20Tasks)         && p('20:name,status,begin,end') && e('测试单20,blocked,2022-10-01,2022-10-31'); // 查看项目20下的所有测试单10的详细信息