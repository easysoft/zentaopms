#!/usr/bin/env php
<?php

/**

title=productTao->getBugStats();
cid=0

- 测试系统中所有产品下的Bug统计信息
 - 第1条的unresolved属性 @3
 - 第1条的fixed属性 @0
 - 第1条的closed属性 @0
 - 第1条的total属性 @3
- 测试系统中产品1-10下的Bug统计信息
 - 第2条的unresolved属性 @3
 - 第2条的fixed属性 @0
 - 第2条的closed属性 @0
 - 第2条的total属性 @3
- 测试系统中产品11-20下的Bug统计信息
 - 第11条的unresolved属性 @3
 - 第11条的fixed属性 @0
 - 第11条的closed属性 @0
 - 第11条的total属性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('bug')->gen(50);
zdTable('user')->gen(5);
su('admin');

$productIdList[] = array();
$productIdList[] = range(1, 10);
$productIdList[] = range(10, 20);

$productTester = new productTest();
r($productTester->getBugStatsTest($productIdList[0])) && p('1:unresolved,fixed,closed,total')  && e('3,0,0,3'); // 测试系统中所有产品下的Bug统计信息
r($productTester->getBugStatsTest($productIdList[1])) && p('2:unresolved,fixed,closed,total')  && e('3,0,0,3'); // 测试系统中产品1-10下的Bug统计信息
r($productTester->getBugStatsTest($productIdList[2])) && p('11:unresolved,fixed,closed,total') && e('3,0,0,3'); // 测试系统中产品11-20下的Bug统计信息
