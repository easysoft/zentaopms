#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTaskStructure();
timeout=0
cid=1

- 获取root 1  的task 结构 @模块1:1;模块11:0
- 获取root 41 的task 结构 @模块18:0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(20);
$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('2-100');
$projectproduct->product->range('2-100');
$projectproduct->gen(10);

$tree = new treeTest();

r($tree->getTaskStructureTest(1))  && p() && e('模块1:1;模块11:0'); // 获取root 1  的task 结构
r($tree->getTaskStructureTest(41)) && p() && e('模块18:0');         // 获取root 41 的task 结构