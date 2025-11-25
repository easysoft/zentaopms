#!/usr/bin/env php
<?php
/**

title=测试 treeModel->getParents();
cid=19374

- 获取 module 1 的未删除父module @,1

- 获取 module 2 的未删除父module @,2

- 获取 module 3 的未删除父module @,3

- 获取 module 6 的未删除父module @,1,6

- 获取 module 7 的未删除父module @,2,7

- 获取 module 8 的未删除父module @,3,8

- 获取 module 1 的全部父module @,1

- 获取 module 2 的全部父module @,2

- 获取 module 3 的全部父module @,3

- 获取 module 6 的全部父module @,1,6

- 获取 module 7 的全部父module @,2,7

- 获取 module 8 的全部父module @,3,8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';
su('admin');

zenData('module')->loadYaml('module')->gen(100);

$tree = new treeTest();

r($tree->getParentsTest(1)) && p() && e(',1');   // 获取 module 1 的未删除父module
r($tree->getParentsTest(2)) && p() && e(',2');   // 获取 module 2 的未删除父module
r($tree->getParentsTest(3)) && p() && e(',3');   // 获取 module 3 的未删除父module
r($tree->getParentsTest(6)) && p() && e(',1,6'); // 获取 module 6 的未删除父module
r($tree->getParentsTest(7)) && p() && e(',2,7'); // 获取 module 7 的未删除父module
r($tree->getParentsTest(8)) && p() && e(',3,8'); // 获取 module 8 的未删除父module

r($tree->getParentsTest(1, true)) && p() && e(',1');   // 获取 module 1 的全部父module
r($tree->getParentsTest(2, true)) && p() && e(',2');   // 获取 module 2 的全部父module
r($tree->getParentsTest(3, true)) && p() && e(',3');   // 获取 module 3 的全部父module
r($tree->getParentsTest(6, true)) && p() && e(',1,6'); // 获取 module 6 的全部父module
r($tree->getParentsTest(7, true)) && p() && e(',2,7'); // 获取 module 7 的全部父module
r($tree->getParentsTest(8, true)) && p() && e(',3,8'); // 获取 module 8 的全部父module
