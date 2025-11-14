#!/usr/bin/env php
<?php

/**

title=测试 treeModel->changeRoot();
timeout=0
cid=0

- 测试修改module 12 的root从 1 修改为 2属性id @6
属性root @6
- 测试修改module 2 的root从 1 修改为 2，子模块module 7同时修改属性id @20
属性root @20
- 测试修改module 14 的 root从 1 修改为 3属性id @6
属性root @6
- 测试修改module 4 的root从 1 修改为 3，子模块module 9同时修改属性id @20
属性root @20
- 测试修改module 15 的root从 1 修改为 4属性id @6
属性root @6
- 测试修改module 5 的root从 1 修改为 4，子模块module 10同时修改属性id @20
属性root @20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(100);

$story = zenData('story');
$story->id->range('1-30');
$story->product->range('1');
$story->module->range('2,7,12');
$story->gen(20);

$bug = zenData('bug');
$bug->id->range('1-30');
$bug->product->range('1,2,3');
$bug->module->range('4,9,14');
$bug->gen(20);

$case = zenData('case');
$case->id->range('1-30');
$case->product->range('1');
$case->module->range('5,10,15');
$case->gen(20);

$tree = new treeTest();

r($tree->changeRootTest(12, 1, 2, 'story')) && p('id,root') && e('6');  // 测试修改module 12 的root从 1 修改为 2
r($tree->changeRootTest(2,  1, 2, 'story')) && p('id,root') && e('20'); // 测试修改module 2 的root从 1 修改为 2，子模块module 7同时修改

r($tree->changeRootTest(14, 1, 3, 'bug')) && p('id,root') && e('6');  // 测试修改module 14 的 root从 1 修改为 3
r($tree->changeRootTest(4,  1, 3, 'bug')) && p('id,root') && e('20'); // 测试修改module 4 的root从 1 修改为 3，子模块module 9同时修改

r($tree->changeRootTest(15, 1, 4, 'case')) && p('id,root') && e('6');  // 测试修改module 15 的root从 1 修改为 4
r($tree->changeRootTest(5,  1, 4, 'case')) && p('id,root') && e('20'); // 测试修改module 5 的root从 1 修改为 4，子模块module 10同时修改