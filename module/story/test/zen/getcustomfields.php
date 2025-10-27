#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getCustomFields();
timeout=0
cid=0

- 步骤1：正常产品类型返回9个字段 @9
- 步骤2：branch产品类型包含branch字段 @1
- 步骤3：platform产品类型包含platform字段 @1
- 步骤4：隐藏计划字段后不包含plan @0
- 步骤5：project标签下不包含parent字段 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{2},branch{2},platform{1}');
$product->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($storyTest->getCustomFieldsTest('story', false, 1))) && p() && e('9'); // 步骤1：正常产品类型返回9个字段
r(isset($storyTest->getCustomFieldsTest('story', false, 3)['branch'])) && p() && e('1'); // 步骤2：branch产品类型包含branch字段
r(isset($storyTest->getCustomFieldsTest('story', false, 5)['platform'])) && p() && e('1'); // 步骤3：platform产品类型包含platform字段
r(isset($storyTest->getCustomFieldsTest('story', true, 1)['plan'])) && p() && e('0'); // 步骤4：隐藏计划字段后不包含plan
r(isset($storyTest->getCustomFieldsTest('story', false, 1, 'project')['parent'])) && p() && e('0'); // 步骤5：project标签下不包含parent字段