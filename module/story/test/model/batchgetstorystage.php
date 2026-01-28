#!/usr/bin/env php
<?php

/**

title=测试 storyModel::batchGetStoryStage();
timeout=0
cid=18474

- 执行storyTest模块的batchGetStoryStageTest方法，参数是array 第100条的0:stage属性 @wait
- 执行storyTest模块的batchGetStoryStageTest方法，参数是array 第101条的0:stage属性 @planned
- 执行storyTest模块的batchGetStoryStageTest方法，参数是array  @~~
- 执行storyTest模块的batchGetStoryStageTest方法，参数是array  @~~
- 执行storyTest模块的batchGetStoryStageTest方法，参数是array 第103条的0:stage属性 @developing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$storystage = zenData('storystage');
$storystage->story->range('100-104');
$storystage->branch->range('0');
$storystage->stage->range('wait,planned,projected,developing,testing');
$storystage->stagedBy->range('admin');
$storystage->gen(5);

su('admin');

$storyTest = new storyModelTest();

r($storyTest->batchGetStoryStageTest(array(100,101,102))) && p('100:0:stage') && e('wait');
r($storyTest->batchGetStoryStageTest(array(101))) && p('101:0:stage') && e('planned');
r($storyTest->batchGetStoryStageTest(array(999))) && p() && e('~~');
r($storyTest->batchGetStoryStageTest(array())) && p() && e('~~');
r($storyTest->batchGetStoryStageTest(array(103))) && p('103:0:stage') && e('developing');