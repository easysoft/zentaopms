#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getToAndCcList();
cid=1
pid=1

获取创建需求1时的通知人数量 >> 2
获取评审需求1时的通知人数量 >> 2
获取变更需求1时的通知人数量 >> 2
获取创建需求1时的通知人详情 >> admin
获取评审需求1时的通知人详情 >> admin
获取变更需求1时的通知人详情 >> po82,user92,

*/

global $tester;
$tester->loadModel('story');
$story1 = $tester->story->getByID(1);

$dataList1 = $tester->story->getToAndCcList($story1, 'opened');
$dataList2 = $tester->story->getToAndCcList($story1, 'reviewed');
$dataList3 = $tester->story->getToAndCcList($story1, 'changed');

r(count($dataList1)) && p() && e('2');            //获取创建需求1时的通知人数量
r(count($dataList2)) && p() && e('2');            //获取评审需求1时的通知人数量
r(count($dataList3)) && p() && e('2');            //获取变更需求1时的通知人数量
r($dataList1[0])     && p() && e('admin');        //获取创建需求1时的通知人详情
r($dataList2[0])     && p() && e('admin');        //获取评审需求1时的通知人详情
r($dataList3[1])     && p() && e('po82,user92,'); //获取变更需求1时的通知人详情