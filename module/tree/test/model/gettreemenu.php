#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTreeMenu();
timeout=0
cid=19386

- 测试获取root 1 的story模块 @7|2|12
- 测试获取root 1 的task模块 @6|1|11
- 测试获取root 1 的doc模块 @8|3|13
- 测试获取root 1 的case模块 @7|10|2|12|5|15
- 测试获取root 1 的bug模块 @7|9|2|12|4|14

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

su('admin');

zenData('module')->loadYaml('module')->gen(20);
zenData('projectproduct')->gen(20);
zenData('projectstory')->gen(20);
zenData('projectcase')->gen(20);
zenData('task')->gen(20);
zenData('story')->gen(20);
zenData('case')->gen(20);
zenData('bug')->gen(20);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(20);

$tree = new treeTest();

r($tree->getTreeMenuTest(1, 'story')) && p() && e('7|2|12');         // 测试获取root 1 的story模块
r($tree->getTreeMenuTest(1, 'task'))  && p() && e('6|1|11');         // 测试获取root 1 的task模块
r($tree->getTreeMenuTest(1, 'doc'))   && p() && e('8|3|13');         // 测试获取root 1 的doc模块
r($tree->getTreeMenuTest(1, 'case'))  && p() && e('7|10|2|12|5|15'); // 测试获取root 1 的case模块
r($tree->getTreeMenuTest(1, 'bug'))   && p() && e('7|9|2|12|4|14');  // 测试获取root 1 的bug模块