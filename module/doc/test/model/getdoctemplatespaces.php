#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocTemplateSpaces();
timeout=0
cid=16086

- 步骤1：正常获取文档模板空间数量 @3
- 步骤2：验证返回值为数组类型 @array
- 步骤3：验证返回数组的第一个键值属性1 @计划模板
- 步骤4：验证数组类型判断 @1
- 步骤5：验证方法执行无错误 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('user')->gen(5);

$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('doctemplate{3},custom{5},product{2}');
$table->name->range('计划模板,开发模板,测试模板,自定义空间1,自定义空间2,自定义空间3,自定义空间4,自定义空间5,产品空间1,产品空间2');
$table->parent->range('0');
$table->deleted->range('0');
$table->vision->range('rnd');
$table->acl->range('open');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($docTest->getDocTemplateSpacesTest())) && p() && e('3');                // 步骤1：正常获取文档模板空间数量
r(gettype($docTest->getDocTemplateSpacesTest())) && p() && e('array');          // 步骤2：验证返回值为数组类型
r($docTest->getDocTemplateSpacesTest()) && p('1') && e('计划模板');             // 步骤3：验证返回数组的第一个键值
r(is_array($docTest->getDocTemplateSpacesTest())) && p() && e('1');             // 步骤4：验证数组类型判断
r(!dao::isError()) && p() && e('1');                                             // 步骤5：验证方法执行无错误