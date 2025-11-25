#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getEstimateRounds();
timeout=0
cid=18529

- 执行storyTest模块的getEstimateRoundsTest方法，参数是999  @0
- 执行storyTest模块的getEstimateRoundsTest方法，参数是2 属性1 @第 1 轮估算
- 执行storyTest模块的getEstimateRoundsTest方法，参数是1
 - 属性1 @第 1 轮估算
 - 属性2 @第 2 轮估算
 - 属性3 @第 3 轮估算
- 执行storyTest模块的getEstimateRoundsTest方法，参数是-1  @0
- 执行storyTest模块的getEstimateRoundsTest方法  @0
- 执行storyTest模块的getEstimateRoundsTest方法，参数是4  @0
- 执行storyTest模块的getEstimateRoundsTest方法，参数是3
 - 属性1 @第 1 轮估算
 - 属性2 @第 2 轮估算
 - 属性3 @第 3 轮估算
 - 属性4 @第 4 轮估算
 - 属性5 @第 5 轮估算

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$storyEstimate = zenData('storyestimate');
$storyEstimate->story->range('1{3},2{1},3{5}');
$storyEstimate->round->range('1,2,3,1,1,2,3,4,5');
$storyEstimate->estimate->range('estimate1,estimate2,estimate3');
$storyEstimate->average->range('1.5,2.5,3.5');
$storyEstimate->openedBy->range('admin');
$storyEstimate->openedDate->range('`2023-01-01 10:00:00`');
$storyEstimate->gen(9);

su('admin');

$storyTest = new storyTest();

r($storyTest->getEstimateRoundsTest(999)) && p() && e('0');
r($storyTest->getEstimateRoundsTest(2)) && p('1') && e('第 1 轮估算');
r($storyTest->getEstimateRoundsTest(1)) && p('1,2,3') && e('第 1 轮估算,第 2 轮估算,第 3 轮估算');
r($storyTest->getEstimateRoundsTest(-1)) && p() && e('0');
r($storyTest->getEstimateRoundsTest(0)) && p() && e('0');
r($storyTest->getEstimateRoundsTest(4)) && p() && e('0');
r($storyTest->getEstimateRoundsTest(3)) && p('1,2,3,4,5') && e('第 1 轮估算,第 2 轮估算,第 3 轮估算,第 4 轮估算,第 5 轮估算');