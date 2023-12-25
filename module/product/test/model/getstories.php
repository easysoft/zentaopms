#!/usr/bin/env php
<?php

/**

title=productModel->getStories();
cid=0

- 测试获取产品1 browseType为unclosed的需求数量 @2
- 测试获取产品2 browseType为unclosed的需求数量 @2
- 测试获取产品3 browseType为unclosed的需求数量 @2
- 测试获取产品4 browseType为unclosed的需求数量 @2
- 测试获取产品5 browseType为unclosed的需求数量 @2
- 测试获取不存在的 browseType为unclosed的需求数量 @0
- 测试获取产品1 browseType为unplan的需求数量 @0
- 测试获取产品1 browseType为allstory的需求数量 @2
- 测试获取产品1 browseType为assignedtome的需求数量 @0
- 测试获取产品2 browseType为openedbyme的需求数量 @0
- 测试获取产品2 browseType为reviewedbyme的需求数量 @1
- 测试获取产品2 browseType为reviewbyme的需求数量 @0
- 测试获取产品2 browseType为closedbyme的需求数量 @0
- 测试获取产品3 browseType为draftstory的需求数量 @0
- 测试获取产品3 browseType为activestory的需求数量 @1
- 测试获取产品3 browseType为changedstory的需求数量 @0
- 测试获取产品3 browseType为willclose的需求数量 @1
- 测试获取产品4 browseType为closedstory的需求数量 @0
- 测试获取产品4 browseType为unclosed的需求数量 @2
- 测试获取产品4 browseType为unplan的需求数量 @0
- 测试获取产品5 browseType为allstory的需求数量 @2
- 测试获取产品5 browseType为assignedtome的需求数量 @0
- 测试获取产品5 browseType为openedbyme的需求数量 @0
- 测试获取不存在产品 browseType为reviewedbyme的需求数量 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('story')->gen(100);

$productIDList = array(1, 2, 3, 4, 5, 1000001);
$branch        = 0;
$browseType    = array('unclosed', 'unplan', 'allstory', 'assignedtome', 'openedbyme', 'reviewedbyme', 'reviewbyme', 'closedbyme', 'draftstory', 'activestory', 'changedstory', 'willclose', 'closedstory');
$queryID       = 0;
$moduleID      = 0;

$product = new productTest('admin');

r($product->getStoriesTest($productIDList[0], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品1 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[1], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品2 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[2], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品3 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[3], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品4 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[4], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品5 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[5], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('0');  // 测试获取不存在的 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[0], $branch, $browseType[1], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品1 browseType为unplan的需求数量
r($product->getStoriesTest($productIDList[0], $branch, $browseType[2], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品1 browseType为allstory的需求数量
r($product->getStoriesTest($productIDList[0], $branch, $browseType[3], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品1 browseType为assignedtome的需求数量
r($product->getStoriesTest($productIDList[1], $branch, $browseType[4], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品2 browseType为openedbyme的需求数量
r($product->getStoriesTest($productIDList[1], $branch, $browseType[5], $queryID, $moduleID))  && p() && e('1');  // 测试获取产品2 browseType为reviewedbyme的需求数量
r($product->getStoriesTest($productIDList[1], $branch, $browseType[6], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品2 browseType为reviewbyme的需求数量
r($product->getStoriesTest($productIDList[1], $branch, $browseType[7], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品2 browseType为closedbyme的需求数量
r($product->getStoriesTest($productIDList[2], $branch, $browseType[8], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品3 browseType为draftstory的需求数量
r($product->getStoriesTest($productIDList[2], $branch, $browseType[9], $queryID, $moduleID))  && p() && e('1');  // 测试获取产品3 browseType为activestory的需求数量
r($product->getStoriesTest($productIDList[2], $branch, $browseType[10], $queryID, $moduleID)) && p() && e('0');  // 测试获取产品3 browseType为changedstory的需求数量
r($product->getStoriesTest($productIDList[2], $branch, $browseType[11], $queryID, $moduleID)) && p() && e('1');  // 测试获取产品3 browseType为willclose的需求数量
r($product->getStoriesTest($productIDList[3], $branch, $browseType[12], $queryID, $moduleID)) && p() && e('0');  // 测试获取产品4 browseType为closedstory的需求数量
r($product->getStoriesTest($productIDList[3], $branch, $browseType[0], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品4 browseType为unclosed的需求数量
r($product->getStoriesTest($productIDList[3], $branch, $browseType[1], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品4 browseType为unplan的需求数量
r($product->getStoriesTest($productIDList[4], $branch, $browseType[2], $queryID, $moduleID))  && p() && e('2');  // 测试获取产品5 browseType为allstory的需求数量
r($product->getStoriesTest($productIDList[4], $branch, $browseType[3], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品5 browseType为assignedtome的需求数量
r($product->getStoriesTest($productIDList[4], $branch, $browseType[4], $queryID, $moduleID))  && p() && e('0');  // 测试获取产品5 browseType为openedbyme的需求数量
r($product->getStoriesTest($productIDList[5], $branch, $browseType[5], $queryID, $moduleID))  && p() && e('0');  // 测试获取不存在产品 browseType为reviewedbyme的需求数量
