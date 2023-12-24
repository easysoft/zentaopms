#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-30');
$project->type->range('sprint');
$project->gen(20);

zdTable('product')->gen(20);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('1-15');
$projectProduct->product->range('1-30');
$projectProduct->gen(20);

$story = zdTable('story');
$story->module->range('1-100');
$story->type->range('story');
$story->gen(20);

$projectStory = zdTable('projectstory');
$projectStory->project->range('1-15');
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

$branch = zdTable('branch');
$branch->product->range('1');
$branch->gen(20);

/**

title=测试 treeModel->getTaskOptionMenu();
timeout=0
cid=1

- 测试获取 root 0 task目录 @,/
- 测试获取 root 1 task目录 @,/,/正常产品1/这是一个模块1,/正常产品16/这是一个模块16,/这是一个模块31
- 测试获取 root 2 task目录 @,/,/正常产品2/这是一个模块2,/正常产品17/这是一个模块17,/这是一个模块32
- 测试获取 root 3 task目录 @,/,/正常产品3/这是一个模块3,/正常产品18/这是一个模块18,/这是一个模块33
- 测试获取 root 4 task目录 @,/,/正常产品4/这是一个模块4,/正常产品19/这是一个模块19,/这是一个模块34
- 测试获取 root 4 startModule 4 task目录 @,/,/正常产品4/这是一个模块4
- 测试获取 root 4 startModule 34 allModule task目录 @,/,/这是一个模块34

*/
$root  = array(0, 1, 2, 3, 4);
$extra = 'allModule';

$tree = new treeTest();
$tree->objectModel->app->user->admin = true;

r($tree->getTaskOptionMenuTest($root[0]))             && p('', '|') && e(',/');                                                                      // 测试获取 root 0 task目录
r($tree->getTaskOptionMenuTest($root[1]))             && p('', '|') && e(',/,/正常产品1/这是一个模块1,/正常产品16/这是一个模块16,/这是一个模块31');  // 测试获取 root 1 task目录
r($tree->getTaskOptionMenuTest($root[2]))             && p('', '|') && e(',/,/正常产品2/这是一个模块2,/正常产品17/这是一个模块17,/这是一个模块32');  // 测试获取 root 2 task目录
r($tree->getTaskOptionMenuTest($root[3]))             && p('', '|') && e(',/,/正常产品3/这是一个模块3,/正常产品18/这是一个模块18,/这是一个模块33');  // 测试获取 root 3 task目录
r($tree->getTaskOptionMenuTest($root[4]))             && p('', '|') && e(',/,/正常产品4/这是一个模块4,/正常产品19/这是一个模块19,/这是一个模块34');  // 测试获取 root 4 task目录
r($tree->getTaskOptionMenuTest($root[4], 4))          && p('', '|') && e(',/,/正常产品4/这是一个模块4');                                             // 测试获取 root 4 startModule 4 task目录
r($tree->getTaskOptionMenuTest($root[4], 34, $extra)) && p('', '|') && e(',/,/这是一个模块34');                                                      // 测试获取 root 4 startModule 34 allModule task目录
