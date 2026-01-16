#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasDeliverableData();
timeout=0
cid=15914

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('deliverable')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasDeliverableDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无项目活动数据
r($customTester->hasDeliverableDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无项目活动数据
r($customTester->hasDeliverableDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无项目活动数据

zenData('deliverable')->gen(1);
r($customTester->hasDeliverableDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有项目活动数据
r($customTester->hasDeliverableDataTest($editionList[1])) && p() && e('1'); // 测试ipd版中有项目活动数据
r($customTester->hasDeliverableDataTest($editionList[2])) && p() && e('1'); // 测试旗舰版中有项目活动数据
