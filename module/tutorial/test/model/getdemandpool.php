#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDemandpool();
timeout=0
cid=19419

- 步骤1：正常调用并验证基本信息
 - 属性id @1
 - 属性name @Test demandpool
- 步骤2：验证状态为正常属性status @normal
- 步骤3：验证未删除状态属性deleted @0
- 步骤4：验证创建者和所有者
 - 属性createdBy @admin
 - 属性owner @admin
- 步骤5：验证产品和访问控制
 - 属性products @1
 - 属性acl @open

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($tutorialTest->getDemandpoolTest()) && p('id,name') && e('1,Test demandpool'); // 步骤1：正常调用并验证基本信息
r($tutorialTest->getDemandpoolTest()) && p('status') && e('normal'); // 步骤2：验证状态为正常
r($tutorialTest->getDemandpoolTest()) && p('deleted') && e('0'); // 步骤3：验证未删除状态
r($tutorialTest->getDemandpoolTest()) && p('createdBy,owner') && e('admin,admin'); // 步骤4：验证创建者和所有者
r($tutorialTest->getDemandpoolTest()) && p('products,acl') && e('1,open'); // 步骤5：验证产品和访问控制