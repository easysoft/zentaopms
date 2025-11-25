#!/usr/bin/env php
<?php

/**

title=- 步骤1:单个需求ID属性extra @
timeout=0
cid=1

- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1' 属性extra @#1 需求A
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1, 2, 3'
 - 属性extra @#1 需求A
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'999' 属性extra @~~
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'' 属性extra @~~
- 执行actionTest模块的processCreateRequirementsActionExtraTest方法，参数是'1, 999, 2'
 - 属性extra @#1 需求A

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->title->range('需求A,需求B,需求C,需求D,需求E,需求F,需求G,需求H,需求I,需求J');
$storyTable->type->range('requirement');
$storyTable->product->range('1-3');
$storyTable->status->range('active{5},closed{3},draft{2}');
$storyTable->openedBy->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin');
$storyTable->gen(10);

su('admin');

$actionTest = new actionTest();

r($actionTest->processCreateRequirementsActionExtraTest('1')) && p('extra') && e('#1 需求A');
r($actionTest->processCreateRequirementsActionExtraTest('1,2,3')) && p('extra') && e('#1 需求A, #2 需求B, #3 需求C');
r($actionTest->processCreateRequirementsActionExtraTest('999')) && p('extra') && e('~~');
r($actionTest->processCreateRequirementsActionExtraTest('')) && p('extra') && e('~~');
r($actionTest->processCreateRequirementsActionExtraTest('1,999,2')) && p('extra') && e('#1 需求A, #2 需求B');