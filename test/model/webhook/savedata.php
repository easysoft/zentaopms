#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->saveData();
cid=1
pid=1

测试正常传入的情况不传任何参数都会报错，所以这里没写其他传入情况 >> 1

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'productplan';
$objectType[1] = '';

$objectID   = array();
$objectID[0]   = '3';
$objectID[1]   = '';

$actionType = array();
$actionType[0] = 'opened';
$actionType[1] = '';

$webhookID  = array();
$webhookID[0]  = '3';
$webhookID[1]  = '';

$actionID   = array();
$actionID[0]   = '3';
$actionID[1]   = '';

$actor      = array();
$actor[0]      = 'test18';
$actor[1]      = '';

$result1 = $webhook->saveDataTest($objectType[0], $objectID[0], $actionType[0], $webhookID[0], $actionID[0], $actor[0]);

r($webhook->saveDataTest($result1)) && p() && e('1'); //测试正常传入的情况不传任何参数都会报错，所以这里没写其他传入情况