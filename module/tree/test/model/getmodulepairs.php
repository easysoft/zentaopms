#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getModulePairs();
timeout=0
cid=19370

- 测试获取root 1 type story 的树结构 @15
- 测试获取root 1 type task  的树结构 @30
- 测试获取root 1 type case  的树结构 @30
- 测试获取root 1 type bug   的树结构 @30
- 测试获取root 1 type feedback 的树结构 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

su('admin');

zenData('module')->loadYaml('module')->gen(100);
zenData('project')->gen(100);
zenData('product')->gen(100);
zenData('projectcase')->gen(100);
zenData('projectstory')->gen(100);
zenData('case')->gen(100);
zenData('bug')->gen(100);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$tree = new treeTest();

r($tree->getModulePairsTest(1, 'story'))    && p() && e('15'); // 测试获取root 1 type story 的树结构
r($tree->getModulePairsTest(1, 'task'))     && p() && e('30'); // 测试获取root 1 type task  的树结构
r($tree->getModulePairsTest(1, 'case'))     && p() && e('30'); // 测试获取root 1 type case  的树结构
r($tree->getModulePairsTest(1, 'bug'))      && p() && e('30'); // 测试获取root 1 type bug   的树结构
r($tree->getModulePairsTest(1, 'feedback')) && p() && e('0');  // 测试获取root 1 type feedback 的树结构