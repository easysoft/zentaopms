#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('notify')->gen(0);

/**

title=测试 webhookModel->saveData();
timeout=0
cid=1

- 正常传入的情况 @1
- 正常传入的情况 @1

*/

$webhook = new webhookTest();

$objectType    = array();
$objectType[0] = 'productplan';
$objectType[1] = '';

$objectID    = array();
$objectID[0] = 3;
$objectID[1] = 0;

$actionType = array();
$actionType[0] = 'opened';
$actionType[1] = '';

$webhookID    = array();
$webhookID[0] = 3;
$webhookID[1] = 0;

$actionID    = array();
$actionID[0] = 3;
$actionID[1] = 0;

$actor     = array();
$actor[0]  = 'test18';
$actor[1]  = '';

$result1 = $webhook->saveDataTest($objectType[0], $objectID[0], $actionType[0], $webhookID[0], $actionID[0], $actor[0]);
$result2 = $webhook->saveDataTest($objectType[1], $objectID[1], $actionType[1], $webhookID[1], $actionID[1], $actor[1]);

r($result1) && p() && e('1'); // 正常传入的情况
r($result2) && p() && e('1'); // 正常传入的情况