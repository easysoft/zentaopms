#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getLogList();
cid=1
pid=1

统计ID为1的日志数量 >> 0
统计ID为2的日志数量 >> 0
统计ID不存在时的数量 >> 0
取出ID为1的其中一个匹配操作内容 >> 0

*/

$webhook = new webhookTest();

$ID = array();
$ID[0] = 1;
$ID[1] = 3;
$ID[2] = 1111;

$result1 = $webhook->getLogListTest($ID[0], '', '');
$result2 = $webhook->getLogListTest($ID[1], '', '');
$result3 = $webhook->getLogListTest($ID[2], '', '');

//a($result1);die;
r(count($result1)) && p()           && e('0');                          //统计ID为1的日志数量
r(count($result2)) && p()           && e('0');                          //统计ID为2的日志数量
r(count($result3)) && p()           && e('0');                          //统计ID不存在时的数量
r($result1)        && p('4:action') && e('0');                          //取出ID为1的其中一个匹配操作内容