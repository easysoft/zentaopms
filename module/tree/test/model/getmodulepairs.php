#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getModulePairs();
timeout=0
cid=1

- 测试获取root 1 type story 的树结构 @10
- 测试获取root 1 type task  的树结构 @10
- 测试获取root 1 type case  的树结构 @20
- 测试获取root 1 type bug   的树结构 @20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

$tree = new treeTest();

r($tree->getModulePairsTest(1, 'story')) && p() && e('10'); // 测试获取root 1 type story 的树结构
r($tree->getModulePairsTest(1, 'task'))  && p() && e('10'); // 测试获取root 1 type task  的树结构
r($tree->getModulePairsTest(1, 'case'))  && p() && e('20'); // 测试获取root 1 type case  的树结构
r($tree->getModulePairsTest(1, 'bug'))   && p() && e('20'); // 测试获取root 1 type bug   的树结构