#!/usr/bin/env php
<?php

/**

title=测试 storyModel->setStageToClosed();
cid=0

- 不传入任何数据。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storystage')->gen(0);
$story = zdTable('story');
$story->branch->range('0');
$story->gen(5);
global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->setStageToClosed(0)) && p() && e('0'); //不传入任何数据。

$storyTest = new storyTest();
r($storyTest->setStageToClosedTest(1)) && p('stage') && r('closed'); //传入需求，检查需求的阶段。

$story = $storyTest->setStageToClosedTest(2, array(0, 1));
r($story) && p('stage') && r('closed');                             //传入需求，检查需求的阶段。
r(count($story->stages)) && p() && r('2');                          //传入需求，检查storystage记录数。
r($story->stages[0]) && p('story,branch,stage') && r('2,0,closed'); //传入需求，检查分支为 0 的storystage记录。
r($story->stages[1]) && p('story,branch,stage') && r('2,1,closed'); //传入需求，检查分支为 1 的storystage记录。
