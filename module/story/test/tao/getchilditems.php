#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getChildItems();
timeout=0
cid=0

- 执行storyTest模块的getChildItemsTest方法，参数是array
 - 第1条的total属性 @3
 - 第1条的finished属性 @1
- 执行storyTest模块的getChildItemsTest方法，参数是array
 - 第2条的total属性 @4
 - 第2条的finished属性 @2
- 执行storyTest模块的getChildItemsTest方法，参数是array
 - 第3条的total属性 @4
 - 第3条的finished属性 @3
- 执行storyTest模块的getChildItemsTest方法，参数是array  @0
- 执行storyTest模块的getChildItemsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$story = zenData('story');
$story->id->range('1-10');
$story->parent->range('0,0,0,1,1,1,0,0,0,0');
$story->product->range('1');
$story->title->range('父需求1,父需求2,父需求3,子需求1,子需求2,子需求3,独立需求1,独立需求2,独立需求3,独立需求4');
$story->type->range('story');
$story->status->range('active,active,active,closed,active,active,active,active,active,active');
$story->openedBy->range('admin');
$story->deleted->range('0');
$story->gen(10);

$task = zenData('task');
$task->id->range('1-10');
$task->story->range('2,2,2,2,3,3,3,3,0,0');
$task->execution->range('1');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->type->range('devel');
$task->status->range('done,done,doing,wait,done,closed,cancel,wait,wait,wait');
$task->openedBy->range('admin');
$task->deleted->range('0');
$task->gen(10);

su('admin');

$storyTest = new storyTest();

r($storyTest->getChildItemsTest(array(1 => (object)array('id' => 1)))) && p('1:total,finished') && e('3,1');
r($storyTest->getChildItemsTest(array(2 => (object)array('id' => 2)))) && p('2:total,finished') && e('4,2');
r($storyTest->getChildItemsTest(array(3 => (object)array('id' => 3)))) && p('3:total,finished') && e('4,3');
r($storyTest->getChildItemsTest(array())) && p() && e('0');
r($storyTest->getChildItemsTest(array(5 => (object)array('id' => 5)))) && p() && e('0');