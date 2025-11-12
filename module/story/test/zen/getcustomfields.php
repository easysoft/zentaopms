#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getCustomFields();
timeout=0
cid=0

- 步骤1:normal产品且不隐藏plan属性plan @所属计划
- 步骤2:branch产品且不隐藏plan
 - 属性branch @分支
 - 属性plan @所属计划
- 步骤3:platform产品且不隐藏plan
 - 属性platform @平台
 - 属性plan @所属计划
- 步骤4:normal产品且隐藏plan返回字段数量 @8
- 步骤5:branch产品且隐藏plan包含branch字段属性branch @分支
- 步骤6:normal产品不隐藏plan返回字段数量 @9
- 步骤7:branch产品不隐藏plan返回字段数量 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$storyZenTest = new storyZenTest();

// 4. 准备产品对象
$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

$platformProduct = new stdclass();
$platformProduct->id = 3;
$platformProduct->type = 'platform';

// 5. 测试步骤 - 必须包含至少5个测试步骤
r($storyZenTest->getCustomFieldsTest('story', false, $normalProduct, 'product')) && p('plan') && e('所属计划'); // 步骤1:normal产品且不隐藏plan
r($storyZenTest->getCustomFieldsTest('story', false, $branchProduct, 'product')) && p('branch,plan') && e('分支,所属计划'); // 步骤2:branch产品且不隐藏plan
r($storyZenTest->getCustomFieldsTest('story', false, $platformProduct, 'product')) && p('platform,plan') && e('平台,所属计划'); // 步骤3:platform产品且不隐藏plan
r(count($storyZenTest->getCustomFieldsTest('story', true, $normalProduct, 'product'))) && p() && e('8'); // 步骤4:normal产品且隐藏plan返回字段数量
r($storyZenTest->getCustomFieldsTest('story', true, $branchProduct, 'product')) && p('branch') && e('分支'); // 步骤5:branch产品且隐藏plan包含branch字段
r(count($storyZenTest->getCustomFieldsTest('story', false, $normalProduct, 'product'))) && p() && e('9'); // 步骤6:normal产品不隐藏plan返回字段数量
r(count($storyZenTest->getCustomFieldsTest('story', false, $branchProduct, 'product'))) && p() && e('10'); // 步骤7:branch产品不隐藏plan返回字段数量