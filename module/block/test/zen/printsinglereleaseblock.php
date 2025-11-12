#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printSingleReleaseBlock();
timeout=0
cid=0

- 步骤1：正常情况，限制5条属性releasesCount @5
- 步骤2：产品2有5条记录属性releasesCount @5
- 步骤3：无效产品ID属性releasesCount @0
- 步骤4：仅返回1条属性releasesCount @1
- 步骤5：产品1有10条记录属性releasesCount @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
$release = zenData('release');
$release->id->range('1-20');
$release->product->range('1{10},2{5},3{5}');
$release->name->range('Release 1.0,Release 2.0,Release 3.0,Release 4.0,Release 5.0');
$release->status->range('normal{15},terminate{5}');
$release->deleted->range('0{18},1{2}');
$release->gen(20);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->deleted->range('0');
$product->gen(5);

$build = zenData('build');
$build->id->range('1-15');
$build->product->range('1{8},2{4},3{3}');
$build->name->range('Build 1.0,Build 2.0,Build 3.0');
$build->deleted->range('0{13},1{2}');
$build->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 步骤1：正常情况测试，产品ID=1，限制数量=5
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 5;
r($blockTest->printSingleReleaseBlockTest($block1, 1)) && p('releasesCount') && e('5'); // 步骤1：正常情况，限制5条

// 步骤2：正常情况测试，产品ID=2，限制数量=10
$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->count = 10;
r($blockTest->printSingleReleaseBlockTest($block2, 2)) && p('releasesCount') && e('5'); // 步骤2：产品2有5条记录

// 步骤3：边界值测试，产品ID=0，限制数量=5
$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = 5;
r($blockTest->printSingleReleaseBlockTest($block3, 0)) && p('releasesCount') && e('0'); // 步骤3：无效产品ID

// 步骤4：边界值测试，限制数量=1
$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->count = 1;
r($blockTest->printSingleReleaseBlockTest($block4, 1)) && p('releasesCount') && e('1'); // 步骤4：仅返回1条

// 步骤5：大数量限制测试，限制数量=100
$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->count = 100;
r($blockTest->printSingleReleaseBlockTest($block5, 1)) && p('releasesCount') && e('10'); // 步骤5：产品1有10条记录