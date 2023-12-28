#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getProjectStoryTreeMenu();
timeout=0
cid=1

- 测试获取项目1的Story模块 @7|2
- 测试获取项目2的Story模块 @7|2|12
- 测试不存在项目的Story模块 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1');
$projectproduct->gen(20);

$projectstory = zdTable('projectstory');
$projectstory->project->range('1-10');
$projectstory->story->range('1-20');
$projectstory->gen(20);

$story = zdTable('story');
$story->module->range('2,7,12');
$story->gen(100);

$tree = new treeTest();

r($tree->getProjectStoryTreeMenuTest(1))  && p() && e('7|2');    // 测试获取项目1的Story模块
r($tree->getProjectStoryTreeMenuTest(2))  && p() && e('7|2|12'); // 测试获取项目2的Story模块
r($tree->getProjectStoryTreeMenuTest(30)) && p() && e('0');      // 测试不存在项目的Story模块