#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

/**

title=测试 treeModel->getDocStructure();
timeout=0
cid=1

- 测试获取doc树 @3:2;

*/

zdTable('module')->config('module')->gen(30);

$tree = new treeTest();

r($tree->getDocStructureTest()) && p() && e('3:2;'); //测试获取doc树