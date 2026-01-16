#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasOpportunityData();
timeout=0
cid=15913

- 测试开源版中无机会数据 @0
- 测试ipd版中无机会数据 @0
- 测试旗舰版中无机会数据 @0
- 测试开源版中有机会数据 @0
- 测试ipd版中有机会数据 @5
- 测试旗舰版中有机会数据 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('opportunity')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasOpportunityDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无机会数据
r($customTester->hasOpportunityDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无机会数据
r($customTester->hasOpportunityDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无机会数据

$opportunityTable = zenData('opportunity');
$opportunityTable->execution->range('1-5');
$opportunityTable->deleted->range('0');
$opportunityTable->gen(5);
r($customTester->hasOpportunityDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有机会数据
r($customTester->hasOpportunityDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有机会数据
r($customTester->hasOpportunityDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有机会数据