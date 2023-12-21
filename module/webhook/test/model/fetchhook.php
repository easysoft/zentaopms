#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('webhook')->gen(10);
zdTable('action')->gen(10);

/**

title=测试 webhookModel->fetchHook();
timeout=0
cid=1

- 传入正确参数 @0
- 不传入objectType @0
- 不传入objectID @0
- 不传入actionType @0
- 不传入actionID @0

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'product';
$objectType[1] = '';

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 0;

$actionType = array();
$actionType[0] = 'common';
$actionType[1] = '';

$actionID = array();
$actionID[0] = '4';
$actionID[1] = 0;

$result1 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[0], $actionID[0]);
$result2 = $webhook->fetchHookTest($objectType[1], $objectID[0], $actionType[0], $actionID[0]);
$result3 = $webhook->fetchHookTest($objectType[0], $objectID[1], $actionType[0], $actionID[0]);
$result4 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[1], $actionID[0]);
$result5 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[0], $actionID[1]);

r($result1) && p() && e('0'); //传入正确参数
r($result2) && p() && e('0'); //不传入objectType
r($result3) && p() && e('0'); //不传入objectID
r($result4) && p() && e('0'); //不传入actionType
r($result5) && p() && e('0'); //不传入actionID