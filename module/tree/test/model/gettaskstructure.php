#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskStructure();
timeout=0
cid=19383

- 获取root 1  的task 结构 @模块1:1;模块11:0
- 获取root 2  的task 结构 @正常产品2:0;
- 获取root 3  的task 结构 @正常产品3:0;
- 获取root 41 的task 结构 @模块18:0
- 获取root 100 的task 结构 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(20);
$projectproduct = zenData('projectproduct');
$projectproduct->project->range('2-100');
$projectproduct->product->range('2-100');
$projectproduct->gen(10);

$tree = new treeTest();

r($tree->getTaskStructureTest(1))  && p() && e('模块1:1;模块11:0'); // 获取root 1  的task 结构
r($tree->getTaskStructureTest(2))  && p() && e('正常产品2:0;'); // 获取root 2  的task 结构
r($tree->getTaskStructureTest(3))  && p() && e('正常产品3:0;'); // 获取root 3  的task 结构
r($tree->getTaskStructureTest(41)) && p() && e('模块18:0'); // 获取root 41 的task 结构
r($tree->getTaskStructureTest(100)) && p() && e('0'); // 获取root 100 的task 结构