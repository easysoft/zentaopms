#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskTreeModules();
timeout=0
cid=1

- 测试获取 execution 1 parent false story 的树 @1,11,31

- 测试获取 execution 2 parent true story 的树 @2,12,32

- 测试获取 execution 3 parent false bug 的树 @3
- 测试获取 execution 4 parent false case 的树 @4,14

- 测试获取 execution 4 parent false  的树 @4,34

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-30');
$project->type->range('sprint');

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('1-30');
$projectProduct->product->range('1-30');
$projectProduct->gen(20);

$story = zdTable('story');
$story->module->range('1-100');
$story->type->range('story');
$story->gen(20);

$projectStory = zdTable('projectstory');
$projectStory->project->range('1-30');
$projectStory->story->range('1-30');
$projectStory->gen(20);

$case = zdTable('case');
$case->execution->range('1-30');
$case->module->range('1-100');
$case->gen(20);

$projectCase = zdTable('projectcase');
$projectCase->project->range('1-30');
$projectCase->gen(20);

$task = zdTable('task');
$task->execution->range('1-30');
$task->module->range('1-100');
$task->gen(20);

$bug = zdTable('bug');
$bug->execution->range('1-30');
$bug->module->range('1-100');
$bug->gen(20);

$module = zdTable('module');
$module->root->range('1-30');
$module->type->range('story{30},task{30},case{30},bug{30}');
$module->gen(120);

$executionID = array(1, 2, 3, 4);
$parent      = array(false, true);
$linkObject  = array('story', 'case', 'bug', '');

$tree = new treeTest();

r($tree->getTaskTreeModulesTest($executionID[0], $parent[0], $linkObject[0])) && p() && e('1,11,31'); // 测试获取 execution 1 parent false story 的树
r($tree->getTaskTreeModulesTest($executionID[1], $parent[1], $linkObject[0])) && p() && e('2,12,32'); // 测试获取 execution 2 parent true story 的树
r($tree->getTaskTreeModulesTest($executionID[2], $parent[0], $linkObject[2])) && p() && e('3');       // 测试获取 execution 3 parent false bug 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[0], $linkObject[1])) && p() && e('4,14');    // 测试获取 execution 4 parent false case 的树
r($tree->getTaskTreeModulesTest($executionID[3], $parent[0], $linkObject[3])) && p() && e('4,34');    // 测试获取 execution 4 parent false  的树