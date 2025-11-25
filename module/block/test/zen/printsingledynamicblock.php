#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleDynamicBlock();
timeout=0
cid=15296

- 执行blockTest模块的printSingleDynamicBlockTest方法 属性productID @1
- 执行blockTest模块的printSingleDynamicBlockTest方法
 - 属性productID @999
 - 属性actionsCount @0
- 执行blockTest模块的printSingleDynamicBlockTest方法 属性productID @0
- 执行blockTest模块的printSingleDynamicBlockTest方法 属性usersCount @11
- 执行blockTest模块的printSingleDynamicBlockTest方法 属性productID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product', false, 2)->gen(10);
zenData('user')->gen(10);
zenData('action')->loadYaml('action', false, 2)->gen(50);

su('admin');

$blockTest = new blockZenTest();

global $app;
$app->session->set('product', 1);
r($blockTest->printSingleDynamicBlockTest()) && p('productID') && e('1');
$app->session->set('product', 999);
r($blockTest->printSingleDynamicBlockTest()) && p('productID,actionsCount') && e('999,0');
$app->session->set('product', 0);
r($blockTest->printSingleDynamicBlockTest()) && p('productID') && e('0');
$app->session->set('product', 1);
r($blockTest->printSingleDynamicBlockTest()) && p('usersCount') && e('11');
$app->session->set('product', 1);
r($blockTest->printSingleDynamicBlockTest()) && p('productID') && e('1');