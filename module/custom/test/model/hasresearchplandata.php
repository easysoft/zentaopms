#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasResearchplanData();
timeout=0
cid=15915

- 测试开源版中无调研计划数据 @0
- 测试ipd版中无调研计划数据 @0
- 测试旗舰版中无调研计划数据 @0
- 测试开源版中有调研计划数据 @0
- 测试ipd版中有调研计划数据 @5
- 测试旗舰版中有调研计划数据 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->model->range('scrum');
$projectTable->gen(5);

zenData('researchplan')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasResearchplanDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无调研计划数据
r($customTester->hasResearchplanDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无调研计划数据
r($customTester->hasResearchplanDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无调研计划数据

$researchplanTable = zenData('researchplan');
$researchplanTable->deleted->range('0');
$researchplanTable->project->range('1-5');
$researchplanTable->gen(5);
r($customTester->hasResearchplanDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有调研计划数据
r($customTester->hasResearchplanDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有调研计划数据
r($customTester->hasResearchplanDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有调研计划数据