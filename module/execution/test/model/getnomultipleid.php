#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,迭代1,项目2,迭代2,项目3,迭代3');
$execution->type->range('program,project,sprint,project,sprint,project,sprint');
$execution->parent->range('0,1,0,1,0,1,0');
$execution->project->range('0{2},2,0,4,0,6');
$execution->status->range('wait');
$execution->multiple->range('1{2},0,1,0,1,0');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(7);

zenData('user')->gen(5);
su('admin');

/**

title=测试 executionModel->getNoMultipleID();
timeout=0
cid=16330

- 根据正确项目ID获取被隐藏的执行id @3
- 根据空项目ID获取被隐藏的执行id @0
- 根据不存在的项目ID获取被隐藏的执行id @0
- 获取项目2下被隐藏的执行id @5
- 获取项目3下被隐藏的执行id @7

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r($executionModel->getNoMultipleID(2)) && p() && e('3'); // 根据正确项目ID获取被隐藏的执行id
r($executionModel->getNoMultipleID(0)) && p() && e('0'); // 根据空项目ID获取被隐藏的执行id
r($executionModel->getNoMultipleID(5)) && p() && e('0'); // 根据不存在的项目ID获取被隐藏的执行id
r($executionModel->getNoMultipleID(4)) && p() && e('5'); // 获取项目2下被隐藏的执行id
r($executionModel->getNoMultipleID(6)) && p() && e('7'); // 获取项目3下被隐藏的执行id
