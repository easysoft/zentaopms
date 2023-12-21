#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('action')->gen(10);
zdTable('oauth')->gen(10);

/**

title=测试 webhookModel->getOpenIdList();
timeout=0
cid=1

- 测试传入正常数据的情况 @0
- 测试传入空的情况 @0
- 测试webhook传入空的情况 @0
- 测试action传入空的情况 @0

*/

$webhookTest = new webhookTest();

$webhook = array();
$webhook[0] = 2;
$webhook[1] = '';

$action = array();
$action[0] = 1;
$action[1] = 0;

r($webhookTest->getOpenIdListTest($webhook[0], $action[0])) && p() && e('0'); //测试传入正常数据的情况
r($webhookTest->getOpenIdListTest($webhook[1], $action[1])) && p() && e('0'); //测试传入空的情况
r($webhookTest->getOpenIdListTest($webhook[0], $action[1])) && p() && e('0'); //测试webhook传入空的情况
r($webhookTest->getOpenIdListTest($webhook[1], $action[0])) && p() && e('0'); //测试action传入空的情况