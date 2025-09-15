#!/usr/bin/env php
<?php

/**

title=测试 productZen::buildSearchFormForBrowse();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性success @1
 - 属性productID @1
- 步骤2：项目故事模式
 - 属性success @1
 - 属性productID @1
- 步骤3：需求类型
 - 属性success @1
 - 属性searchConfigModule @requirement
- 步骤4：无产品项目属性success @1
- 步骤5：搜索浏览类型
 - 属性success @1
 - 属性searchConfigOnMenuBar @yes

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('Product1,Product2,Product3,Product4,Product5,Product6,Product7,Product8,Product9,Product10');
$table->status->range('normal{8},closed{2}');
$table->type->range('normal{7},branch{3}');
$table->program->range('0{5},1{5}');
$table->deleted->range('0');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('Project1,Project2,Project3,Project4,Project5,Project6,Project7,Project8,Project9,Project10');
$projectTable->status->range('wait{3},doing{4},suspended{1},closed{2}');
$projectTable->type->range('project');
$projectTable->hasProduct->range('1{8},0{2}');
$projectTable->model->range('scrum{5},waterfall{3},kanban{2}');
$projectTable->deleted->range('0');
$projectTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, 'all', 0, 'story', 'unclosed', false, '', 0)) && p('success,productID') && e('1,1'); // 步骤1：正常情况
r($productTest->buildSearchFormForBrowseTest(null, 1, 0, 'all', 0, 'story', 'unclosed', true, '', 0)) && p('success,productID') && e('1,1'); // 步骤2：项目故事模式
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, 'all', 0, 'requirement', 'unclosed', false, '', 0)) && p('success,searchConfigModule') && e('1,requirement'); // 步骤3：需求类型
r($productTest->buildSearchFormForBrowseTest((object)array('hasProduct' => '0', 'model' => 'waterfall'), 1, 1, 'all', 0, 'story', 'unclosed', false, '', 0)) && p('success') && e('1'); // 步骤4：无产品项目
r($productTest->buildSearchFormForBrowseTest(null, 0, 1, 'all', 5, 'story', 'bysearch', false, '', 0)) && p('success,searchConfigOnMenuBar') && e('1,yes'); // 步骤5：搜索浏览类型