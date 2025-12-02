#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,不启用迭代项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint{3}');
$execution->status->range('doing{3},closed,doing');
$execution->project->range('0,0,1,1,2');
$execution->parent->range('0,0,1,1,2');
$execution->multiple->range('1,0,1,1,0');
$execution->grade->range('1{2},2{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);
su('admin');

/**

title=测试 taskZen::setMenu();
timeout=0
cid=18949

- 启用迭代的项目，跳转到可访问的第一个执行下 @3
- 不启用迭代的项目，直接返回当前项目的导航 @2
- 进行中的迭代，直接返回当前迭代的导航 @3
- 不可以修改关闭的迭代，关闭的迭代，跳转到可访问的第一个执行下 @5
- 可以修改关闭的迭代，关闭的迭代，直接返回当前迭代的导航 @4

*/

$executionIDList = array('1', '2', '3', '4', '5');

global $tester, $app, $config;
$app->rawModule   = 'execution';
$app->rawMethod   = 'task';

$zen  = initReference('task');
$func = $zen->getMethod('setMenu');

r($func->invokeArgs($zen->newInstance(), [$executionIDList[0]])) && p() && e('3'); //启用迭代的项目，跳转到可访问的第一个执行下
r($func->invokeArgs($zen->newInstance(), [$executionIDList[1]])) && p() && e('2'); //不启用迭代的项目，直接返回当前项目的导航
r($func->invokeArgs($zen->newInstance(), [$executionIDList[2]])) && p() && e('3'); //进行中的迭代，直接返回当前迭代的导航
$config->CRExecution = 0;
r($func->invokeArgs($zen->newInstance(), [$executionIDList[3]])) && p() && e('5'); //不可以修改关闭的迭代，关闭的迭代，跳转到可访问的第一个执行下
$config->CRExecution = 1;
r($func->invokeArgs($zen->newInstance(), [$executionIDList[3]])) && p() && e('4'); //可以修改关闭的迭代，关闭的迭代，直接返回当前迭代的导航
