#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->send();
timeout=0
cid=1

- 传入正常数据的情况，返回true @1
- 传入正常数据2 @1
- 传入objectType为空时 @1
- 传入objectID为空时 @1
- 传入actionType为空时 @1
- 传入actionID为空时 @1
- 传入actor为空时 @1
- 传入参数不符的情况 @1

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'product';
$objectType[1] = 'story';
$objectType[2] = 'productplan';
$objectType[3] = 'release';
$objectType[4] = 'project';
$objectType[5] = '';

$objectID   = array();
$objectID[0]   = 1;
$objectID[1]   = 2;
$objectID[2]   = 3;
$objectID[3]   = 4;
$objectID[4]   = 0;

$actionType = array();
$actionType[0] = 'common';
$actionType[1] = 'extra';
$actionType[2] = 'opented';
$actionType[3] = 'created';
$actionType[4] = '';

$actionID   = array();
$actionID[0]   = 1;
$actionID[1]   = 2;
$actionID[2]   = 3;
$actionID[3]   = 4;
$actionID[4]   = 0;

$actor      = array();
$actor[0]      = 'admin';
$actor[1]      = 'dev17';
$actor[2]      = 'test18';
$actor[3]      = 'dev18';
$actor[4]      = '';

$result1 = $webhook->sendTest($objectType[0], $objectID[0], $actionType[0], $actionID[0], $actor[0]);
$result2 = $webhook->sendTest($objectType[1], $objectID[1], $actionType[1], $actionID[1], $actor[1]);
$result3 = $webhook->sendTest($objectType[5], $objectID[2], $actionType[2], $actionID[2], $actor[2]);
$result4 = $webhook->sendTest($objectType[0], $objectID[4], $actionType[0], $actionID[0], $actor[0]);
$result5 = $webhook->sendTest($objectType[0], $objectID[0], $actionType[4], $actionID[0], $actor[0]);
$result6 = $webhook->sendTest($objectType[0], $objectID[0], $actionType[0], $actionID[4], $actor[0]);
$result7 = $webhook->sendTest($objectType[0], $objectID[3], $actionType[0], $actionID[0], $actor[0]);
$result8 = $webhook->sendTest($objectType[1], $objectID[1], $actionType[0], $actionID[1], $actor[3]);

r($result1) && p() && e('1'); //传入正常数据的情况，返回true
r($result2) && p() && e('1'); //传入正常数据2
r($result3) && p() && e('1'); //传入objectType为空时
r($result4) && p() && e('1'); //传入objectID为空时
r($result5) && p() && e('1'); //传入actionType为空时
r($result6) && p() && e('1'); //传入actionID为空时
r($result7) && p() && e('1'); //传入actor为空时
r($result8) && p() && e('1'); //传入参数不符的情况