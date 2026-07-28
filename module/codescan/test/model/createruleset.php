#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);
su('admin');

/**

title=测试 codescanModel->createRuleset();
timeout=0
cid=0

- 创建第一个规则集 @1
- 空对象创建规则集失败 @0
- 只传描述创建规则集失败 @0
- 名称为空创建规则集失败 @0
- 创建第二个规则集 @2

*/

$test = new codescanModelTest();

$rulesetA = (object)array('name' => 'test1');
$rulesetB = (object)array('desc' => 'only desc');
$rulesetC = (object)array('name' => '');
$rulesetD = (object)array('name' => 'test2');

r($test->createRulesetTest($rulesetA)) && p() && e('1');
r($test->createRulesetTest(new stdclass())) && p() && e('0');
r($test->createRulesetTest($rulesetB)) && p() && e('0');
r($test->createRulesetTest($rulesetC)) && p() && e('0');
r($test->createRulesetTest($rulesetD)) && p() && e('2');
