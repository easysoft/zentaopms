#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForEdit();
timeout=0
cid=0

- 步骤1：正常需求编辑表单字段第title条的name属性 @title
- 步骤2：不存在的需求ID属性error @story_not_found
- 步骤3：无效需求ID属性error @story_not_found
- 步骤4：检查产品字段配置第product条的name属性 @product
- 步骤5：检查阶段字段配置第stage条的name属性 @stage

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$story = zenData('story');
$story->loadYaml('story_getformfieldsforedit', false, 2)->gen(10);

$product = zenData('product');
$product->loadYaml('product_getformfieldsforedit', false, 2)->gen(3);

$user = zenData('user');
$user->loadYaml('user_getformfieldsforedit', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getFormFieldsForEditTest(1)) && p('title:name') && e('title'); // 步骤1：正常需求编辑表单字段
r($storyTest->getFormFieldsForEditTest(999)) && p('error') && e('story_not_found'); // 步骤2：不存在的需求ID
r($storyTest->getFormFieldsForEditTest(0)) && p('error') && e('story_not_found'); // 步骤3：无效需求ID
r($storyTest->getFormFieldsForEditTest(2)) && p('product:name') && e('product'); // 步骤4：检查产品字段配置
r($storyTest->getFormFieldsForEditTest(3)) && p('stage:name') && e('stage'); // 步骤5：检查阶段字段配置