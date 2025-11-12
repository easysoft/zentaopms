#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleStoryBlock();
timeout=0
cid=0

- 测试步骤1:测试正常显示指派给我的需求类型assignedTo,限制5条属性storiesCount @5
- 测试步骤2:测试显示由我创建的需求类型openedBy,限制3条属性storiesCount @3
- 测试步骤3:测试显示我评审的需求类型reviewedBy,限制10条属性storiesCount @10
- 测试步骤4:测试显示需求类型closedBy,限制8条属性storiesCount @8
- 测试步骤5:测试显示需求数量限制为0时应该显示所有需求属性storiesCount @15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal');
$product->type->range('normal');
$product->deleted->range('0');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-100');
$story->product->range('1{20},2{20},3{20},4{20},5{20}');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10')->prefix('用户需求')->postfix('');
$story->type->range('story');
$story->status->range('active{60},draft{20},closed{20}');
$story->stage->range('wait{30},planned{30},developing{40}');
$story->pri->range('1-4');
$story->assignedTo->range('admin{15},user1{20},user2{20},user3{20},[]25');
$story->openedBy->range('admin{30},user1{20},user2{20},user3{30}');
$story->reviewedBy->range('admin{20},user1{20},user2{20},user3{20},[]20');
$story->closedBy->range('admin{15},user1{20},user2{20},user3{20},[]25');
$story->openedDate->range('`2024-01-01 00:00:00`,`2024-02-01 00:00:00`,`2024-03-01 00:00:00`')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$story->deleted->range('0');
$story->version->range('1-3');
$story->gen(100);

global $tester;
$tester->session->product = 1;

$blockTest = new blockZenTest();

$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = 'assignedTo';
$block1->params->count = 5;
$block1->params->orderBy = 'id_desc';

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->type = 'openedBy';
$block2->params->count = 3;
$block2->params->orderBy = 'id_desc';

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->type = 'reviewedBy';
$block3->params->count = 10;
$block3->params->orderBy = 'id_desc';

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->type = 'closedBy';
$block4->params->count = 8;
$block4->params->orderBy = 'id_desc';

$block5 = new stdClass();
$block5->params = new stdClass();
$block5->params->type = 'assignedTo';
$block5->params->count = 0;
$block5->params->orderBy = 'id_desc';

r($blockTest->printSingleStoryBlockTest($block1)) && p('storiesCount') && e('5'); // 测试步骤1:测试正常显示指派给我的需求类型assignedTo,限制5条
r($blockTest->printSingleStoryBlockTest($block2)) && p('storiesCount') && e('3'); // 测试步骤2:测试显示由我创建的需求类型openedBy,限制3条
r($blockTest->printSingleStoryBlockTest($block3)) && p('storiesCount') && e('10'); // 测试步骤3:测试显示我评审的需求类型reviewedBy,限制10条
r($blockTest->printSingleStoryBlockTest($block4)) && p('storiesCount') && e('8'); // 测试步骤4:测试显示需求类型closedBy,限制8条
r($blockTest->printSingleStoryBlockTest($block5)) && p('storiesCount') && e('15'); // 测试步骤5:测试显示需求数量限制为0时应该显示所有需求