#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('storystage')->gen(0);
$story = zdTable('story');
$story->plan->range('0,``,1,`2,3`');
$story->branch->range('0');
$story->gen(5);

/**

title=测试 storyModel->setStageToPlanned();
cid=1
pid=1

*/
global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->setStageToPlanned(0)) && p() && e('0');

$storyTest = new storyTest();
r($storyTest->setStageToPlannedTest(1)) && p('stage') && r('wait');
r($storyTest->setStageToPlannedTest(2)) && p('stage') && r('wait');
r($storyTest->setStageToPlannedTest(3)) && p('stage') && r('planned');
r($storyTest->setStageToPlannedTest(4)) && p('stage') && r('planned');

$story = $storyTest->setStageToPlannedTest(4, array(0 => 'planned', '1' => 'planned'));
r(count($story->stages)) && p() && r('2');
r($story->stages[0]) && p('story,branch,stage') && r('4,0,planned');
r($story->stages[1]) && p('story,branch,stage') && r('4,1,planned');

$oldStages[2] = new stdclass();
$oldStages[2]->story    = 4;
$oldStages[2]->branch   = 2;
$oldStages[2]->stage    = 'projected';
$oldStages[2]->stagedBy = 'admin';
$story = $storyTest->setStageToPlannedTest(4, array(0 => 'planned', '1' => 'planned', '2' => 'planned'), $oldStages);
r(count($story->stages)) && p() && r('3');
r($story->stages[0]) && p('story,branch,stage') && r('4,0,planned');
r($story->stages[1]) && p('story,branch,stage') && r('4,1,planned');
r($story->stages[2]) && p('story,branch,stage') && r('4,2,projected');
