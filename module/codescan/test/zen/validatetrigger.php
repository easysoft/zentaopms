#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->validateTrigger();
timeout=0
cid=0

- 测试action类型返回true >> 1
- 测试cron类型基本验证 >> 1
- 测试空triggerType返回true >> 1
- 测试cron带minute字段 >> 1
- 测试空对象触发默认 >> 1

*/

su('admin');
$test = new codescanZenTest();

$trigger = new stdclass();
$trigger->triggerType = 'action';
r($test->validateTriggerTest($trigger)) && p() && e('1');
$trigger->triggerType = 'cron';
$trigger->minute = '0';
$trigger->hour = '9';
r($test->validateTriggerTest($trigger)) && p() && e('1');
$trigger2 = new stdclass();
$trigger2->triggerType = '';
r($test->validateTriggerTest($trigger2)) && p() && e('1');
$trigger3 = new stdclass();
$trigger3->triggerType = 'cron';
$trigger3->minute = '*';
r($test->validateTriggerTest($trigger3)) && p() && e('1');
r($test->validateTriggerTest(new stdclass())) && p() && e('1');
