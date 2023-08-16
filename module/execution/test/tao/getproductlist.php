#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(200);
su('admin');

zdTable('project')->config('execution', true)->gen(30);
zdTable('product')->config('product', true)->gen(30);
zdTable('projectproduct')->config('projectproduct', true)->gen(60);

/**

title=测试 executionModel->getProductList();
timeout=0
cid=1

*/

global $tester;
$executionModel = $tester->loadModel('execution');

r(count($executionModel->getProductList(11)))   && p()     && e('1');    // 测试获取敏捷项目下执行的产品个数
r(current($executionModel->getProductList(11))) && p('id') && e('101');  // 测试获取敏捷项目下执行的产品
