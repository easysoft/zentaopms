#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->getProduct();
cid=1
pid=1

获取module 1821 的产品 >> 正常产品1
获取module 1825 的产品 >> 正常产品2
获取module 2621 的产品 >> 正常产品1
获取module 21 的产品 >> 0
获取module 25 的产品 >> 0
获取module 3021 的产品 >> 0
获取module 3621 的产品 >> 0
获取module 3622 的产品 >> 0
获取module 3722 的产品 >> 0
获取module 0 的产品 >> 0

*/
$moduleID = array(1821, 1825, 2621, 21, 25, 3021, 3621, 3622, 3722, 0);

$tree = new treeTest();

r($tree->getProductTest($moduleID[0])) && p('name') && e('正常产品1'); // 获取module 1821 的产品
r($tree->getProductTest($moduleID[1])) && p('name') && e('正常产品2'); // 获取module 1825 的产品
r($tree->getProductTest($moduleID[2])) && p('name') && e('正常产品1'); // 获取module 2621 的产品
r($tree->getProductTest($moduleID[3])) && p('name') && e('0');         // 获取module 21 的产品
r($tree->getProductTest($moduleID[4])) && p('name') && e('0');         // 获取module 25 的产品
r($tree->getProductTest($moduleID[5])) && p('name') && e('0');         // 获取module 3021 的产品
r($tree->getProductTest($moduleID[6])) && p('name') && e('0');         // 获取module 3621 的产品
r($tree->getProductTest($moduleID[7])) && p('name') && e('0');         // 获取module 3622 的产品
r($tree->getProductTest($moduleID[8])) && p('name') && e('0');         // 获取module 3722 的产品
r($tree->getProductTest($moduleID[9])) && p('name') && e('0');         // 获取module 0 的产品