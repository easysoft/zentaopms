#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::apiUpdateTrigger();
timeout=0
cid=0

- 测试apiUpdateTrigger调用不报错 @1
- 测试无效pipelineID=0 @1
- 测试不存在triggerID @1
- 测试空trigger对象 @1
- 测试重复调用幂等性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

$trigger1 = (object)array('event' => 'push', 'cron' => '', 'comment' => 'test');
$trigger2 = (object)array('event' => '', 'cron' => '', 'comment' => '');

$r1 = $tester->apiUpdateTriggerTest(1, 1, $trigger1);
$r2 = $tester->apiUpdateTriggerTest(0, 1, $trigger1);
$r3 = $tester->apiUpdateTriggerTest(1, 99999, $trigger1);
$r4 = $tester->apiUpdateTriggerTest(1, 1, $trigger2);
$r5 = $tester->apiUpdateTriggerTest(1, 1, $trigger1);

r($r1 === false ? '1' : '0') && p() && e('1');
r($r2 === false ? '1' : '0') && p() && e('1');
r($r3 === false ? '1' : '0') && p() && e('1');
r($r4 === false ? '1' : '0') && p() && e('1');
r($r5 === false ? '1' : '0') && p() && e('1');
