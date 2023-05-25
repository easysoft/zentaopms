#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';
su('admin');

$stage = zdTable('stage');
$stage->id->range('1-10');
$stage->percent->range('10-20');
$stage->projectType->range('waterfall{5}, waterfallplus{5}');
$stage->deleted->range('0-1');
$stage->gen(10);

/**

title=测试 stageModel->getTotalPercent();
cid=1
pid=1

测试获取瀑布项目的总百分比 >> 36
测试获取融合瀑布项目的总百分比 >> 34

*/

$stage = new stageTest();

r($stage->getTotalPercentTest('waterfall'))     && p() && e('36'); // 测试获取瀑布项目的总百分比
r($stage->getTotalPercentTest('waterfallplus')) && p() && e('34'); // 测试获取融合瀑布项目的总百分比
