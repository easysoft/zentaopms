#!/usr/bin/env php
<?php

/**

title=测试productModel->getStatsStoriesAndRequirements();
cid=0

- 测试不传产品ID列表获取产品下的需求和用户需求第1条的changing属性 @0
- 测试产品ID列表获取产品下的需求和用户需求第1条的changing属性 @1
- 测试不存在的产品ID列表获取产品下的需求和用户需求第11条的changing属性 @0
- 测试不传产品ID列表获取产品下的用户需求 @0
- 测试产品ID列表获取产品下的用户需求第1条的active属性 @4
- 测试不存在的产品ID列表获取产品下的用户需求第11条的1属性 @draft

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('product')->config('product')->gen(10);
zdTable('story')->config('story')->gen(30);
zdTable('user')->gen(5);
su('admin');

$productIdList[0] = array();
$productIdList[1] = range(1, 10);
$productIdList[2] = range(11, 20);
$storyTypeList    = array('story', 'requirement');

global $tester;
$tester->loadModel('product');
r($tester->product->getStatsStoriesAndRequirements($productIdList[0], $storyTypeList[0])[0]) && p('1:changing')  && e('0');     // 测试不传产品ID列表获取产品下的需求和用户需求
r($tester->product->getStatsStoriesAndRequirements($productIdList[1], $storyTypeList[0])[0]) && p('1:changing')  && e('1');     // 测试产品ID列表获取产品下的需求和用户需求
r($tester->product->getStatsStoriesAndRequirements($productIdList[2], $storyTypeList[0])[0]) && p('11:changing') && e('0');     // 测试不存在的产品ID列表获取产品下的需求和用户需求
r($tester->product->getStatsStoriesAndRequirements($productIdList[0], $storyTypeList[1])[0]) && p()              && e('0');     // 测试不传产品ID列表获取产品下的用户需求
r($tester->product->getStatsStoriesAndRequirements($productIdList[1], $storyTypeList[1])[0]) && p('1:active')    && e('4');     // 测试产品ID列表获取产品下的用户需求
r($tester->product->getStatsStoriesAndRequirements($productIdList[2], $storyTypeList[1])[0]) && p('11:1')        && e('draft'); // 测试不存在的产品ID列表获取产品下的用户需求
