#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasScrumProcessData();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('programactivity')->gen(0);
zdTable('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customTest();
r($customTester->hasScrumProcessDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无项目活动数据
r($customTester->hasScrumProcessDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无项目活动数据
r($customTester->hasScrumProcessDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无项目活动数据

$programactivityTable = zdTable('programactivity');
$programactivityTable->execution->range('1-5');
$programactivityTable->deleted->range('0');
$programactivityTable->gen(5);
r($customTester->hasScrumProcessDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有项目活动数据
r($customTester->hasScrumProcessDataTest($editionList[1])) && p() && e('5'); // 测试ipd版中有项目活动数据
r($customTester->hasScrumProcessDataTest($editionList[2])) && p() && e('5'); // 测试旗舰版中有项目活动数据
