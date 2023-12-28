#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getDataStructure();
timeout=0
cid=1

- 测试获取root 1 type story 的树结构 @2:2;12:0;22:0;
- 测试获取root 1 type task  的树结构 @0
- 测试获取root 1 type bug   的树结构 @2:2;12:0;22:0;4:2;14:0;24:0;
- 测试获取root 1 type case  的树结构 @2:2;12:0;22:0;5:2;15:0;25:0;
- 测试获取root 1 type doc   的树结构 @3:2;13:0;23:0;
- 测试获取不存在root, type story 的树结构 @0
- 测试获取不存在root, type task  的树结构 @0
- 测试获取不存在root, type bug   的树结构 @0
- 测试获取不存在root, type case  的树结构 @0
- 测试获取不存在root, type doc   的树结构 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module', true)->gen(30);
$type = array('story', 'task', 'bug', 'case', 'doc');

$tree = new treeTest();

r($tree->getDataStructureTest(1, $type[0])) && p() && e('2:2;12:0;22:0;');               // 测试获取root 1 type story 的树结构
r($tree->getDataStructureTest(1, $type[1])) && p() && e('0');                            // 测试获取root 1 type task  的树结构
r($tree->getDataStructureTest(1, $type[2])) && p() && e('2:2;12:0;22:0;4:2;14:0;24:0;'); // 测试获取root 1 type bug   的树结构
r($tree->getDataStructureTest(1, $type[3])) && p() && e('2:2;12:0;22:0;5:2;15:0;25:0;'); // 测试获取root 1 type case  的树结构
r($tree->getDataStructureTest(1, $type[4])) && p() && e('3:2;13:0;23:0;');               // 测试获取root 1 type doc   的树结构
r($tree->getDataStructureTest(4, $type[0])) && p() && e('0');                            // 测试获取不存在root, type story 的树结构
r($tree->getDataStructureTest(4, $type[1])) && p() && e('0');                            // 测试获取不存在root, type task  的树结构
r($tree->getDataStructureTest(4, $type[2])) && p() && e('0');                            // 测试获取不存在root, type bug   的树结构
r($tree->getDataStructureTest(4, $type[3])) && p() && e('0');                            // 测试获取不存在root, type case  的树结构
r($tree->getDataStructureTest(4, $type[4])) && p() && e('0');                            // 测试获取不存在root, type doc   的树结构