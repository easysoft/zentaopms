#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignStoryForCreate();
timeout=0
cid=18898

- 执行taskZenTest模块的assignStoryForCreateTest方法，参数是1, 1
 - 属性executionID @1
 - 属性moduleID @1
- 执行taskZenTest模块的assignStoryForCreateTest方法，参数是0, 1
 - 属性executionID @0
 - 属性moduleID @1
- 执行taskZenTest模块的assignStoryForCreateTest方法，参数是1, 0
 - 属性executionID @1
 - 属性moduleID @0
- 执行taskZenTest模块的assignStoryForCreateTest方法，参数是999, 1
 - 属性executionID @999
 - 属性moduleID @1
- 执行taskZenTest模块的assignStoryForCreateTest方法，参数是-1, 1
 - 属性executionID @-1
 - 属性moduleID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

$task = zenData('task');
$task->loadYaml('task_assignstoryforcreate', false, 2)->gen(10);

$story = zenData('story');
$story->loadYaml('story_assignstoryforcreate', false, 2)->gen(15);

$project = zenData('project');
$project->loadYaml('project_assignstoryforcreate', false, 2)->gen(10);

$projectstory = zenData('projectstory');
$projectstory->project->range('1-10');
$projectstory->story->range('1-15');
$projectstory->version->range('1');
$projectstory->gen(20);

su('admin');

$taskZenTest = new taskZenTest();

r($taskZenTest->assignStoryForCreateTest(1, 1)) && p('executionID,moduleID') && e('1,1');
r($taskZenTest->assignStoryForCreateTest(0, 1)) && p('executionID,moduleID') && e('0,1');
r($taskZenTest->assignStoryForCreateTest(1, 0)) && p('executionID,moduleID') && e('1,0');
r($taskZenTest->assignStoryForCreateTest(999, 1)) && p('executionID,moduleID') && e('999,1');
r($taskZenTest->assignStoryForCreateTest(-1, 1)) && p('executionID,moduleID') && e('-1,1');