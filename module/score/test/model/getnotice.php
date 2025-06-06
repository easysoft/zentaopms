#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->getNotice();
cid=1

- 获取admin用户昨日积分总数 @昨天增加了积分：<strong>100</strong><br/>总积分：<strong>100</strong>
- 获取user1用户昨日积分总数 @昨天增加了积分：<strong>100</strong><br/>总积分：<strong>100</strong>
- 获取user2用户昨日积分总数 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/score.unittest.class.php';

$time = date('Y-m-d 12:00:00', strtotime('-1 day'));
zenData('user')->gen(5);
$scoreTable = zenData('score')->loadYaml('score');
$scoreTable->account->range('user1{1},user2{2},user3{3},user4{4},admin{5}');
$scoreTable->time->range("`{$time}`");
$scoreTable->score->range('10');
$scoreTable->gen(100);

$accounts = array('admin', 'user1', 'user2', 'user4', 'user5');

$scoreTester = new scoreTest();
r($scoreTester->getNoticeTest($accounts[0])) && p() && e('昨天增加了积分：<strong>300</strong><br/>总积分：<strong>100</strong>'); // 获取admin用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[1])) && p() && e('昨天增加了积分：<strong>70</strong><br/>总积分：<strong>100</strong>');  // 获取user1用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[2])) && p() && e('昨天增加了积分：<strong>140</strong><br/>总积分：<strong>100</strong>'); // 获取user2用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[3])) && p() && e('昨天增加了积分：<strong>280</strong><br/>总积分：<strong>100</strong>'); // 获取user3用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[4])) && p() && e('昨天增加了积分：<strong>280</strong><br/>总积分：<strong>100</strong>'); // 获取user4用户昨日积分总数
