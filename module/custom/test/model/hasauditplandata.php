#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasAuditplanData();
timeout=0
cid=15910

- 测试开源版中无审计数据 @0
- 测试ipd版中无审计数据 @0
- 测试旗舰版中无审计数据 @0
- 测试开源版中有审计数据 @0
- 测试ipd版中有审计数据 @5
- 测试旗舰版中有审计数据 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->model->range('scrum');
$projectTable->gen(5);

zenData('auditplan')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasAuditplanDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无审计数据
r($customTester->hasAuditplanDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无审计数据
r($customTester->hasAuditplanDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无审计数据

$auditplanTable = zenData('auditplan');
$auditplanTable->deleted->range('0');
$auditplanTable->project->range('1-5');
$auditplanTable->gen(5);
r($customTester->hasAuditplanDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有审计数据
r($customTester->hasAuditplanDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有审计数据
r($customTester->hasAuditplanDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有审计数据