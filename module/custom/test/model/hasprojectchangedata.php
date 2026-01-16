#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasProjectchangeData();
timeout=0
cid=15914

- 测试开源版中无项目活动数据 @0
- 测试ipd版中无项目活动数据 @0
- 测试旗舰版中无项目活动数据 @0
- 测试开源版中有项目活动数据 @0
- 测试ipd版中有项目活动数据 @1
- 测试旗舰版中有项目活动数据 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('projectchange')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasProjectchangeDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无项目活动数据
r($customTester->hasProjectchangeDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无项目活动数据
r($customTester->hasProjectchangeDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无项目活动数据

zenData('object')->gen(1);
r($customTester->hasProjectchangeDataTest($editionList[0])) && p() && e('0'); // 测试开源版中有项目活动数据
r($customTester->hasProjectchangeDataTest($editionList[1])) && p() && e('1'); // 测试ipd版中有项目活动数据
r($customTester->hasProjectchangeDataTest($editionList[2])) && p() && e('1'); // 测试旗舰版中有项目活动数据