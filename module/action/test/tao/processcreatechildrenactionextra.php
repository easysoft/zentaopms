#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processCreateChildrenActionExtra();
timeout=0
cid=14961

- 步骤1:单个任务ID属性extra @#1 A
- 步骤2:多个任务ID属性extra @#1 A, #2 B, #3 C
- 步骤3:不存在的任务ID属性extra @~~
- 步骤4:空字符串属性extra @~~
- 步骤5:混合存在和不存在的任务ID属性extra @#1 A, #2 B

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('A,B,C,D,E,F,G,H,I,J');
$taskTable->type->range('devel{3},test{3},design{2},study,misc');
$taskTable->status->range('wait{2},doing{3},done{3},pause,cancel');
$taskTable->project->range('1-3');
$taskTable->execution->range('1-3');
$taskTable->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateChildrenActionExtraTest('1')) && p('extra') && e('#1 A');
r($actionTest->processCreateChildrenActionExtraTest('1,2,3')) && p('extra') && e('#1 A, #2 B, #3 C');
r($actionTest->processCreateChildrenActionExtraTest('999')) && p('extra') && e('~~');
r($actionTest->processCreateChildrenActionExtraTest('')) && p('extra') && e('~~');
r($actionTest->processCreateChildrenActionExtraTest('1,999,2')) && p('extra') && e('#1 A, #2 B');
