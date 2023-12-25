#!/usr/bin/env php
<?php

/**

title=productTao->getProductStats();
cid=0

- 获取系统内所有产品的统计信息
 - 第1条的draftStories属性 @0
 - 第1条的activeStories属性 @1
 - 第1条的changingStories属性 @1
 - 第1条的reviewingStories属性 @0
 - 第1条的closedStories属性 @0
 - 第1条的finishedStories属性 @0
 - 第1条的totalStories属性 @2
 - 第1条的unresolvedBugs属性 @3
 - 第1条的closedBugs属性 @0
 - 第1条的fixedBugs属性 @0
 - 第1条的totalBugs属性 @3
 - 第1条的plans属性 @0
 - 第1条的releases属性 @25
- 获取产品1-10的统计信息
 - 第2条的draftStories属性 @0
 - 第2条的activeStories属性 @1
 - 第2条的changingStories属性 @1
 - 第2条的reviewingStories属性 @0
 - 第2条的closedStories属性 @0
 - 第2条的finishedStories属性 @0
 - 第2条的totalStories属性 @2
 - 第2条的unresolvedBugs属性 @3
 - 第2条的closedBugs属性 @0
 - 第2条的fixedBugs属性 @0
 - 第2条的totalBugs属性 @3
 - 第2条的plans属性 @0
 - 第2条的releases属性 @10
- 获取产品11-20的统计信息
 - 第11条的draftStories属性 @0
 - 第11条的activeStories属性 @1
 - 第11条的changingStories属性 @1
 - 第11条的reviewingStories属性 @0
 - 第11条的closedStories属性 @0
 - 第11条的finishedStories属性 @0
 - 第11条的totalStories属性 @2
 - 第11条的unresolvedBugs属性 @3
 - 第11条的closedBugs属性 @0
 - 第11条的fixedBugs属性 @0
 - 第11条的totalBugs属性 @3
 - 第11条的plans属性 @0
 - 第11条的releases属性 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('bug')->gen(50);
zdTable('productplan')->gen(50);
zdTable('release')->gen(50);
zdTable('user')->gen(5);
su('admin');

$productIdList[] = array();
$productIdList[] = range(1, 10);
$productIdList[] = range(11, 20);

$productTester = new productTest();
r($productTester->getProductStatsTest($productIdList[0])) && p('1:draftStories,activeStories,changingStories,reviewingStories,closedStories,finishedStories,totalStories,unresolvedBugs,closedBugs,fixedBugs,totalBugs,plans,releases')  && e('0,1,1,0,0,0,2,3,0,0,3,0,25'); // 获取系统内所有产品的统计信息
r($productTester->getProductStatsTest($productIdList[1])) && p('2:draftStories,activeStories,changingStories,reviewingStories,closedStories,finishedStories,totalStories,unresolvedBugs,closedBugs,fixedBugs,totalBugs,plans,releases')  && e('0,1,1,0,0,0,2,3,0,0,3,0,10'); // 获取产品1-10的统计信息
r($productTester->getProductStatsTest($productIdList[2])) && p('11:draftStories,activeStories,changingStories,reviewingStories,closedStories,finishedStories,totalStories,unresolvedBugs,closedBugs,fixedBugs,totalBugs,plans,releases') && e('0,1,1,0,0,0,2,3,0,0,3,0,0');  // 获取产品11-20的统计信息
