#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::handleWebhook();
timeout=0
cid=0

- 测试未知事件类型 @0
- 测试Push Hook未匹配事件 @0
- 测试Tag Push Hook @0
- 测试Merge Request Hook @0
- 测试空事件数据 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(2);
su('admin');

$tester = new pipelineModelTest();

$pushEvent   = (object)array('commits' => array((object)array('message' => 'fix bug')));
$emptyEvent  = (object)array('commits' => array());
$pipeline     = (object)array('id' => 1, 'event' => 'push', 'providerID' => 1, 'externalPipeline' => 'pipeline-1');

r($tester->handleWebhookTest('Unknown Event', $pushEvent, $pipeline)) && p() && e('0');
r($tester->handleWebhookTest('Push Hook', $emptyEvent, $pipeline)) && p() && e('0');
r($tester->handleWebhookTest('Tag Push Hook', $pushEvent, $pipeline)) && p() && e('0');
r($tester->handleWebhookTest('Merge Request Hook', $pushEvent, $pipeline)) && p() && e('0');
r($tester->handleWebhookTest('Push Hook', $pushEvent, (object)array('id' => 1, 'event' => 'tag_push'))) && p() && e('0');
