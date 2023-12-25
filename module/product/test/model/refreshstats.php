#!/usr/bin/env php
<?php

/**

title=测试productModel->getRoadmapOfReleases();
cid=0

- 刷新产品的统计信息
 - 第1条的draftStories属性 @0
 - 第1条的activeStories属性 @1
 - 第1条的changingStories属性 @1
 - 第1条的reviewingStories属性 @0
 - 第1条的finishedStories属性 @0
 - 第1条的closedStories属性 @0
 - 第1条的totalStories属性 @2
 - 第1条的unresolvedBugs属性 @3
 - 第1条的closedBugs属性 @0
 - 第1条的fixedBugs属性 @0
 - 第1条的totalBugs属性 @3

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
