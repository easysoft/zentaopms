#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

/**

title=测试 treeModel->getDocStructure();
timeout=0
cid=19367

- 测试获取doc树的数量 @1
- 测试获取doc树的第1条数据
 - 第0条的id属性 @3
 - 第0条的root属性 @1
 - 第0条的branch属性 @0
 - 第0条的name属性 @模块3

*/

zenData('module')->loadYaml('module')->gen(30);

$tree = new treeTest();

$structure = $tree->getDocStructureTest();
r(count($structure)) && p() && e('1'); //测试获取doc树的数量
r($structure[1])     && p('0:id,root,branch,name') && e('3,1,0,模块3'); //测试获取doc树的第1条数据