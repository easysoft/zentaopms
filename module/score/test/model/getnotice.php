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
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
$time = date('Y-m-d 12:00:00', strtotime('-1 day'));
$scoreTable = zdTable('score')->config('score');
$scoreTable->time->range("`{$time}`");
$scoreTable->score->range('10');
$scoreTable->gen(20);

$accounts = array('admin', 'user1', 'user2');

$scoreTester = new scoreTest();
r($scoreTester->getNoticeTest($accounts[0])) && p() && e('昨天增加了积分：<strong>100</strong><br/>总积分：<strong>100</strong>'); // 获取admin用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[1])) && p() && e('昨天增加了积分：<strong>100</strong><br/>总积分：<strong>100</strong>'); // 获取user1用户昨日积分总数
r($scoreTester->getNoticeTest($accounts[2])) && p() && e('0');                                                                     // 获取user2用户昨日积分总数
