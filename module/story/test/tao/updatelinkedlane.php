#!/usr/bin/env php
<?php

/**

title=测试 storyModel->updateLinkedLane();
cid=18662

- 不传入任何数据。 @0
- 只传入需求 ID。 @0
- 只传入关联的执行。 @0
- 传入需求 ID，传入关联的执行不是看板。 @0
- 传入需求 ID，传入关联的执行中有看板。 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-5');
$project->gen(5);

global $tester;
$storyModel = $tester->loadModel('story');

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->kanban = false;

r($storyModel->updateLinkedLane(0, array()))         && p() && e('0'); //不传入任何数据。
r($storyModel->updateLinkedLane(1, array()))         && p() && e('0'); //只传入需求 ID。
r($storyModel->updateLinkedLane(0, $linkedProjects)) && p() && e('0'); //只传入关联的执行。
r($storyModel->updateLinkedLane(1, $linkedProjects)) && p() && e('0'); //传入需求 ID，传入关联的执行不是看板。

$linkedProjects = array();
$linkedProjects[1] = new stdclass();
$linkedProjects[1]->kanban = false;
$linkedProjects[2] = new stdclass();
$linkedProjects[2]->kanban = true;
r($storyModel->updateLinkedLane(1, $linkedProjects)) && p() && e('1'); //传入需求 ID，传入关联的执行中有看板。
