#!/usr/bin/env php
<?php

/**

title=测试 customModel->hasProcessData();
timeout=0
cid=15914

- 测试开源版中无度量数据 @0
- 测试ipd版中无度量数据 @0
- 测试旗舰版中无度量数据 @0
- 测试其他情况 @0
- 测试其他情况 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('programactivity')->gen(0);
zenData('user')->gen(5);
su('admin');

$editionList = array('open', 'ipd', 'max');

$customTester = new customModelTest();
r($customTester->hasMeasrecordDataTest($editionList[0])) && p() && e('0'); // 测试开源版中无度量数据
r($customTester->hasMeasrecordDataTest($editionList[1])) && p() && e('0'); // 测试ipd版中无度量数据
r($customTester->hasMeasrecordDataTest($editionList[2])) && p() && e('0'); // 测试旗舰版中无度量数据

r($customTester->hasMeasrecordDataTest('test')) && p() && e('0'); // 测试其他情况
r($customTester->hasMeasrecordDataTest('')) && p() && e('0'); // 测试其他情况