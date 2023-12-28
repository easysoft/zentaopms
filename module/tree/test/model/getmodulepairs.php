#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getModulePairs();
timeout=0
cid=1

- 测试获取root 1 type story 的树结构 @15
- 测试获取root 1 type task  的树结构 @30
- 测试获取root 1 type case  的树结构 @30
- 测试获取root 1 type bug   的树结构 @30

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(100);
zdTable('project')->gen(100);
zdTable('product')->gen(100);
zdTable('projectcase')->gen(100);
zdTable('projectstory')->gen(100);
zdTable('case')->gen(100);
zdTable('bug')->gen(100);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$tree = new treeTest();

r($tree->getModulePairsTest(1, 'story')) && p() && e('15'); // 测试获取root 1 type story 的树结构
r($tree->getModulePairsTest(1, 'task'))  && p() && e('30'); // 测试获取root 1 type task  的树结构
r($tree->getModulePairsTest(1, 'case'))  && p() && e('30'); // 测试获取root 1 type case  的树结构
r($tree->getModulePairsTest(1, 'bug'))   && p() && e('30'); // 测试获取root 1 type bug   的树结构