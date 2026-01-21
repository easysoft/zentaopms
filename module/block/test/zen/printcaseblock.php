#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printCaseBlock();
timeout=0
cid=15253

- 执行blockTest模块的printCaseBlockTest方法，参数是$block1 属性count @5
- 执行blockTest模块的printCaseBlockTest方法，参数是$block2 属性count @4
- 执行blockTest模块的printCaseBlockTest方法，参数是$block2 属性count @3
- 执行blockTest模块的printCaseBlockTest方法，参数是$block3 属性count @5
- 执行blockTest模块的printCaseBlockTest方法，参数是$block4 属性count @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$case = zenData('case');
$case->id->range('1-15');
$case->project->range('1-3');
$case->product->range('1-3');
$case->title->range('测试用例`1-15`');
$case->openedBy->range('admin{6},user1{5},user2{4}');
$case->deleted->range('0');
$case->gen(15);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->name->range('测试单`1-5`');
$testtask->status->range('wait{3},doing{2}');
$testtask->deleted->range('0');
$testtask->gen(5);

$testrun = zenData('testrun');
$testrun->id->range('1-12');
$testrun->task->range('1-5');
$testrun->case->range('1-12');
$testrun->assignedTo->range('admin{5},user1{4},user2{3}');
$testrun->status->range('wait{8},doing{4}');
$testrun->gen(12);

$projectadmin = zenData('projectadmin');
$projectadmin->account->range('admin,user1,user2');
$projectadmin->programs->range('[]');
$projectadmin->projects->range('[]');
$projectadmin->products->range('[]');
$projectadmin->executions->range('[]');
$projectadmin->gen(3);

zenData('product')->loadYaml('product')->gen(3);
zenData('project')->loadYaml('project')->gen(5);
zenData('user')->loadYaml('user')->gen(5);

su('admin');

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->dashboard = 'my';
$block1->params = new stdClass();
$block1->params->type = 'assigntome';
$block1->params->orderBy = 'id_desc';
$block1->params->count = 15;

$block2 = new stdClass();
$block2->dashboard = 'my';
$block2->params = new stdClass();
$block2->params->type = 'assigntome';
$block2->params->orderBy = 'id_desc';
$block2->params->count = 15;

$block3 = new stdClass();
$block3->dashboard = 'my';
$block3->params = new stdClass();
$block3->params->type = 'assigntome';
$block3->params->orderBy = 'id_asc';
$block3->params->count = 10;

$block4 = new stdClass();
$block4->dashboard = 'my';
$block4->params = new stdClass();
$block4->params->type = 'assigntome';
$block4->params->orderBy = 'id_asc';
$block4->params->count = 2;

$block5 = new stdClass();
$block5->dashboard = 'project';
$block5->params = new stdClass();
$block5->params->type = 'assigntome';
$block5->params->orderBy = 'id_desc';
$block5->params->count = 15;

r($blockTest->printCaseBlockTest($block1)) && p('count') && e('5');
su('user1');
r($blockTest->printCaseBlockTest($block2)) && p('count') && e('4');
su('user2');
r($blockTest->printCaseBlockTest($block2)) && p('count') && e('3');
su('admin');
r($blockTest->printCaseBlockTest($block3)) && p('count') && e('5');
su('user1');
r($blockTest->printCaseBlockTest($block4)) && p('count') && e('2');