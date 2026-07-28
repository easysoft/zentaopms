#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getScanRulesetRules();
timeout=0
cid=0

- 测试有ID和数组参数 >> 0
- 测试规则集2空参数返回0 >> 2,empty,0,0
- 测试默认参数 >> 0
- 测试规则集1多参数返回0 >> 1,4|5|6,3,0
- 测试不同数组参数 >> 0

*/

$test = new codescanModelTest();

r($test->getscanrulesetrulesTest(1, array(1, 2, 3))) && p() && e('0');
r($test->getscanrulesetrulesTest(2, array())) && p() && e('0');
r($test->getscanrulesetrulesTest(0, array(1))) && p() && e('0');
r($test->getscanrulesetrulesTest(1, array(4, 5, 6))) && p() && e('0');
r($test->getscanrulesetrulesTest(2, array(7, 8))) && p() && e('0');
