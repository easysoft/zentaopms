#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getStoriesByPlanIdList();
cid=1
pid=1

传入计划ID列表，获取其下面的需求数量 >> 20
传入计划ID列表，查看其下面的需求详情 >> 软件需求388,story,draft
传入计划ID列表，获取其下面的需求数量 >> 20
传入计划ID列表，查看其下面的需求详情 >> 软件需求392,story,draft
传入空的计划ID列表，获取系统中所有需求数量 >> 424

*/

global $tester;
$tester->loadModel('story');

$planIdList1 = array(1, 2, 3);
$planIdList2 = array(4, 5, 6);
$planIdList3 = array();
$stories1    = $tester->story->getStoriesByPlanIdList($planIdList1);
$stories2    = $tester->story->getStoriesByPlanIdList($planIdList2);
$stories3    = $tester->story->getStoriesByPlanIdList($planIdList3);

r(count($stories1[1]))    && p()                        && e('20');                         //传入计划ID列表，获取其下面的需求数量
r($stories1[1])           && p('388:title,type,status') && e('软件需求388,story,draft');    //传入计划ID列表，查看其下面的需求详情
r(count($stories2[4]))    && p()                        && e('20');                         //传入计划ID列表，获取其下面的需求数量
r($stories2[4])           && p('392:title,type,status') && e('软件需求392,story,draft');    //传入计划ID列表，查看其下面的需求详情
r(count($stories3, true)) && p()                        && e('424');                        //传入空的计划ID列表，获取系统中所有需求数量