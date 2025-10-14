#!/usr/bin/env php
<?php

/**

title=测试 productZen::getModuleTree();
timeout=0
cid=0

- 步骤1：正常情况获取模块树 @array
- 步骤2：教程模式下获取模块树 @array
- 步骤3：projectstory模块获取模块树 @string
- 步骤4：无效产品ID获取模块树 @array
- 步骤5：不同需求类型获取模块树 @array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('module');
$table->id->range('1-10');
$table->root->range('1-3');
$table->branch->range('0');
$table->name->range('模块1,模块2,模块3,子模块1,子模块2,功能模块,测试模块,开发模块,管理模块,系统模块');
$table->path->range('`,1,`, `,2,`, `,3,`, `,1,4,`, `,1,5,`, `,2,6,`, `,2,7,`, `,3,8,`, `,3,9,`, `,1,10,`');
$table->grade->range('1-2');
$table->type->range('story');
$table->deleted->range('0');
$table->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品A,产品B,产品C,产品D,产品E');
$productTable->type->range('normal{3},branch{2}');
$productTable->status->range('normal');
$productTable->deleted->range('0');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('项目A,项目B,项目C');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->hasProduct->range('1');
$projectTable->deleted->range('0');
$projectTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getModuleTreeTest(0, 1, 'all', 0, 'story', 'unclosed')) && p() && e('array'); // 步骤1：正常情况获取模块树
r($productTest->getModuleTreeTest(0, 1, 'all', 0, 'requirement', 'unclosed', true)) && p() && e('array'); // 步骤2：教程模式下获取模块树
r($productTest->getModuleTreeTest(1, 1, 'all', 0, 'story', 'unclosed', false, 'projectstory')) && p() && e('string'); // 步骤3：projectstory模块获取模块树
r($productTest->getModuleTreeTest(0, 999, 'all', 0, 'story', 'unclosed')) && p() && e('array'); // 步骤4：无效产品ID获取模块树
r($productTest->getModuleTreeTest(0, 1, 'all', 0, 'epic', 'unclosed')) && p() && e('array'); // 步骤5：不同需求类型获取模块树