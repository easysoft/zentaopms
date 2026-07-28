#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->updateScanRuleStatus();
timeout=0
cid=0

- 测试ID为1的调用 >> 0
- 测试规则2状态返回0 >> 2,0,none
- 测试ID为0的调用 >> 0
- 测试规则3状态返回0 >> 3,0,none
- 测试ID为2的调用 >> 0

*/

$test = new codescanModelTest();

r($test->updatescanrulestatusTest(1)) && p() && e('0');
r($test->updatescanrulestatusTest(2)) && p() && e('0');
r($test->updatescanrulestatusTest(0)) && p() && e('0');
r($test->updatescanrulestatusTest(3)) && p() && e('0');
r($test->updatescanrulestatusTest(4)) && p() && e('0');
