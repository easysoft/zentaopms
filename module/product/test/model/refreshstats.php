#!/usr/bin/env php
<?php
/**

title=测试productModel->getRoadmapOfReleases();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('productplan')->gen(50);
zdTable('release')->gen(50);
zdTable('bug')->gen(50);
zdTable('user')->gen(5);

$productTester = new productTest();
r($productTester->refreshStatsTest()) && p('1:draftStories,activeStories,changingStories,reviewingStories,finishedStories,closedStories,totalStories,unresolvedBugs,closedBugs,fixedBugs,totalBugs') && e('0,1,1,0,0,0,2,3,0,0,3'); // 刷新产品的统计信息
