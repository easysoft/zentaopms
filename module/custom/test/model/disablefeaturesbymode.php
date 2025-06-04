#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('story')->gen(0);
zenData('user')->gen(5);
su('admin');

/**

title=测试 customModel->disableFeaturesByMode();
timeout=0
cid=1

- 将模式设置为全生命周期管理模式 @0
- 将模式设置为轻量级管理模式 @productER,productUR,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap

*/

$modeList = array('ALM', 'light');

$customTester = new customTest();
r($customTester->disableFeaturesByModeTest($modeList[0]))      && p() && e('0');                                                                                                           // 将模式设置为全生命周期管理模式
r($customTester->disableFeaturesByModeTest($modeList[1], ';')) && p() && e('productER,productUR,waterfall,waterfallplus,scrumMeasrecord,agileplusMeasrecord,productTrack,productRoadmap'); // 将模式设置为轻量级管理模式