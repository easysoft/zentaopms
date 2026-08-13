#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->editRuleset();
timeout=0
cid=0

- 编辑第一个规则集名称成功 @1
- 编辑第二个规则集空对象成功 @1
- 编辑 0 号规则集失败 @0
- 编辑不存在的规则集失败 @0
- 编辑另一个不存在的规则集失败 @0

*/

$test = new codescanModelTest();

$runID = date('YmdHis') . '-' . getmypid();
$rulesetAID = $test->createRulesetTest((object)array('name' => "codescan-edit-ruleset-{$runID}-a", 'isCustom' => true));
$rulesetBID = $test->createRulesetTest((object)array('name' => "codescan-edit-ruleset-{$runID}-b", 'isCustom' => true));
$data1 = (object)array('name' => "codescan-edit-ruleset-{$runID}-edited");

r($test->editRulesetTest($rulesetAID, $data1)) && p() && e('1');
r($test->editRulesetTest($rulesetBID, new stdclass())) && p() && e('1');
r($test->editRulesetTest(0)) && p() && e('0');
r($test->editRulesetTest(999999999, $data1)) && p() && e('0');
r($test->editRulesetTest(1000000000, new stdclass())) && p() && e('0');

dao::$errors = array();
$test->deleteRulesetTest($rulesetAID);
dao::$errors = array();
$test->deleteRulesetTest($rulesetBID);
