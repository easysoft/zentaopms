#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('productplan')->config('productplan')->gen(30);
zdTable('user')->gen(5);
su('admin');

/**

title=productTao->getPlanList();
timeout=0
cid=1

*/

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);

global $tester;
$tester->loadModel('product');
r($tester->product->getPlanList($productIdList[0]))     && p()                   && e('0');         // 测试传入空的产品ID列表
r($tester->product->getPlanList($productIdList[1])[10]) && p('30:product,title') && e('10,计划30'); // 测试传入产品ID列表
r($tester->product->getPlanList($productIdList[2]))     && p()                   && e('0');         // 测试传入不存在产品ID列表
