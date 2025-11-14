#!/usr/bin/env php
<?php

/**

title=测试 storyModel->setStageToPlanned();
timeout=0
cid=18660

- 不传入任何数据。 @0
- 传入未关联计划的需求。属性stage @wait
- 传入未关联计划的需求。属性stage @wait
- 传入关联一个计划的需求。属性stage @planned
- 传入关联多个计划的需求。属性stage @planned
- 传入关联计划的需求，并传入分支信息，检查storystage记录数。 @2
- 传入关联计划的需求，并传入分支信息，检查分支为 0 的 storystage 记录。
 - 属性story @4
 - 属性branch @0
 - 属性stage @planned
- 传入关联计划的需求，并传入分支信息，检查分支为 1 的 storystage 记录。
 - 属性story @4
 - 属性branch @1
 - 属性stage @planned
- 传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查storystage记录数。 @3
- 传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 0 的 storystage 记录。
 - 属性story @4
 - 属性branch @0
 - 属性stage @planned
- 传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 1 的 storystage 记录。
 - 属性story @4
 - 属性branch @1
 - 属性stage @planned
- 传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 2 的 storystage 记录。
 - 属性story @4
 - 属性branch @2
 - 属性stage @projected

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('storystage')->gen(0);
$story = zenData('story');
$story->plan->range('0,``,1,`2,3`');
$story->branch->range('0');
$story->gen(5);
global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->setStageToPlanned(0)) && p() && e('0'); //不传入任何数据。

$storyTest = new storyTest();
r($storyTest->setStageToPlannedTest(1)) && p('stage') && e('wait');    //传入未关联计划的需求。
r($storyTest->setStageToPlannedTest(2)) && p('stage') && e('wait');    //传入未关联计划的需求。
r($storyTest->setStageToPlannedTest(3)) && p('stage') && e('planned'); //传入关联一个计划的需求。
r($storyTest->setStageToPlannedTest(4)) && p('stage') && e('planned'); //传入关联多个计划的需求。

$story = $storyTest->setStageToPlannedTest(4, array(0 => 'planned', '1' => 'planned'));
r(count($story->stages)) && p() && e('2');                           //传入关联计划的需求，并传入分支信息，检查storystage记录数。
r($story->stages[0]) && p('story,branch,stage') && e('4,0,planned'); //传入关联计划的需求，并传入分支信息，检查分支为 0 的 storystage 记录。
r($story->stages[1]) && p('story,branch,stage') && e('4,1,planned'); //传入关联计划的需求，并传入分支信息，检查分支为 1 的 storystage 记录。

$oldStages[2] = new stdclass();
$oldStages[2]->story    = 4;
$oldStages[2]->branch   = 2;
$oldStages[2]->stage    = 'projected';
$oldStages[2]->stagedBy = 'admin';
$story = $storyTest->setStageToPlannedTest(4, array(0 => 'planned', '1' => 'planned', '2' => 'planned'), $oldStages);
r(count($story->stages)) && p() && e('3');                             //传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查storystage记录数。
r($story->stages[0]) && p('story,branch,stage') && e('4,0,planned');   //传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 0 的 storystage 记录。
r($story->stages[1]) && p('story,branch,stage') && e('4,1,planned');   //传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 1 的 storystage 记录。
r($story->stages[2]) && p('story,branch,stage') && e('4,2,projected'); //传入关联计划的需求，并传入分支信息，还有旧有点storystage记录，检查分支为 2 的 storystage 记录。