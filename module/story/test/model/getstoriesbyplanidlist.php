#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getStoriesByPlanIdList();
cid=18557

- 传入计划ID列表，获取其下面的需求数量 @3
- 传入计划ID列表，获取其下面的需求数量 @3
- 传入计划ID列表，获取其下面的需求数量 @10
- 传入计划ID列表，获取其下面的需求数量 @5
- 传入计划ID列表，查看其下面的需求详情
 - 第1条的title属性 @用户需求1
 - 第1条的type属性 @requirement
 - 第1条的status属性 @draft
- 传入计划ID列表，获取其下面的需求数量 @5
- 传入计划ID列表，查看其下面的需求详情
 - 第4条的title属性 @软件需求4
 - 第4条的type属性 @story
 - 第4条的status属性 @changing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zenData('story');
$story->product->range('1');
$story->gen(50);

$planstory = zenData('planstory');
$planstory->plan->range('1-10');
$planstory->gen(50);

global $tester;
$tester->loadModel('story');

$planIdList1 = array(1, 2, 3);
$planIdList2 = array(4, 5, 6);
$planIdList3 = array();
$stories1    = $tester->story->getStoriesByPlanIdList($planIdList1);
$stories2    = $tester->story->getStoriesByPlanIdList($planIdList2);
$stories3    = $tester->story->getStoriesByPlanIdList($planIdList3);

r(count($stories1))    && p()                      && e('3');                           //传入计划ID列表，获取其下面的需求数量
r(count($stories2))    && p()                      && e('3');                           //传入计划ID列表，获取其下面的需求数量
r(count($stories3))    && p()                      && e('10');                          //传入计划ID列表，获取其下面的需求数量
r(count($stories1[1])) && p()                      && e('5');                           //传入计划ID列表，获取其下面的需求数量
r($stories1[1])        && p('1:title,type,status') && e('用户需求1,requirement,draft'); //传入计划ID列表，查看其下面的需求详情
r(count($stories2[4])) && p()                      && e('5');                           //传入计划ID列表，获取其下面的需求数量
r($stories2[4])        && p('4:title,type,status') && e('软件需求4,story,changing');    //传入计划ID列表，查看其下面的需求详情
