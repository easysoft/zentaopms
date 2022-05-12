#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/webhook.class.php';
su('admin');

/**

title=测试 webhookModel->fetchHook();
cid=1
pid=1

传入正确参数 >> Could not resolve host: oapi.dinggroup.com; Unknown error
不传入objectType >> Could not resolve host: oapi.dinggroup.com; Unknown error
不传入objectID >> Could not resolve host: oapi.dinggroup.com; Unknown error
不传入actionType >> Could not resolve host: oapi.dinggroup.com; Unknown error
不传入actionID >> Could not resolve host: oapi.dinggroup.com; Unknown error

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'product';
$objectType[1] = '';

$objectID = array();
$objectID[0] = '1';
$objectID[1] = '';

$actionType = array();
$actionType[0] = 'created';
$actionType[1] = '';

$actionID = array();
$actionID[0] = '4';
$actionID[1] = '';

$result1 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[0], $actionID[0]);
$result2 = $webhook->fetchHookTest($objectType[1], $objectID[0], $actionType[0], $actionID[0]);
$result3 = $webhook->fetchHookTest($objectType[0], $objectID[1], $actionType[0], $actionID[0]);
$result4 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[1], $actionID[0]);
$result5 = $webhook->fetchHookTest($objectType[0], $objectID[0], $actionType[0], $actionID[1]);

r($webhook->fetchHookTest($result1)) && p() && e('Could not resolve host: oapi.dinggroup.com; Unknown error'); //传入正确参数
r($webhook->fetchHookTest($result2)) && p() && e('Could not resolve host: oapi.dinggroup.com; Unknown error'); //不传入objectType
r($webhook->fetchHookTest($result3)) && p() && e('Could not resolve host: oapi.dinggroup.com; Unknown error'); //不传入objectID
r($webhook->fetchHookTest($result4)) && p() && e('Could not resolve host: oapi.dinggroup.com; Unknown error'); //不传入actionType
r($webhook->fetchHookTest($result5)) && p() && e('Could not resolve host: oapi.dinggroup.com; Unknown error'); //不传入actionID