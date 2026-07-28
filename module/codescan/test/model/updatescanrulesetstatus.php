#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->updateScanRulesetStatus();
timeout=0
cid=0

- 测试disabled状态 >> 0
- 测试规则集2启用状态返回0 >> 2,enabled,0,none
- 测试enabled状态 >> 0
- 测试默认参数 >> 0
- 测试规则集3启用状态返回0 >> 3,enabled,0,none

*/

$test = new codescanModelTest();

r($test->updatescanrulesetstatusTest(1, 'disabled')) && p() && e('0');
r($test->updatescanrulesetstatusTest(2, 'enabled')) && p() && e('0');
r($test->updatescanrulesetstatusTest(0, 'disabled')) && p() && e('0');
r($test->updatescanrulesetstatusTest(3, 'enabled')) && p() && e('0');
r($test->updatescanrulesetstatusTest(4)) && p() && e('0');
