#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->updateScanRulesetStatus();
timeout=0
cid=0

- 更新 1 号规则集为禁用状态 @edit1,disabled
- 更新 2 号规则集传 enabled 实际仍为 disabled @test2,disabled
- 更新 0 号规则集状态失败 @0
- 更新 3 号规则集状态失败 @0
- 更新 4 号规则集状态失败 @0

*/

$test = new codescanModelTest();

r($test->updateScanRulesetStatusTest(1, 'disabled')) && p('name,status') && e('edit1,disabled');
r($test->updateScanRulesetStatusTest(2, 'enabled')) && p('name,status') && e('test2,disabled');
r($test->updateScanRulesetStatusTest(0, 'disabled')) && p() && e('0');
r($test->updateScanRulesetStatusTest(3, 'enabled')) && p() && e('0');
r($test->updateScanRulesetStatusTest(4)) && p() && e('0');
