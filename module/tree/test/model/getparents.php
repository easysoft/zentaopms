#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getParents();
timeout=0
cid=1

- 获取 module 1 的父module @,1

- 获取 module 2 的父module @,2

- 获取 module 3 的父module @,3

- 获取 module 6 的父module @,1,6

- 获取 module 7 的父module @,2,7

- 获取 module 8 的父module @,3,8

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(100);

$tree = new treeTest();

r($tree->getParentsTest(1)) && p() && e(',1');   // 获取 module 1 的父module
r($tree->getParentsTest(2)) && p() && e(',2');   // 获取 module 2 的父module
r($tree->getParentsTest(3)) && p() && e(',3');   // 获取 module 3 的父module
r($tree->getParentsTest(6)) && p() && e(',1,6'); // 获取 module 6 的父module
r($tree->getParentsTest(7)) && p() && e(',2,7'); // 获取 module 7 的父module
r($tree->getParentsTest(8)) && p() && e(',3,8'); // 获取 module 8 的父module