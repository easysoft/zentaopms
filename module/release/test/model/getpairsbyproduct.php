#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->config('product')->gen(10);
zdTable('release')->config('release')->gen(100);

/**

title=taskModel->getReleasesByProduct();
timeout=0
cid=0

*/

$productIdList        = array(1);
$allProductIdList     = range(1, 8);
$noExistProductIdList = range(200, 203);

global $tester;
$releaseModel = $tester->loadModel('release');
r($releaseModel->getReleasesByProduct(array()))                  && p()    && e('0');     // 测试空数据
r($releaseModel->getReleasesByProduct($productIdList))           && p('1') && e('发布1'); // 测试获取产品1下的发布
r(count($releaseModel->getReleasesByProduct($productIdList)))    && p()    && e('8');     // 测试获取产品1下的发布数量
r(count($releaseModel->getReleasesByProduct($allProductIdList))) && p()    && e('64');    // 测试获取所有产品发布的数量
r($releaseModel->getReleasesByProduct($noExistProductIdList))    && p()    && e('0');     // 测试获取不存在产品下的发布
