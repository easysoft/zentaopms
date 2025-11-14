#!/usr/bin/env php
<?php

/**

title=测试 storyModel->setStageToClosed();
timeout=0
cid=18659

- 不传入任何数据。 @0
- 传入需求，检查需求的阶段。属性stage @closed
- 传入需求，检查需求的阶段。属性stage @closed
- 传入需求，检查storystage记录数。 @1
- 传入需求，检查分支为 0 的storystage记录。
 - 属性story @2
 - 属性branch @1
 - 属性stage @closed

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('storystage')->gen(0);
$story = zenData('story');
$story->branch->range('0');
$story->gen(5);
global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->setStageToClosed(0)) && p() && e('0'); //不传入任何数据。

$storyTest = new storyTest();
r($storyTest->setStageToClosedTest(1)) && p('stage') && e('closed'); //传入需求，检查需求的阶段。

$story = $storyTest->setStageToClosedTest(2, array(0, 1));
r($story) && p('stage') && e('closed');                             //传入需求，检查需求的阶段。
r(count($story->stages)) && p() && e('1');                          //传入需求，检查storystage记录数。
r($story->stages[0]) && p('story,branch,stage') && e('2,1,closed'); //传入需求，检查分支为 0 的storystage记录。