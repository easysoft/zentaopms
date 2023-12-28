#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getProductStructure();
timeout=0
cid=1

- 测试获取 root 1  type story 的树结构 @2:2;12:0;22:0;
- 测试获取 root 2  type bug   的树结构 @0
- 测试获取 root 41 type case  的树结构 @16:1;
- 测试获取 root 1  type story 的树结构 @2:2;12:0;22:0;4:2;14:0;24:0;
- 测试获取 root 2  type bug   的树结构 @0
- 测试获取 root 41 type case  的树结构 @16:1;17:1;
- 测试获取 root 1  type story 的树结构 @2:2;12:0;22:0;5:2;15:0;25:0;
- 测试获取 root 2  type bug   的树结构 @0
- 测试获取 root 41 type case  的树结构 @16:1;

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(30);
zdTable('product')->gen(100);
zdTable('projectstory')->gen(30);
zdTable('projectcase')->gen(30);
zdTable('bug')->gen(30);
zdTable('case')->gen(30);

$projectproduct = zdTable('projectproduct');
$projectproduct->project->range('1-100');
$projectproduct->product->range('1-100');
$projectproduct->gen(100);

$root = array(1, 2, 41);
$type = array('story', 'bug', 'case');

$tree = new treeTest();

r($tree->getProductStructureTest($root[0], $type[0])) && p() && e('2:2;12:0;22:0;');               // 测试获取 root 1  type story 的树结构
r($tree->getProductStructureTest($root[1], $type[0])) && p() && e('0');                            // 测试获取 root 2  type bug   的树结构
r($tree->getProductStructureTest($root[2], $type[0])) && p() && e('16:1;');                        // 测试获取 root 41 type case  的树结构
r($tree->getProductStructureTest($root[0], $type[1])) && p() && e('2:2;12:0;22:0;4:2;14:0;24:0;'); // 测试获取 root 1  type story 的树结构
r($tree->getProductStructureTest($root[1], $type[1])) && p() && e('0');                            // 测试获取 root 2  type bug   的树结构
r($tree->getProductStructureTest($root[2], $type[1])) && p() && e('16:1;17:1;');                        // 测试获取 root 41 type case  的树结构
r($tree->getProductStructureTest($root[0], $type[2])) && p() && e('2:2;12:0;22:0;5:2;15:0;25:0;'); // 测试获取 root 1  type story 的树结构
r($tree->getProductStructureTest($root[1], $type[2])) && p() && e('0');                            // 测试获取 root 2  type bug   的树结构
r($tree->getProductStructureTest($root[2], $type[2])) && p() && e('16:1;');                            // 测试获取 root 41 type case  的树结构