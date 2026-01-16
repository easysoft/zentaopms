#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasRiskData();
timeout=0
cid=15915

- 测试开源版中无风险数据 @0
- 测试ipd版中无风险数据 @0
- 测试旗舰版中无风险数据 @0
- 测试开源版中有风险数据 @0
- 测试ipd版中有风险数据 @5
- 测试旗舰版中有风险数据 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->model->range('scrum');
$projectTable->gen(5);

zenData('risk')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasRiskDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无风险数据
r($customTester->hasRiskDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无风险数据
r($customTester->hasRiskDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无风险数据

$riskTable = zenData('risk');
$riskTable->deleted->range('0');
$riskTable->project->range('1-5');
$riskTable->gen(5);
r($customTester->hasRiskDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有风险数据
r($customTester->hasRiskDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有风险数据
r($customTester->hasRiskDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有风险数据