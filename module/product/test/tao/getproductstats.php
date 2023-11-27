#!/usr/bin/env php
<?php
/**

title=productTao->getProductStats();
timeout=0
cid=1

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
