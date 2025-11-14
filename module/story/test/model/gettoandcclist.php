#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getToAndCcList();
timeout=0
cid=18564

- 获取创建需求1时的通知人数量 @2
- 获取评审需求1时的通知人数量 @2
- 获取变更需求1时的通知人数量 @2
- 获取创建需求1时的通知人详情 @admin
- 获取评审需求1时的通知人详情 @admin
- 获取变更需求1时的通知人详情 @,

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(10);

global $tester;
$tester->loadModel('story');
$story1 = $tester->story->getByID(1);

$dataList1 = $tester->story->getToAndCcList($story1, 'opened');
$dataList2 = $tester->story->getToAndCcList($story1, 'reviewed');
$dataList3 = $tester->story->getToAndCcList($story1, 'changed');

r(count($dataList1)) && p() && e('2');     //获取创建需求1时的通知人数量
r(count($dataList2)) && p() && e('2');     //获取评审需求1时的通知人数量
r(count($dataList3)) && p() && e('2');     //获取变更需求1时的通知人数量
r($dataList1[0])     && p() && e('admin'); //获取创建需求1时的通知人详情
r($dataList2[0])     && p() && e('admin'); //获取评审需求1时的通知人详情
r($dataList3[1])     && p() && e(',');     //获取变更需求1时的通知人详情