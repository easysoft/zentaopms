#!/usr/bin/env php
<?php

/**

title=测试 customModel::hasProductURData();
timeout=0
cid=15909

- 步骤1：测试空数据库中无用户需求数据 @0
- 步骤2：测试非requirement类型story时返回0 @0
- 步骤3：测试已删除的requirement类型story时返回0 @0
- 步骤4：测试正常的requirement类型story时返回正确数量 @5
- 步骤5：测试混合类型story时只返回requirement且未删除的数量 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(0);
zenData('user')->gen(5);
su('admin');

$customTester = new customModelTest();

r($customTester->hasProductURDataTest()) && p() && e('0'); // 步骤1：测试空数据库中无用户需求数据

$storyTable = zenData('story');
$storyTable->type->range('story');
$storyTable->deleted->range('0');
$storyTable->gen(3);
r($customTester->hasProductURDataTest()) && p() && e('0'); // 步骤2：测试非requirement类型story时返回0

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('1');
$storyTable->gen(2);
r($customTester->hasProductURDataTest()) && p() && e('0'); // 步骤3：测试已删除的requirement类型story时返回0

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('requirement');
$storyTable->deleted->range('0');
$storyTable->gen(5);
r($customTester->hasProductURDataTest()) && p() && e('5'); // 步骤4：测试正常的requirement类型story时返回正确数量

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('story{3},requirement{4},epic{2}');
$storyTable->deleted->range('0{7},1{2}');
$storyTable->gen(9);
r($customTester->hasProductURDataTest()) && p() && e('4'); // 步骤5：测试混合类型story时只返回requirement且未删除的数量