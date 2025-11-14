#!/usr/bin/env php
<?php

/**

title=测试 docModel::initDocTemplateSpaces();
timeout=0
cid=16139

- 步骤1：检查方法调用是否成功完成 @true
- 步骤2：测试默认空间数量是否正确创建 @12
- 步骤3：测试父级模板空间是否正确创建 @true
- 步骤4：测试子级模板空间是否正确创建 @true
- 步骤5：测试空间基本属性设置是否正确 @true

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('product{5},execution{3},custom{2}');
$table->vision->range('rnd{10}');
$table->name->range('产品库1,产品库2,产品库3,产品库4,产品库5,执行库1,执行库2,执行库3,自定义库1,自定义库2');
$table->acl->range('open{8},private{2}');
$table->addedBy->range('admin{10}');
$table->addedDate->range('2023-09-08 10:00:00{10}');
$table->deleted->range('0{10}');
$table->gen(0); // 清空表，从空表开始测试

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->initDocTemplateSpacesTest()) && p() && e('true'); // 步骤1：检查方法调用是否成功完成
r($docTest->initDocTemplateSpacesTest('count')) && p() && e('12'); // 步骤2：测试默认空间数量是否正确创建
r($docTest->initDocTemplateSpacesTest('checkParentSpace')) && p() && e('true'); // 步骤3：测试父级模板空间是否正确创建
r($docTest->initDocTemplateSpacesTest('checkChildSpace')) && p() && e('true'); // 步骤4：测试子级模板空间是否正确创建
r($docTest->initDocTemplateSpacesTest('checkAttributes')) && p() && e('true'); // 步骤5：测试空间基本属性设置是否正确