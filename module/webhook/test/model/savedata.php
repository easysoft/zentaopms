#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('productplan')->gen(5);
zenData('notify')->gen(0);

/**

title=测试 webhookModel->saveData();
timeout=0
cid=19703

- $objectType[0], $objectID[0], $actionType[0], $webhookID[0], $actionID[0], $actor[0] @1
- $objectType[1], $objectID[1], $actionType[1], $webhookID[1], $actionID[1], $actor[1] @1
- $objectType[0], $objectID[2], $actionType[0], $webhookID[0], $actionID[0], $actor[0] @1
- $objectType[0], $objectID[2], $actionType[2], $webhookID[0], $actionID[0], $actor[0] @1
- $objectType[0], $objectID[0], $actionType[2], $webhookID[0], $actionID[0], $actor[0] @1

*/

$webhook = new webhookModelTest();

$objectType    = array();
$objectType[0] = 'productplan';
$objectType[1] = '';

$objectID    = array();
$objectID[0] = 3;
$objectID[1] = 0;
$objectID[2] = 1;

$actionType = array();
$actionType[0] = 'opened';
$actionType[1] = '';
$actionType[2] = 'edited';

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
$result3 = $webhook->saveDataTest($objectType[0], $objectID[2], $actionType[0], $webhookID[0], $actionID[0], $actor[0]);
$result4 = $webhook->saveDataTest($objectType[0], $objectID[2], $actionType[2], $webhookID[0], $actionID[0], $actor[0]);
$result5 = $webhook->saveDataTest($objectType[0], $objectID[0], $actionType[2], $webhookID[0], $actionID[0], $actor[0]);

r($result1) && p() && e('1'); // $objectType[0], $objectID[0], $actionType[0], $webhookID[0], $actionID[0], $actor[0]
r($result2) && p() && e('1'); // $objectType[1], $objectID[1], $actionType[1], $webhookID[1], $actionID[1], $actor[1]
r($result3) && p() && e('1'); // $objectType[0], $objectID[2], $actionType[0], $webhookID[0], $actionID[0], $actor[0]
r($result4) && p() && e('1'); // $objectType[0], $objectID[2], $actionType[2], $webhookID[0], $actionID[0], $actor[0]
r($result5) && p() && e('1'); // $objectType[0], $objectID[0], $actionType[2], $webhookID[0], $actionID[0], $actor[0]