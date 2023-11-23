#!/usr/bin/env php
<?php
/**

title=测试 customModel->hasWaterfallplusData();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

zdTable('project')->gen(0);
zdTable('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->hasWaterfallplusDataTest()) && p() && e('0'); // 测试系统中无融合瀑布项目数据

$projectTable = zdTable('project');
$projectTable->model->range('waterfallplus');
$projectTable->deleted->range('0');
$projectTable->gen(5);
r($customTester->hasWaterfallplusDataTest()) && p() && e('5'); // 测试系统中有融合瀑布项目数据
