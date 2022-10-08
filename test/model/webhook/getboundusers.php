#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getBoundUsers();
cid=1
pid=1

统计匹配数量 >> 1
取出其中一个用户 >> 2
传入不存在的情况 >> 0
传入空时 >> 0

*/

$webhook = new webhookTest();

$ID = array();
$ID[0] = 1;
$ID[1] = 1111;
$ID[2] = '';

$user = array();
$user[0] = array('user3', 'user4');
$user[1] = 'user3, user1111';
$user[2] = '';

r(count($webhook->getBoundUsersTest($ID[0], $user[0]))) && p()        && e('1'); //统计匹配数量
r($webhook->getBoundUsersTest($ID[0], $user[0]))        && p('user4') && e('2'); //取出其中一个用户
r(count($webhook->getBoundUsersTest($ID[1], $user[1]))) && p()        && e('0'); //传入不存在的情况
r(count($webhook->getBoundUsersTest($ID[2], $user[2]))) && p()        && e('0'); //传入空时