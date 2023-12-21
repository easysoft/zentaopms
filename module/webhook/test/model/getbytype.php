#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('webhook')->gen(10);

/**

title=测试 webhookModel->getByType();
timeout=0
cid=1

- 通过type为dinggroup查id属性id @1
- 通过type为dinguser查id属性id @2
- 通过type为wechatgroup查id属性id @3
- 通过type为wechatuser查id属性id @4
- 通过type为feishugroup查id属性id @5
- 通过type为feishuuser查id属性id @6
- 通过type为default查id属性id @7
- 传入空的情况属性id @0

*/

$webhook = new webhookTest();

$type = array();
$type[0] = 'dinggroup';
$type[1] = 'dinguser';
$type[2] = 'wechatgroup';
$type[3] = 'wechatuser';
$type[4] = 'feishugroup';
$type[5] = 'feishuuser';
$type[6] = 'default';
$type[7] = '';

$result1 = $webhook->getByTypeTest($type[0]);
$result2 = $webhook->getbyTypeTest($type[1]);
$result3 = $webhook->getbyTypeTest($type[2]);
$result4 = $webhook->getbyTypeTest($type[3]);
$result5 = $webhook->getbyTypeTest($type[4]);
$result6 = $webhook->getbyTypeTest($type[5]);
$result7 = $webhook->getbyTypeTest($type[6]);
$result8 = $webhook->getbyTypeTest($type[7]);

r($result1) && p('id') && e('1'); //通过type为dinggroup查id
r($result2) && p('id') && e('2'); //通过type为dinguser查id
r($result3) && p('id') && e('3'); //通过type为wechatgroup查id
r($result4) && p('id') && e('4'); //通过type为wechatuser查id
r($result5) && p('id') && e('5'); //通过type为feishugroup查id
r($result6) && p('id') && e('6'); //通过type为feishuuser查id
r($result7) && p('id') && e('7'); //通过type为default查id
r($result8) && p('id') && e('0'); //传入空的情况