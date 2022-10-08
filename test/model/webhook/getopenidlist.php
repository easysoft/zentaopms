#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->getOpenIdList();
cid=1
pid=1



*/

$webhook = new webhookTest();

$webhook = array();
$webhook[0] = 2;
$webhook[1] = '';

$action = array();
$action[0] = 1;
$action[1] = '';

r($webhook->getOpenIdListTest($webhook[0], $action[0])) && p() && e(''); //测试传入正常数据的情况
r($webhook->getOpenIdListTest($webhook[1], $action[1])) && p() && e(''); //测试传入空的情况
r($webhook->getOpenIdListTest($webhook[0], $action[1])) && p() && e(''); //测试webhook传入空的情况
r($webhook->getOpenIdListTest($webhook[1], $action[0])) && p() && e(''); //测试action传入空的情况