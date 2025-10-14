#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printCaseBlock();
timeout=0
cid=0

- 执行blockTest模块的printCaseBlockTest方法，参数是$block1 属性type @assigntome
- 执行blockTest模块的printCaseBlockTest方法，参数是$block2 属性type @openedbyme
- 执行blockTest模块的printCaseBlockTest方法，参数是$block3 属性type @invalid<>
- 执行blockTest模块的printCaseBlockTest方法，参数是$block4 属性count @5
- 执行blockTest模块的printCaseBlockTest方法，参数是$block5 属性projectID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

$case = zenData('case');
$case->id->range('1-30');
$case->title->range('测试用例{30}');
$case->type->range('feature{15},unit{15}');
$case->status->range('normal{25},blocked{5}');
$case->openedBy->range('admin{15},user1{15}');
$case->deleted->range('0{30}');
$case->gen(30);

$testrun = zenData('testrun');
$testrun->id->range('1-20');
$testrun->task->range('1{10},2{10}');
$testrun->case->range('1-20');
$testrun->assignedTo->range('admin{10},user1{10}');
$testrun->status->range('wait{15},done{5}');
$testrun->gen(20);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->name->range('测试任务{5}');
$testtask->status->range('doing{3},done{2}');
$testtask->deleted->range('0{5}');
$testtask->gen(5);

su('admin');
$blockTest = new blockTest();

$block1 = new stdclass();
$block1->dashboard = 'qa';
$block1->params = new stdclass();
$block1->params->type = 'assigntome';
$block1->params->count = '10';

r($blockTest->printCaseBlockTest($block1)) && p('type') && e('assigntome');

$block2 = new stdclass();
$block2->dashboard = 'qa';
$block2->params = new stdclass();
$block2->params->type = 'openedbyme';
$block2->params->count = '10';

r($blockTest->printCaseBlockTest($block2)) && p('type') && e('openedbyme');

$block3 = new stdclass();
$block3->dashboard = 'qa';
$block3->params = new stdclass();
$block3->params->type = 'invalid<>';
$block3->params->count = '10';

r($blockTest->printCaseBlockTest($block3)) && p('type') && e('invalid<>');

$block4 = new stdclass();
$block4->dashboard = 'qa';
$block4->params = new stdclass();
$block4->params->type = '';
$block4->params->count = '5';

r($blockTest->printCaseBlockTest($block4)) && p('count') && e('5');

$block5 = new stdclass();
$block5->dashboard = 'my';
$block5->params = new stdclass();
$block5->params->type = 'assigntome';
$block5->params->count = '8';

r($blockTest->printCaseBlockTest($block5)) && p('projectID') && e('0');