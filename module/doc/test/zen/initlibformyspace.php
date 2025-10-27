#!/usr/bin/env php
<?php

/**

title=测试 docZen::initLibForMySpace();
timeout=0
cid=0

- 步骤1：用户没有默认个人空间文档库时自动创建属性result @created
- 步骤2：用户已有默认个人空间文档库时不重复创建属性result @exists
- 步骤3：测试不同vision环境下的文档库创建属性result @created
- 步骤4：验证创建的文档库字段值正确性
 - 属性type @mine
 - 属性main @1
 - 属性acl @private
- 步骤5：测试不同用户创建各自的个人空间文档库属性result @created

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-100');
$table->type->range('mine{2},product{3},project{3},custom{2}');
$table->vision->range('rnd{5},lite{5}');
$table->name->range('我的空间,产品文档库,项目文档库,团队空间,默认空间');
$table->main->range('1{3},0{7}');
$table->acl->range('private{5},open{3},default{2}');
$table->addedBy->range('admin,user1,user2,user3,user4');
$table->addedDate->range('`2024-01-01 00:00:00`');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->initLibForMySpaceTest('testuser1')) && p('result') && e('created'); // 步骤1：用户没有默认个人空间文档库时自动创建
r($docTest->initLibForMySpaceTest('admin')) && p('result') && e('exists'); // 步骤2：用户已有默认个人空间文档库时不重复创建
r($docTest->initLibForMySpaceTest('testuser2', 'lite')) && p('result') && e('created'); // 步骤3：测试不同vision环境下的文档库创建
r($docTest->initLibForMySpaceTest('testuser3')) && p('type,main,acl') && e('mine,1,private'); // 步骤4：验证创建的文档库字段值正确性
r($docTest->initLibForMySpaceTest('testuser4')) && p('result') && e('created'); // 步骤5：测试不同用户创建各自的个人空间文档库