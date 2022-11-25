#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->statisticData();
cid=1
pid=1

测试获取统计数据集中产品2的信息 >> 项目集1,2,2,2,2,4,0,0,2
测试获取统计数据集中产品3的信息 >> 项目集2,2,2,2,2,4,0,0,0
测试获取统计数据集中产品4的信息 >> 项目集3,2,2,2,2,4,0,0,0
测试获取统计数据集中产品5的信息 >> 项目集4,2,2,2,2,4,0,0,0
测试获取统计数据集中产品6的信息 >> 项目集5,2,0,1,1,4,0,0,0
测试获取统计数据集中产品7的信息 >> 项目集6,2,0,1,1,4,0,0,0
测试获取统计数据集中产品8的信息 >> 项目集7,2,0,1,1,4,0,0,0
测试获取统计数据集中产品9的信息 >> 项目集8,2,0,1,1,4,0,0,0
测试获取统计数据集中产品10的信息 >> 项目集9,2,0,1,1,4,0,3,0

*/

$product = new productTest('admin');

$productStats = $product->getStatsTest();

r($product->statisticDataTest($productStats[2]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集1,2,2,2,2,4,0,0,2'); // 测试获取统计数据集中产品2的信息
r($product->statisticDataTest($productStats[4]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集2,2,2,2,2,4,0,0,0'); // 测试获取统计数据集中产品3的信息
r($product->statisticDataTest($productStats[6]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集3,2,2,2,2,4,0,0,0'); // 测试获取统计数据集中产品4的信息
r($product->statisticDataTest($productStats[8]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集4,2,2,2,2,4,0,0,0'); // 测试获取统计数据集中产品5的信息
r($product->statisticDataTest($productStats[10]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集5,2,0,1,1,4,0,0,0'); // 测试获取统计数据集中产品6的信息
r($product->statisticDataTest($productStats[12]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集6,2,0,1,1,4,0,0,0'); // 测试获取统计数据集中产品7的信息
r($product->statisticDataTest($productStats[14]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集7,2,0,1,1,4,0,0,0'); // 测试获取统计数据集中产品8的信息
r($product->statisticDataTest($productStats[16]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集8,2,0,1,1,4,0,0,0'); // 测试获取统计数据集中产品9的信息
r($product->statisticDataTest($productStats[18]))  && p('programName,totalRequirements,activeRequirements,totalStories,activeStories,unResolvedBugs,closedBugs,plans,releases') && e('项目集9,2,0,1,1,4,0,3,0'); // 测试获取统计数据集中产品10的信息
