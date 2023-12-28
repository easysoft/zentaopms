#!/usr/bin/env php
<?php

/**

title=测试 treeModel->getProduct();
timeout=0
cid=1

- 获取module 2 的产品属性name @正常产品1
- 获取module 7 的产品属性name @正常产品1
- 获取module 19 的产品属性name @多分支产品41
- 获取module 200 的产品属性name @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tree.class.php';
su('admin');

zdTable('module')->config('module')->gen(100);
zdTable('product')->gen(100);

$moduleID = array(2, 7, 19, 200);

$tree = new treeTest();

r($tree->getProductTest($moduleID[0])) && p('name') && e('正常产品1');    // 获取module 2 的产品
r($tree->getProductTest($moduleID[1])) && p('name') && e('正常产品1');    // 获取module 7 的产品
r($tree->getProductTest($moduleID[2])) && p('name') && e('多分支产品41'); // 获取module 19 的产品
r($tree->getProductTest($moduleID[3])) && p('name') && e('0');            // 获取module 200 的产品