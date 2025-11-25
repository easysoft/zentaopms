#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getDataOfStoriesPerPlan();
timeout=0
cid=18519

- 按照计划分组，获取分组后的需求数量 @5
- 按照计划分组，获取各个计划的需求数量，查看1下的数据
 - 第1条的name属性 @1.0
 - 第1条的value属性 @4
- 按照计划分组，获取各个计划的需求数量，查看4下的数据
 - 第4条的name属性 @1.0
 - 第4条的value属性 @4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('productplan')->gen(10);
$story = zenData('story');
$story->version->range('1-4');
$story->gen(20);

su('admin');

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = "`id` < 20";

$data = $tester->story->getDataOfStoriesPerPlan();

r(count($data)) && p()               && e('5');     // 按照计划分组，获取分组后的需求数量
r($data)        && p('1:name,value') && e('1.0,4'); // 按照计划分组，获取各个计划的需求数量，查看1下的数据
r($data)        && p('4:name,value') && e('1.0,4'); // 按照计划分组，获取各个计划的需求数量，查看4下的数据