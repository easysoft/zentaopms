#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/product.class.php';

/**

title=productModel->getStats();
cid=1
pid=1

*/

$product = new productTest('admin');

$productIdList = array(1,2);
r($product->getStatsTest($productIdList)) && p('1:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品1,0,1,500,500,0,0,0,70,0'); // 测试获取产品1的stats信息
r($product->getStatsTest($productIdList)) && p('2:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品2,0,1,500,500,0,0,0,80,0'); // 测试获取产品2的stats信息
