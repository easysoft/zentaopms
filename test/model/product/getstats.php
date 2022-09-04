#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->getStats();
cid=1
pid=1

测试获取产品1的stats信息 >> 正常产品1,0,5,9,9,0,0,0,0,0
测试获取产品101的stats信息 >> 产品正常产品1,0,0,0,0,0,0,0,0,0
测试获取产品2的stats信息 >> 正常产品2,0,2,4,4,0,0,0,0,0
测试获取产品102的stats信息 >> 产品正常产品2,0,0,0,0,0,0,0,0,0
测试获取产品3的stats信息 >> 正常产品3,0,0,4,4,0,0,0,0,0
测试获取产品103的stats信息 >> 产品正常产品3,0,0,0,0,0,0,0,0,0
测试获取产品4的stats信息 >> 正常产品4,0,0,4,4,0,0,0,0,0
测试获取产品104的stats信息 >> 产品正常产品4,0,0,0,0,0,0,0,0,0

*/

$product = new productTest('admin');

r($product->getStatsTest()) && p('0:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品1,0,5,9,9,0,0,0,0,0');     // 测试获取产品1的stats信息
r($product->getStatsTest()) && p('1:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('产品正常产品1,0,0,0,0,0,0,0,0,0'); // 测试获取产品101的stats信息
r($product->getStatsTest()) && p('2:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品2,0,2,4,4,0,0,0,0,0');     // 测试获取产品2的stats信息
r($product->getStatsTest()) && p('3:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('产品正常产品2,0,0,0,0,0,0,0,0,0'); // 测试获取产品102的stats信息
r($product->getStatsTest()) && p('4:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品3,0,0,4,4,0,0,0,0,0');     // 测试获取产品3的stats信息
r($product->getStatsTest()) && p('5:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('产品正常产品3,0,0,0,0,0,0,0,0,0'); // 测试获取产品103的stats信息
r($product->getStatsTest()) && p('6:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('正常产品4,0,0,4,4,0,0,0,0,0');     // 测试获取产品4的stats信息
r($product->getStatsTest()) && p('7:name,plans,releases,bugs,unResolved,closedBugs,fixedBugs,thisWeekBugs,assignToNull,progress') && e('产品正常产品4,0,0,0,0,0,0,0,0,0'); // 测试获取产品104的stats信息
