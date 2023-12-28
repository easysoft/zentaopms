#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getTreeStructure();
timeout=0
cid=1

- 测试获取root 1 story 的树结构 @2:1;12:0;
- 测试获取root 1 bug   的树结构 @2:1;12:0;4:1;14:0;
- 测试获取root 1 case  的树结构 @2:1;12:0;5:1;15:0;
- 测试获取root 1 doc   的树结构 @3:1;13:0;
- 测试获取root 1 task  的树结构 @0
- 测试获取root 41 story 的树结构 @16:1;
- 测试获取root 41 bug   的树结构 @17:1;
- 测试获取root 41 case  的树结构 @0
- 测试获取root 41 doc   的树结构 @0
- 测试获取root 41 task  的树结构 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

zdTable('module')->config('module')->gen(20);

$tree = new treeTest();

r($tree->getTreeStructureTest(1, 'story')) && p() && e('2:1;12:0;');          // 测试获取root 1 story 的树结构
r($tree->getTreeStructureTest(1, 'bug'))   && p() && e('2:1;12:0;4:1;14:0;'); // 测试获取root 1 bug   的树结构
r($tree->getTreeStructureTest(1, 'case'))  && p() && e('2:1;12:0;5:1;15:0;'); // 测试获取root 1 case  的树结构
r($tree->getTreeStructureTest(1, 'doc'))   && p() && e('3:1;13:0;');          // 测试获取root 1 doc   的树结构
r($tree->getTreeStructureTest(1, 'task'))  && p() && e('0');                  // 测试获取root 1 task  的树结构

r($tree->getTreeStructureTest(41, 'story')) && p() && e('16:1;'); // 测试获取root 41 story 的树结构
r($tree->getTreeStructureTest(41, 'bug'))   && p() && e('17:1;'); // 测试获取root 41 bug   的树结构
r($tree->getTreeStructureTest(41, 'case'))  && p() && e('0');     // 测试获取root 41 case  的树结构
r($tree->getTreeStructureTest(41, 'doc'))   && p() && e('0');     // 测试获取root 41 doc   的树结构
r($tree->getTreeStructureTest(41, 'task'))  && p() && e('0');     // 测试获取root 41 task  的树结构