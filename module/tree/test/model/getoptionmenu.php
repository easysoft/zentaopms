#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('product')->gen(50);
zdTable('branch')->gen(10);
$module = zdTable('module');
$module->root->range('1-50');
$module->gen(100);

/**

title=测试 treeModel->getOptionMenu();
cid=1
pid=1

测试获取root 1 type story 的目录 >> 13
测试获取root 2 type story 的目录 >> 13
测试获取root 41 type story 的目录 >> 11
测试获取root 42 type story 的目录 >> 11
测试获取root 101 type task 的目录 >> 5
测试获取root 102 type task 的目录 >> 4
测试获取root 1 type doc 的目录 >> 4
测试获取root 2 type doc 的目录 >> 4

*/
$root = array(1, 2, 41, 42);

$tree = new treeTest();

r($tree->getOptionMenuTest($root[0])) && p('0')  && e('/');                    // 测试获取root 1 type story 的目录
r($tree->getOptionMenuTest($root[1])) && p('2')  && e('/这是一个模块2');       // 测试获取root 2 type story 的目录
r($tree->getOptionMenuTest($root[2])) && p('41') && e('/主干/这是一个模块41'); // 测试获取root 41 type story 的目录
r($tree->getOptionMenuTest($root[3])) && p('92') && e('/主干/这是一个模块92'); // 测试获取root 42 type story 的目录
