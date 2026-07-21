#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->validateTrigger();
timeout=0
cid=0

- 测试空triggerType返回true >> 1
- 测试action类型返回true >> 1
- 测试cron类型基本验证 >> 1
- 测试带有效minute字段 >> 1
- 测试带有效hour字段 >> 1

*/

su('admin');
$test = new codescanZenTest();

$t1 = new stdclass();
r($test->validateTriggerTest($t1) ? '1' : '0') && p() && e('1');

$t2 = new stdclass(); $t2->triggerType = 'action';
r($test->validateTriggerTest($t2) ? '1' : '0') && p() && e('1');

$t3 = new stdclass(); $t3->triggerType = 'cron';
r(is_bool($test->validateTriggerTest($t3)) ? '1' : '0') && p() && e('1');

$t4 = new stdclass(); $t4->triggerType = 'cron'; $t4->minute = '*/5';
r(is_bool($test->validateTriggerTest($t4)) ? '1' : '0') && p() && e('1');

$t5 = new stdclass(); $t5->triggerType = 'cron'; $t5->hour = '*';
r(is_bool($test->validateTriggerTest($t5)) ? '1' : '0') && p() && e('1');
