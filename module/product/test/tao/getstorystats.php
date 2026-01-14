#!/usr/bin/env php
<?php

/**

title=productTao->getStoryStats();
timeout=0
cid=17558

- 测试系统中所有产品下的需求统计信息
 - 第story条的draft属性 @0
 - 第story条的active属性 @1
 - 第story条的changing属性 @1
 - 第story条的reviewing属性 @0
 - 第story条的finished属性 @0
 - 第story条的closed属性 @0
 - 第story条的total属性 @2
- 测试产品id为1-10的产品下的需求统计信息
 - 第story条的draft属性 @0
 - 第story条的active属性 @1
 - 第story条的changing属性 @1
 - 第story条的reviewing属性 @0
 - 第story条的finished属性 @0
 - 第story条的closed属性 @0
 - 第story条的total属性 @2
- 测试产品id为11-20的产品下的需求统计信息
 - 第story条的draft属性 @0
 - 第story条的active属性 @1
 - 第story条的changing属性 @1
 - 第story条的reviewing属性 @0
 - 第story条的finished属性 @0
 - 第story条的closed属性 @0
 - 第story条的total属性 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('product')->gen(50);
zenData('story')->gen(50);
zenData('user')->gen(5);
su('admin');

$productIdList[] = array();
$productIdList[] = range(1, 10);
$productIdList[] = range(10, 20);

$productTester = new productTaoTest();
r($productTester->getStoryStatsTest($productIdList[0])[1])  && p('story:draft,active,changing,reviewing,finished,closed,total') && e('0,1,1,0,0,0,2'); // 测试系统中所有产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[1])[2])  && p('story:draft,active,changing,reviewing,finished,closed,total') && e('0,1,1,0,0,0,2'); // 测试产品id为1-10的产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[2])[11]) && p('story:draft,active,changing,reviewing,finished,closed,total') && e('0,1,1,0,0,0,2'); // 测试产品id为11-20的产品下的需求统计信息