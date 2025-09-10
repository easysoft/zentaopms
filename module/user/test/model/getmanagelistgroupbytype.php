#!/usr/bin/env php
<?php

/**

title=测试 userModel::getManageListGroupByType();
cid=0

- 测试步骤1：管理员programs权限测试 >> 期望programs类型isAdmin=1
- 测试步骤2：管理员projects权限测试 >> 期望projects类型isAdmin=1
- 测试步骤3：user1 products具体ID权限 >> 期望products类型list包含ID列表
- 测试步骤4：无权限用户返回结果 >> 期望返回0或空数组
- 测试步骤5：user2 executions权限 >> 期望executions类型list包含ID列表

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('projectadmin')->loadYaml('projectadmin_getmanagelistgroupbytype', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->getManageListGroupByTypeTest('admin')) && p('programs:isAdmin') && e('1'); // 步骤1：管理员programs权限测试
r($userTest->getManageListGroupByTypeTest('admin')) && p('projects:isAdmin') && e('1'); // 步骤2：管理员projects权限测试
r($userTest->getManageListGroupByTypeTest('user1')) && p('products:list') && e('1,'); // 步骤3：user1 products具体ID权限
r($userTest->getManageListGroupByTypeTest('noauth')) && p() && e('0'); // 步骤4：无权限用户返回空数组
r($userTest->getManageListGroupByTypeTest('user2')) && p('executions:list') && e('17,'); // 步骤5：user2 executions权限