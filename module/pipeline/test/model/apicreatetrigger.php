#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::apiCreateTrigger();
timeout=0
cid=0

- 测试apiCreateTrigger调用不报错 @1
- 测试无效pipelineID=0 @1
- 测试空trigger对象 @1
- 测试正常trigger对象(push事件) @1
- 测试带cron的trigger @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tester = new pipelineModelTest();

$trigger1 = (object)array('event' => 'push', 'cron' => '', 'comment' => 'test');
$trigger2 = (object)array('event' => '', 'cron' => '', 'comment' => '');
$trigger3 = (object)array('event' => '', 'cron' => '0 10 * * *', 'comment' => '');

$r1 = $tester->apiCreateTriggerTest(1, $trigger1);
$r2 = $tester->apiCreateTriggerTest(0, $trigger1);
$r3 = $tester->apiCreateTriggerTest(1, $trigger2);
$r4 = $tester->apiCreateTriggerTest(1, $trigger3);
$r5 = $tester->apiCreateTriggerTest(99999, $trigger1);

r($r1 === false ? '1' : '0') && p() && e('1');
r($r2 === false ? '1' : '0') && p() && e('1');
r($r3 === false ? '1' : '0') && p() && e('1');
r($r4 === false ? '1' : '0') && p() && e('1');
r($r5 === false ? '1' : '0') && p() && e('1');
