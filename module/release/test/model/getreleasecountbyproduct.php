#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('product')->config('product')->gen(10);
zdTable('release')->config('release')->gen(100);

/**

title=taskModel->getReleaseCountByProduct();
timeout=0
cid=0

*/

$productIdList        = range(1, 3);
$noExistProductIdList = range(200, 203);
$today                = helper::today();
$yesterday            = date('Y-m-d', strtotime('-1 day', strtotime($today)));
$tomorrow             = date('Y-m-d', strtotime('+1 day', strtotime($today)));
$dateList             = array($today, $yesterday, $tomorrow);

global $tester;
$releaseModel = $tester->loadModel('release');

r($releaseModel->getReleaseCountByProduct(array()))                             && p()                   && e('0'); // 测试传入的productId为空时，发布个数
r($releaseModel->getReleaseCountByProduct($productIdList))                      && p('1[normal]:count')  && e('8'); // 测试获取产品1的发布个数
r($releaseModel->getReleaseCountByProduct($noExistProductIdList))               && p()                   && e('0'); // 测试获取不存在产品的发布
r(count($releaseModel->getReleaseCountByProduct($productIdList)))               && p()                   && e('3'); // 测试获取产品的个数
r(count($releaseModel->getReleaseCountByProduct($productIdList, $dateList[0]))) && p('1[normal]:count')  && e('1'); // 测试获取产品1今日创建的发布个数
r(count($releaseModel->getReleaseCountByProduct($productIdList, $dateList[1]))) && p('1[normal]:count')  && e('1'); // 测试获取产品1今日创建的发布个数
r(count($releaseModel->getReleaseCountByProduct($productIdList, $dateList[2]))) && p('1[normal]:count')  && e('0'); // 测试获取产品1今日创建的发布个数
