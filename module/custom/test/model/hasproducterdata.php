#!/usr/bin/env php
<?php

/**

title=测试 customModel::hasProductERData();
timeout=0
cid=15908

- 步骤1：测试空数据库情况 @0
- 步骤2：测试有epic类型story且未删除 @3
- 步骤3：测试有epic类型story但已删除 @0
- 步骤4：测试有story但类型不是epic @0
- 步骤5：测试混合数据情况 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('story')->gen(0);
zenData('user')->gen(5);
su('admin');

$customTester = new customTest();
r($customTester->hasProductERDataTest()) && p() && e('0'); // 步骤1：测试空数据库情况

$storyTable = zenData('story');
$storyTable->type->range('epic');
$storyTable->deleted->range('0');
$storyTable->gen(3);
r($customTester->hasProductERDataTest()) && p() && e('3'); // 步骤2：测试有epic类型story且未删除

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('epic');
$storyTable->deleted->range('1');
$storyTable->gen(2);
r($customTester->hasProductERDataTest()) && p() && e('0'); // 步骤3：测试有epic类型story但已删除

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('story');
$storyTable->deleted->range('0');
$storyTable->gen(4);
r($customTester->hasProductERDataTest()) && p() && e('0'); // 步骤4：测试有story但类型不是epic

zenData('story')->gen(0);
$storyTable = zenData('story');
$storyTable->type->range('epic{2}, story{3}');
$storyTable->deleted->range('0{4}, 1{1}');
$storyTable->gen(5);
r($customTester->hasProductERDataTest()) && p() && e('2'); // 步骤5：测试混合数据情况