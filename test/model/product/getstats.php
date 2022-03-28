#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getStats();
cid=1
pid=1

*/

$product = new productTest('admin');

r($product->getStatsTest()) && p('0:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品1,0,4,9,9,0,0,0,0,0');   // 测试获取产品1的stats信息
r($product->getStatsTest()) && p('1:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品2,0,1,4,4,0,0,0,0,0');   // 测试获取产品2的stats信息
r($product->getStatsTest()) && p('2:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品3,0,0,4,4,0,0,0,0,0');   // 测试获取产品3的stats信息
r($product->getStatsTest()) && p('3:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品4,0,0,4,4,0,0,0,0,0');   // 测试获取产品4的stats信息
r($product->getStatsTest()) && p('4:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品5,0,0,4,4,0,0,0,0,0');   // 测试获取产品5的stats信息
r($product->getStatsTest()) && p('5:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品6,0,0,4,4,0,0,0,0,25');  // 测试获取产品6的stats信息
r($product->getStatsTest()) && p('6:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品7,0,0,4,4,0,0,0,0,25');  // 测试获取产品7的stats信息
r($product->getStatsTest()) && p('7:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品8,0,0,4,4,0,0,0,0,25');  // 测试获取产品8的stats信息
