#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByStatus();
timeout=0
cid=18511

- 执行storyTest模块的getByStatusTest方法，参数是1, 'all', '', 'active'  @5
- 执行storyTest模块的getByStatusTest方法，参数是1, 'all', '', 'draft'  @2
- 执行storyTest模块的getByStatusTest方法，参数是1, 'all', '', 'closed'  @0
- 执行storyTest模块的getByStatusTest方法，参数是1, 'all', '', 'invalid'  @0
- 执行storyTest模块的getByStatusTest方法，参数是1, 'all', '', 'launched'  @0
- 执行storyTest模块的getByStatusTest方法，参数是[1, 2], 'all', '', 'active'  @5
- 执行storyTest模块的getByStatusTest方法，参数是2, 1, '', 'active'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$table = zenData('story');
$table->id->range('1-20');
$table->product->range('1-3{7}');
$table->branch->range('0{10},1{5},2{5}');
$table->module->range('0{5},1{5},2{10}');
$table->title->range('需求A,需求B,需求C,需求D,需求E')->prefix('标题');
$table->status->range('active{5},draft{3},closed{4},reviewing{3},developing{5}');
$table->type->range('story{15},requirement{5}');
$table->stage->range('wait{5},planned{5},projected{5},closed{5}');
$table->deleted->range('0{18},1{2}');
$table->vision->range('rnd');
$table->gen(20);

su('admin');

$storyTest = new storyTest();

r($storyTest->getByStatusTest(1, 'all', '', 'active')) && p() && e('5');
r($storyTest->getByStatusTest(1, 'all', '', 'draft')) && p() && e('2');
r($storyTest->getByStatusTest(1, 'all', '', 'closed')) && p() && e('0');
r($storyTest->getByStatusTest(1, 'all', '', 'invalid')) && p() && e('0');
r($storyTest->getByStatusTest(1, 'all', '', 'launched')) && p() && e('0');
r($storyTest->getByStatusTest([1, 2], 'all', '', 'active')) && p() && e('5');
r($storyTest->getByStatusTest(2, 1, '', 'active')) && p() && e('0');