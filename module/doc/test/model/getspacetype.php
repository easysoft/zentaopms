#!/usr/bin/env php
<?php

/**

title=测试 docModel::getSpaceType();
timeout=0
cid=16127

- 步骤1：传入有效ID查询产品类型 @product
- 步骤2：传入包含点号的字符串 @project
- 步骤3：传入不存在的ID @0
- 步骤4：传入0作为ID @0
- 步骤5：传入字符串形式的数字ID @project

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-5');
$table->type->range('product,project,execution,custom,mine');
$table->name->range('产品文档库,项目文档库,执行文档库,自定义文档库,我的文档库');
$table->deleted->range('0');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($docTest->getSpaceTypeTest(1)) && p() && e('product');                // 步骤1：传入有效ID查询产品类型
r($docTest->getSpaceTypeTest('project.123')) && p() && e('project');    // 步骤2：传入包含点号的字符串
r($docTest->getSpaceTypeTest(999)) && p() && e('0');                    // 步骤3：传入不存在的ID
r($docTest->getSpaceTypeTest(0)) && p() && e('0');                      // 步骤4：传入0作为ID
r($docTest->getSpaceTypeTest('2')) && p() && e('project');              // 步骤5：传入字符串形式的数字ID