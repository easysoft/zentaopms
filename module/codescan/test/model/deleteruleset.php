#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->deleteRuleset();
timeout=0
cid=0

- 删除第一个自定义规则集 @1
- 删除第二个自定义规则集 @1
- 测试ID为0的调用 @0
- 删除不存在的规则集 @0
- 删除另一个不存在的规则集 @0

*/

$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$rulesetAID = $test->createRulesetTest((object)array('name' => "codescan-delete-ruleset-{$runID}-a", 'isCustom' => true));
$rulesetBID = $test->createRulesetTest((object)array('name' => "codescan-delete-ruleset-{$runID}-b", 'isCustom' => true));
$missingID = 999999999;

r($test->deleteRulesetTest($rulesetAID)) && p() && e('1');
r($test->deleteRulesetTest($rulesetBID)) && p() && e('1');
r($test->deleteRulesetTest(0)) && p() && e('0');
r($test->deleteRulesetTest($missingID)) && p() && e('0');
r($test->deleteRulesetTest($missingID + 1)) && p() && e('0');
