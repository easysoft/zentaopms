#!/usr/bin/env php
<?php

/**

title=测试 customModel::hasWaterfallplusData();
timeout=0
cid=15917

- 步骤1：系统中无任何项目数据 @0
- 步骤2：系统中有非融合瀑布项目数据 @0
- 步骤3：系统中有已删除的融合瀑布项目数据 @0
- 步骤4：系统中有5个正常的融合瀑布项目数据 @5
- 步骤5：系统中混合存在多种类型项目数据 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$customTest = new customModelTest();

// 步骤1：测试系统中无任何项目数据的情况
zenData('project')->gen(0);
su('admin');
r($customTest->hasWaterfallplusDataTest()) && p() && e('0'); // 步骤1：系统中无任何项目数据

// 步骤2：测试系统中有非融合瀑布项目数据的情况
$projectTable = zenData('project');
$projectTable->model->range('scrum{3},waterfall{2}');
$projectTable->deleted->range('0');
$projectTable->gen(5);
r($customTest->hasWaterfallplusDataTest()) && p() && e('0'); // 步骤2：系统中有非融合瀑布项目数据

// 步骤3：测试系统中有已删除的融合瀑布项目数据的情况
zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfallplus');
$projectTable->deleted->range('1');
$projectTable->gen(3);
r($customTest->hasWaterfallplusDataTest()) && p() && e('0'); // 步骤3：系统中有已删除的融合瀑布项目数据

// 步骤4：测试系统中有正常的融合瀑布项目数据的情况
zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('waterfallplus');
$projectTable->deleted->range('0');
$projectTable->gen(5);
r($customTest->hasWaterfallplusDataTest()) && p() && e('5'); // 步骤4：系统中有5个正常的融合瀑布项目数据

// 步骤5：测试系统中混合存在多种类型项目数据的情况
zenData('project')->gen(0);
$projectTable = zenData('project');
$projectTable->model->range('scrum{2},waterfall{2},waterfallplus{3},agileplus{1}');
$projectTable->deleted->range('0{7},1{1}');
$projectTable->gen(8);
r($customTest->hasWaterfallplusDataTest()) && p() && e('3'); // 步骤5：系统中混合存在多种类型项目数据