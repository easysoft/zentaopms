#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::getExcludeStoryIdList();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性1 @1
 - 属性2 @2
 - 属性3 @3
- 步骤2：空需求列表 @0
- 步骤3：不存在的需求ID
 - 属性99 @99
 - 属性100 @100
- 步骤4：不同产品的需求
 - 属性10 @10
 - 属性6 @6
 - 属性7 @7
 - 属性8 @8
- 步骤5：产品下无父需求
 - 属性1 @1
 - 属性2 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1{5},2{5}');
$table->type->range('story{10}');
$table->isParent->range('0{6},1{4}');
$table->status->range('active{4},draft{2},reviewing{2},changing{1},closed{1}');
$table->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5,需求标题6,需求标题7,需求标题8,需求标题9,需求标题10');
$table->openedBy->range('admin{10}');
$table->openedDate->range('`2023-01-01 00:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$release1 = new stdclass();
$release1->product = 1;
$release1->stories = '1,2,3';
r($releaseTest->getExcludeStoryIdListTest($release1)) && p('1,2,3') && e('1,2,3'); // 步骤1：正常情况

$release2 = new stdclass();
$release2->product = 1;
$release2->stories = '';
r($releaseTest->getExcludeStoryIdListTest($release2)) && p() && e('0'); // 步骤2：空需求列表

$release3 = new stdclass();
$release3->product = 1;
$release3->stories = '99,100';
r($releaseTest->getExcludeStoryIdListTest($release3)) && p('99,100') && e('99,100'); // 步骤3：不存在的需求ID

$release4 = new stdclass();
$release4->product = 2;
$release4->stories = '6,7,8';
r($releaseTest->getExcludeStoryIdListTest($release4)) && p('10,6,7,8') && e('10,6,7,8'); // 步骤4：不同产品的需求

$release5 = new stdclass();
$release5->product = 3;
$release5->stories = '1,2';
r($releaseTest->getExcludeStoryIdListTest($release5)) && p('1,2') && e('1,2'); // 步骤5：产品下无父需求