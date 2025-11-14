#!/usr/bin/env php
<?php

/**

title=测试 storyModel->linkToExecutionForCreate();
cid=18652

- 不传入执行，也不传入需求。 @0
- 传入执行，不传入需求。 @0
- 不传入执行，传入需求。 @0
- 传入项目，传入需求。属性action @linked2project
- 传入看板执行，传入需求。属性action @linked2kanban
- 传入迭代执行，传入需求。属性action @linked2execution
- 传入不启用迭代的执行，传入需求。属性action @linked2project
- 传入看板执行，传入需求，再传入额外信息。属性action @linked2kanban

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

zenData('story')->gen(5);
$project = zenData('project');
$project->id->range('11-20');
$project->project->range('0,1{3}');
$project->type->range('project,kanban,sprint,sprint');
$project->multiple->range('1{3},0');
$project->gen(4);

$storyTest = new storyTest();

r($storyTest->linkToExecutionForCreateTest(0,  0)) && p() && e('0'); //不传入执行，也不传入需求。
r($storyTest->linkToExecutionForCreateTest(11, 0)) && p() && e('0'); //传入执行，不传入需求。
r($storyTest->linkToExecutionForCreateTest(0,  1)) && p() && e('0'); //不传入执行，传入需求。

r($storyTest->linkToExecutionForCreateTest(11, 1)) && p('action') && e('linked2project');   //传入项目，传入需求。
r($storyTest->linkToExecutionForCreateTest(12, 1)) && p('action') && e('linked2kanban');    //传入看板执行，传入需求。
r($storyTest->linkToExecutionForCreateTest(13, 1)) && p('action') && e('linked2execution'); //传入迭代执行，传入需求。
r($storyTest->linkToExecutionForCreateTest(14, 1)) && p('action') && e('linked2project');   //传入不启用迭代的执行，传入需求。

r($storyTest->linkToExecutionForCreateTest(12, 1, 'laneID=1&columnID=2')) && p('action') && e('linked2kanban'); //传入看板执行，传入需求，再传入额外信息。
