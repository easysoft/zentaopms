#!/usr/bin/env php
<?php

/**

title=测试 storyZen::hiddenFormFieldsForEdit();
timeout=0
cid=0

- 步骤1：普通产品情况
 - 属性product_hidden @0
 - 属性plan_hidden @0
- 步骤2：shadow产品情况
 - 属性product_hidden @1
 - 属性plan_hidden @0
- 步骤3：shadow产品scrum模式
 - 属性product_hidden @1
 - 属性plan_hidden @0
- 步骤4：非多产品项目
 - 属性product_hidden @1
 - 属性plan_hidden @1
- 步骤5：epic类型关闭等级属性parent_hidden @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,影子产品1,影子产品2,普通产品');
$product->shadow->range('0,0,1,1,0');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->model->range('scrum,waterfall,scrum');
$project->multiple->range('1,0,1');
$project->type->range('project{3}');
$project->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 测试步骤（至少5个）
r($storyTest->hiddenFormFieldsForEditTest('normal_product', 'story')) && p('product_hidden,plan_hidden') && e('0,0'); // 步骤1：普通产品情况
r($storyTest->hiddenFormFieldsForEditTest('shadow_product', 'story')) && p('product_hidden,plan_hidden') && e('1,0'); // 步骤2：shadow产品情况
r($storyTest->hiddenFormFieldsForEditTest('shadow_scrum', 'story')) && p('product_hidden,plan_hidden') && e('1,0'); // 步骤3：shadow产品scrum模式
r($storyTest->hiddenFormFieldsForEditTest('shadow_single', 'story')) && p('product_hidden,plan_hidden') && e('1,1'); // 步骤4：非多产品项目
r($storyTest->hiddenFormFieldsForEditTest('normal_product', 'epic')) && p('parent_hidden') && e('1'); // 步骤5：epic类型关闭等级