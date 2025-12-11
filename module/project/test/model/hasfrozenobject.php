#!/usr/bin/env php
<?php
/**

title=测试 projectModel::hasFrozenObject();
timeout=0
cid=0

- 检查传空值 @0
- 检查敏捷项目冻结的需求 @1
- 检查瀑布项目冻结的需求 @1
- 检查冻结的设计 @1
- 检查冻结的阶段 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

zenData('projectstory')->gen(20);

$project = zenData('project');
$project->frozen->range('yes');
$project->gen(20);

$story = zenData('story');
$story->frozen->range('yes');
$story->gen(20);

$design = zenData('design');
$design->frozen->range('yes');
$design->gen(20);

su('admin');

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->hasFrozenObject(0, ''))           && p() && e('0'); // 检查传空值
r($projectModel->hasFrozenObject(11, 'story'))     && p() && e('1'); // 检查敏捷项目冻结的需求
r($projectModel->hasFrozenObject(12, 'story'))     && p() && e('1'); // 检查瀑布项目冻结的需求
r($projectModel->hasFrozenObject(41, 'design'))    && p() && e('1'); // 检查冻结的设计
r($projectModel->hasFrozenObject(10, 'execution')) && p() && e('1'); // 检查冻结的阶段
