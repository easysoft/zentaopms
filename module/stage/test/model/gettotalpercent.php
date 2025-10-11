#!/usr/bin/env php
<?php

/**

title=测试 stageModel->getTotalPercent();
timeout=0
cid=1

- 测试获取敏捷模型的总百分比 @0
- 测试获取瀑布模型的总百分比 @60
- 测试获取融合瀑布模型的总百分比 @60
- 测试获取IPD模型的总百分比 @0
- 测试获取看板模型的总百分比 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stage.unittest.class.php';

zenData('user')->gen(5);
zenData('stage')->loadYaml('stage')->gen(12);

$stageTester = new stageTest();
r($stageTester->getTotalPercentTest(2))  && p() && e('0');  // 测试获取敏捷模型的总百分比
r($stageTester->getTotalPercentTest(4))  && p() && e('60'); // 测试获取瀑布模型的总百分比
r($stageTester->getTotalPercentTest(8))  && p() && e('60'); // 测试获取融合瀑布模型的总百分比
r($stageTester->getTotalPercentTest(12)) && p() && e('0');  // 测试获取IPD模型的总百分比
r($stageTester->getTotalPercentTest(13)) && p() && e('0');  // 测试获取看板模型的总百分比
