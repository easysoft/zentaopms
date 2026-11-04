#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getToAndCcList();
timeout=0
cid=18564

- 获取创建需求1时的通知人数量 @2
- 获取评审需求1时的通知人数量 @2
- 获取变更需求1时的通知人数量 @2
- 获取创建需求1时的通知人详情 @admin,user1
- 获取评审需求1时的通知人详情 @admin
- 获取变更需求1时的抄送人详情 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$zd_story = zenData('story');
$zd_story->assignedTo->range('admin,user{9}');
$zd_story->mailto->range('[]{10}');
$zd_story->type->range('story');
$zd_story->version->range('1');
$zd_story->status->range('active');
$zd_story->deleted->range('0');
$zd_story->gen(10);

zenData('storyreview')->gen(0);
$zd_review = zenData('storyreview');
$zd_review->story->range('1');
$zd_review->version->range('1');
$zd_review->reviewer->range('user1');
$zd_review->result->range('');
$zd_review->gen(1);

zenData('projectstory')->gen(0);
zenData('team')->gen(0);
zenData('task')->gen(0);

global $tester;
$tester->loadModel('story');
$story1 = $tester->story->getByID(1);

$dataList1 = $tester->story->getToAndCcList($story1, 'opened');
$dataList2 = $tester->story->getToAndCcList($story1, 'reviewed');
$dataList3 = $tester->story->getToAndCcList($story1, 'changed');

r(count($dataList1)) && p()     && e('2');             //获取创建需求1时的通知人数量
r(count($dataList2)) && p()     && e('2');             //获取评审需求1时的通知人数量
r(count($dataList3)) && p()     && e('2');             //获取变更需求1时的通知人数量
r($dataList1[0])     && p()     && e('admin,user1');   //获取创建需求1时的通知人详情
r($dataList2[0])     && p()     && e('admin');         //获取评审需求1时的通知人详情
r($dataList3[1])     && p()     && e('0');             //获取变更需求1时的抄送人详情
