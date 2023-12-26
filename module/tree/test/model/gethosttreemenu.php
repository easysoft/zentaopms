#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getHostTreeMenu();
timeout=0
cid=1

- 测试获取Host模块 @1|2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';

su('admin');

$module = zdTable('module');
$module->type->range('host');
$module->parent->range('0,1');
$module->path->range(',1,`,1,2,`');
$module->gen(2);

$tree = new treeTest();

r($tree->getHostTreeMenuTest())  && p() && e('1|2');  // 测试获取Host模块