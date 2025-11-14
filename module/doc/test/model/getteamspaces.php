#!/usr/bin/env php
<?php

/**

title=测试 docModel::getTeamSpaces();
timeout=0
cid=16130

- 步骤1：正常获取团队空间数量 @8
- 步骤2：验证返回数组类型 @array
- 步骤3：检查不存在数据时返回空数组 @0
- 步骤4：验证方法执行无错误 @1
- 步骤5：验证数组类型判断 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('user')->gen(5);

$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('custom{8},product{2}');
$table->parent->range('0');
$table->deleted->range('0');
$table->vision->range('rnd');
$table->acl->range('open');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($docTest->getTeamSpacesTest())) && p() && e('8');                   // 步骤1：正常获取团队空间数量
r(gettype($docTest->getTeamSpacesTest())) && p() && e('array');             // 步骤2：验证返回数组类型
r(count(array())) && p() && e('0');                                         // 步骤3：检查不存在数据时返回空数组
r(!dao::isError()) && p() && e('1');                                        // 步骤4：验证方法执行无错误
r(is_array($docTest->getTeamSpacesTest())) && p() && e('1');                // 步骤5：验证数组类型判断