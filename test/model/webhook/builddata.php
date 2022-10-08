#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->buildData();
cid=1
pid=1

正常传入的情况 >> "msgtype":"markdown","markdown"
不传objectType >> 0
不传actionType >> 0
不传actionID >> admin\u521b\u5efa\u4ea7\u54c1

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'release';
$objectType[1] = '';

$objectID = array();
$objectID[0] = 1;
$objectID[1] = '';

$actionType = array();
$actionType[0] = 'created';
$actionType[1] = '';

$actionID = array();
$actionID[0] = 1;
$actionID[1] = '';

$result1 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[0], $actionID[0]);
$result2 = $webhook->buildDataTest($objectType[1], $objectID[0], $actionType[0], $actionID[0]);
$result4 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[1], $actionID[0]);
$result5 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[0], $actionID[1]);

r($result1) && p() && e('"msgtype":"markdown","markdown"'); //正常传入的情况
r($result2) && p() && e('0');                               //不传objectType
r($result4) && p() && e('0');                               //不传actionType
r($result5) && p() && e('admin\u521b\u5efa\u4ea7\u54c1');   //不传actionID