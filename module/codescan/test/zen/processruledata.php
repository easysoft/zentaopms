#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->processRuleData();
timeout=0
cid=0

- 校验name字段 >> test_rule_name
- 校验priority字段 >> high
- 测试空rule返回对象 >> 1
- 校验带rulekey的name字段 >> original
- 测试有效rule返回对象 >> 1

*/

su('admin');
$test = new codescanZenTest();

$rule = new stdclass();
$rule->name = 'test_rule_name';
$rule->priority = 'high';
r($test->processRuleDataTest($rule)) && p('name') && e('test_rule_name');
r($test->processRuleDataTest($rule)) && p('priority') && e('high');
r(is_object($test->processRuleDataTest(new stdclass()))) && p() && e('1');
$rule2 = new stdclass();
$rule2->name = 'original';
$rule2->rulekey = 'RULE_001';
$rule2->description_en = 'English description';
r($test->processRuleDataTest($rule2)) && p('name') && e('original');
r(is_object($test->processRuleDataTest($rule))) && p() && e('1');
