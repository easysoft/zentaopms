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

- 编辑 1 号规则集名称成功 @1
- 编辑 2 号规则集空对象成功 @1
- 编辑 0 号规则集失败 @0
- 编辑不存在的 3 号规则集失败 @0
- 编辑不存在的 4 号规则集失败 @0

*/

$test = new codescanModelTest();

$data1 = (object)array('name' => 'edit1');
$data2 = (object)array('name' => 'edit2');

r($test->editRulesetTest(1, $data1)) && p() && e('1');
r($test->editRulesetTest(2, new stdclass())) && p() && e('1');
r($test->editRulesetTest(0)) && p() && e('0');
r($test->editRulesetTest(3, $data2)) && p() && e('0');
r($test->editRulesetTest(4, new stdclass())) && p() && e('0');
