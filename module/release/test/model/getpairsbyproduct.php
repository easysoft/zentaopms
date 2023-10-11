#!/usr/bin/env php
<?php
/**

title=releaseModel->getPairsByProduct();
timeout=0
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->config('product')->gen(5);
zdTable('release')->config('release')->gen(10);
zdTable('user')->gen(5);
su('admin');


$productIdList        = array(1);
$allProductIdList     = range(1, 5);
$noExistProductIdList = range(200, 203);

global $tester;
$releaseModel = $tester->loadModel('release');

r($releaseModel->getPairsByProduct(array()))                  && p()    && e('0');     // 测试空数据
r($releaseModel->getPairsByProduct($productIdList))           && p('1') && e('发布1'); // 测试获取产品1下的发布
r(count($releaseModel->getPairsByProduct($productIdList)))    && p()    && e('1');     // 测试获取产品1下的发布数量
r(count($releaseModel->getPairsByProduct($allProductIdList))) && p()    && e('5');     // 测试获取所有产品发布的数量
r($releaseModel->getPairsByProduct($noExistProductIdList))    && p()    && e('0');     // 测试获取不存在产品下的发布
