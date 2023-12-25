#!/usr/bin/env php
<?php

/**

title=productTao->getStoryStats();
cid=0

- 测试系统中所有产品下的需求统计信息
 - 第1条的draft属性 @0
 - 第1条的active属性 @1
 - 第1条的changing属性 @1
 - 第1条的reviewing属性 @0
 - 第1条的finished属性 @0
 - 第1条的closed属性 @0
 - 第1条的total属性 @2
- 测试产品id为1-10的产品下的需求统计信息
 - 第2条的draft属性 @0
 - 第2条的active属性 @1
 - 第2条的changing属性 @1
 - 第2条的reviewing属性 @0
 - 第2条的finished属性 @0
 - 第2条的closed属性 @0
 - 第2条的total属性 @2
- 测试产品id为11-20的产品下的需求统计信息
 - 第11条的draft属性 @0
 - 第11条的active属性 @1
 - 第11条的changing属性 @1
 - 第11条的reviewing属性 @0
 - 第11条的finished属性 @0
 - 第11条的closed属性 @0
 - 第11条的total属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('user')->gen(5);
su('admin');

$productIdList[] = array();
$productIdList[] = range(1, 10);
$productIdList[] = range(10, 20);

$productTester = new productTest();
r($productTester->getStoryStatsTest($productIdList[0])) && p('1:draft,active,changing,reviewing,finished,closed,total')  && e('0,1,1,0,0,0,2'); // 测试系统中所有产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[1])) && p('2:draft,active,changing,reviewing,finished,closed,total')  && e('0,1,1,0,0,0,2'); // 测试产品id为1-10的产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[2])) && p('11:draft,active,changing,reviewing,finished,closed,total') && e('0,1,1,0,0,0,2'); // 测试产品id为11-20的产品下的需求统计信息
