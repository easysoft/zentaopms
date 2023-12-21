#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/webhook.class.php';
su('admin');

zdTable('action')->gen(10);
zdTable('webhook')->gen(10);

/**

title=测试 webhookModel->buildData();
timeout=0
cid=1

- 正常传入的情况 @12
- 不传objectType @0
- 不传actionType @0
- 不传actionID @0

*/

$webhook = new webhookTest();

$objectType = array();
$objectType[0] = 'release';
$objectType[1] = '';

$objectID = array();
$objectID[0] = 1;
$objectID[1] = 0;

$actionType = array();
$actionType[0] = 'created';
$actionType[1] = '';

$actionID = array();
$actionID[0] = 1;
$actionID[1] = 0;

$result1 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[0], $actionID[0]);
$result2 = $webhook->buildDataTest($objectType[1], $objectID[0], $actionType[0], $actionID[0]);
$result3 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[1], $actionID[0]);
$result4 = $webhook->buildDataTest($objectType[0], $objectID[0], $actionType[0], $actionID[1]);

r(strpos($result1, 'markdown')) && p() && e('12'); //正常传入的情况
r($result2)                     && p() && e('0');  //不传objectType
r($result3)                     && p() && e('0');  //不传actionType
r($result4)                     && p() && e('0');  //不传actionID