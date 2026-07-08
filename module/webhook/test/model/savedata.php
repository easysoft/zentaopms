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

- $webhookID[0], $actionID[0], $data[0], $actor[0] @1
- $webhookID[1], $actionID[1], $data[1], $actor[1] @1
- $webhookID[0], $actionID[0], $data[2], $actor[0] @1
- $webhookID[0], $actionID[2], $data[3], $actor[0] @1
- $webhookID[0], $actionID[0], $data[4], $actor[1] @1

*/

$webhook = new webhookModelTest();

$webhookID    = array();
$webhookID[0] = 3;
$webhookID[1] = 0;

$actionID    = array();
$actionID[0] = 3;
$actionID[1] = 0;
$actionID[2] = 8;

$data    = array();
$data[0] = '{"event":"opened","objectType":"productplan","objectID":3}';
$data[1] = '';
$data[2] = '{"event":"opened","objectType":"productplan","objectID":1}';
$data[3] = '{"event":"edited","objectType":"productplan","objectID":1}';
$data[4] = 'special chars: 中文';

$actor     = array();
$actor[0]  = 'test18';
$actor[1]  = '';

$result1 = $webhook->saveDataTest($webhookID[0], $actionID[0], $data[0], $actor[0]);
$result2 = $webhook->saveDataTest($webhookID[1], $actionID[1], $data[1], $actor[1]);
$result3 = $webhook->saveDataTest($webhookID[0], $actionID[0], $data[2], $actor[0]);
$result4 = $webhook->saveDataTest($webhookID[0], $actionID[2], $data[3], $actor[0]);
$result5 = $webhook->saveDataTest($webhookID[0], $actionID[0], $data[4], $actor[1]);

r($result1) && p() && e('1'); // $webhookID[0], $actionID[0], $data[0], $actor[0]
r($result2) && p() && e('1'); // $webhookID[1], $actionID[1], $data[1], $actor[1]
r($result3) && p() && e('1'); // $webhookID[0], $actionID[0], $data[2], $actor[0]
r($result4) && p() && e('1'); // $webhookID[0], $actionID[2], $data[3], $actor[0]
r($result5) && p() && e('1'); // $webhookID[0], $actionID[0], $data[4], $actor[1]
